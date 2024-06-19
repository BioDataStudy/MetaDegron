# %%
import pandas as pd
import numpy as np
import sys
import os
import random
from random import randint
import re
import glob
import pickle
# %%
random.seed(2023)
N= 5 # Number of simulated degrons per annotated one

# %%
path_base = "./" ##must change this path
path_ubi_sites = os.path.join(path_base,"data","ubiquitination_sites_human.tsv.gz")
path_sequences = os.path.join(path_base,"data","uniprot_compressed_true_R.tsv")             
path_phosphosites = os.path.join(path_base,"data","phosphorylation_sites_human.tsv.gz")
                              
def get_position(row):
    for mut in row["Protein_Mutations"].split(","):
        if len(mut)>1:
            return mut[1:-1]
                              
def concat(grp):
        l = []
        for value in grp:
            l.append(str(value))
        return ",".join(l)

def find_hit(row,limit,init):
        list_positions = str(row["Position"]).split(",")
        if str(row["Position"]) == "nan":
            return np.nan
        output = []
        degron_range = range(int(row["START"])-limit, int(row["START"]) -init+1)
        degron_range = list(degron_range) + list(range(int(row["END"])+init, int(row["END"]) + limit+1))
        degron_s = set(degron_range)
        for i in range(0, len(list_positions)):
            domain_range = range(int(list_positions[i]), int(list_positions[i]) + 1)
            if len(degron_s.intersection(domain_range)) > 0:
                output.append(list_positions[i])
        if len(output) == 0:
            return np.nan
        return ",".join(output)

def find_lysines_sequence(row,limit,init):
    positions_explore = list(range(int(row["START"])-limit,int(row["START"])-init+1))
    positions_explore = positions_explore + list(range(int(row["END"])+init,int(row["END"])+limit+1))
    seq = row["Sequence"]
    output = []
    for pos in positions_explore:
        if pos <=0:
            continue
        try:
            if seq[pos-1] == "K":
                output.append(str(pos))
        except IndexError:
            continue

    if len(output) == 0:
        return np.nan
    return ",".join(output)

def get_first(grp):
    return list(grp)[0]

def find_sorrounding_lysines(df,limit=11,init=1):
    '''
    Function to find sorrounding lysines in the +/- limit positions
    :param df:
    :param limit: limit to search (default 11 amino acids)
    :param init: start (default next amino acid)
    :return:
    '''
    try:
        if "MOD_RSD" in df.columns.values:
            df.drop(["MOD_RSD"], axis=1, inplace=True)
        if "Position" in df.columns.values:
            df.drop(["Position"], axis=1, inplace=True)
        if "ub_lysines" in df.columns.values:
            df.drop(["ub_lysines"], axis=1, inplace=True)
        if "nflanking_ub_lysines" in df.columns.values:
            df.drop(["nflanking_ub_lysines"], axis=1, inplace=True)
        if "any_lysines" in df.columns.values:
            df.drop(["any_lysines"], axis=1, inplace=True)
        if "nflanking_lysines" in df.columns.values:
            df.drop(["nflanking_lysines"], axis=1, inplace=True)
    except ValueError:
        print ("Creating the columns")

    # Annotate ubiquitination sites

    df_ubi_sites = pd.read_csv(path_ubi_sites,sep="\t",compression="gzip")
    df_ubi_sites.rename(columns={"ACC_ID":"Entry"},inplace=True)
    df_ubi_sites = df_ubi_sites.groupby("Entry",as_index=False).agg({"MOD_RSD":concat,"Position":concat})
    df = pd.merge(left=df,right=df_ubi_sites[["MOD_RSD", "Entry", "Position"]].drop_duplicates(),how="left")
    df["ub_lysines"] = df.apply(lambda row: find_hit(row,limit,init),axis=1)
    df["nflanking_ub_lysines"] =  df.apply(lambda row: 0 if str(row["ub_lysines"])=="nan" else len(str(row["ub_lysines"]).split(",")),axis=1)

    # Annotate lysines (either ubiquitinated or not)

    df_sequences = pd.read_csv(path_sequences, sep=",")
    df_sequences=df_sequences.groupby(["Entry_Isoform"],as_index=False).agg({"Sequence":get_first}) # Make only one sequence per isoform
    df_query = pd.merge(df_sequences,df[["Entry_Isoform","START","END","DEGRON"]].drop_duplicates())
    df_query["any_lysines"] = df_query.apply(lambda row: find_lysines_sequence(row,limit,init),axis=1)
    df_query["nflanking_lysines"] =  df_query.apply(lambda row: 0 if str(row["any_lysines"])=="nan" else len(str(row["any_lysines"]).split(",")),axis=1)

    df = pd.merge(left=df, right=df_query[["any_lysines","nflanking_lysines", "Entry_Isoform","START","END","DEGRON"]].drop_duplicates(), how="left")

    return df

def find_sorrounding_phospho(df,limit=11,exclude=False):
    '''
    Function to calculate sorrounding phosphorilation sites
    :param df: the input dataframe
    :param limit: end of the search (default 11 amino acids)
    :param exclude: Whether the degron should be included or not, default included
    :return:
    '''
    try:
        if "MOD_RSD" in df.columns.values:
            df.drop(["MOD_RSD"], axis=1, inplace=True)
        if "Position" in df.columns.values:
            df.drop(["Position"], axis=1, inplace=True)
        if "ptms_flanking" in df.columns.values:
            df.drop(["ub_lysines"], axis=1, inplace=True)
        if "nflanking_ptms" in df.columns.values:
            df.drop(["nflanking_ub_lysines"], axis=1, inplace=True)
              
    except ValueError:
        print ("Error in creation of the columns")

    df_ptm_sites = pd.read_csv(path_phosphosites,sep="\t",compression="gzip")
    df_ptm_sites.rename(columns={"ACC_ID":"Entry"},inplace=True)
    df_ptm_sites = df_ptm_sites.groupby("Entry",as_index=False).agg({"MOD_RSD":concat,"Position":concat})
    df = pd.merge(left=df,right=df_ptm_sites[["MOD_RSD", "Entry", "Position"]].drop_duplicates(),how="left")
    df["ptms_flanking"] = df.apply(lambda row: find_hit(row,limit,exclude),axis=1)
    df["nflanking_ptms"] =  df.apply(lambda row: 0 if str(row["ptms_flanking"])=="nan" else len(str(row["ptms_flanking"]).split(",")),axis=1)
    return df

'''
Paths to fetch the properties

'''
# path_base1 = "/public/home/hxu6/projects/degron/DegronsDB/python/bbglab_analysis_degradation/data"
path_base1 = "./properties/data" ##Must change the path

path_base_sequences = os.path.join(path_base1,"sequences")
path_base_sequences_msa = os.path.join(path_base1,"sequences","MSA")
path_base_sequences_msa_scores = os.path.join(path_base1,"sequences","MSA","Scores/")
path_base_features = os.path.join(path_base1,"features/")
path_base_features_asa = os.path.join(path_base_features,"ASA/")
path_base_features_anchor = os.path.join(path_base_features,"ANCHOR/")
path_base_features_dss = os.path.join(path_base_features,"DSS/")
path_base_features_flex = os.path.join(path_base_features,"FLEX/")

def get_cons(row):
    '''
    Return the conservation of the degron (average of amino acids)
    :param row:
    :return:
    '''
    file_f = path_base_sequences_msa_scores + row["Entry"] + ".score"
    try:
        df_entry = pd.read_csv(file_f, sep="\t", comment='#', skiprows=1, names=["RES_NUM_OLD", "RES", "CONS"])
        df_entry["RES_NUM"] = range(1, df_entry.shape[0] + 1)
    except:
        return np.nan
    return np.nanmean(
        list(df_entry[(df_entry["RES_NUM"] >= row["START"]) & (df_entry["RES_NUM"] <= row["END"])]["CONS"].values))

def get_cons_flanking(row, flanking_pos=11):
    '''
    Calculate the flanking conservation of a degron compared to N flanking pos
    :param row: the dataframe row
    :return:
    '''
    file_f =  path_base_sequences_msa_scores + row["Entry"] + ".score"
    try:
        df_entry = pd.read_csv(file_f, sep="\t", comment='#', skiprows=1, names=["RES_NUM_OLD", "RES", "CONS"])
        df_entry["RES_NUM"] = range(1, df_entry.shape[0] + 1)
    except: # File not found
        return np.nan
    degron = np.nanmean(
        list(df_entry[(df_entry["RES_NUM"] >= row["START"]) & (df_entry["RES_NUM"] <= row["END"])]["CONS"].values)) # Calculate the mean conservation of the degron

    flanking = np.nanmean(list(df_entry[((df_entry["RES_NUM"] >= row["START"] - flanking_pos) & (
            df_entry["RES_NUM"] < row["START"])) | ((df_entry["RES_NUM"] > row["END"]) & (
            df_entry["RES_NUM"] <= row["END"] + flanking_pos))]["CONS"].values)) # Compare with the mean conservation of the flanking pos

    return (degron / flanking)

def get_dss(row):
    '''
    Calculate the mean DSS of the degron
    :param row:
    :return:
    '''
    file_f = path_base_features_dss + row["Entry_Isoform"] + ".fa"

    try:
        df_entry = pd.read_csv(file_f, sep="\t", comment='#', names=["RES_NUM", "RES", "DSS"])
    except:
        return np.nan
    return np.nanmean(
        list(df_entry[(df_entry["RES_NUM"] >= row["START"]) & (df_entry["RES_NUM"] <= row["END"])]["DSS"].values))

def get_anchor(row):
    '''
    Return the average ANCHORING score of the degron
    :param row:
    :return:
    '''
    file_f = path_base_features_anchor + row["Entry_Isoform"] + ".fa.out"

    try:
        df_entry = pd.read_csv(file_f, sep="\t", comment='#', names=["RES_NUM", "RES", "ANCHOR", "OUT"])
    except:
        return np.nan
    return np.nanmean(
        list(df_entry[(df_entry["RES_NUM"] >= row["START"]) & (df_entry["RES_NUM"] <= row["END"])]["ANCHOR"].values))

def get_flex_protein(row):
    '''
    Return the average Rigidity of the degron
    :param uniprot_isoform:
    :return:
    '''
    file_f = path_base_features_flex + row["Entry_Isoform"] + '_backbone.pred'

    try:
        df_entry = pd.read_csv(file_f, sep=",", skiprows=1, names=["RES", "FLEX"])
    except:

        return np.nan
    df_entry["Entry_Isoform"] = row["Entry_Isoform"]
    df_entry["FLEX"].fillna(0.0,inplace=True)
    df_entry["RES_NUM"] = range(1, df_entry.shape[0] + 1)
    rig = np.nanmean(list(df_entry[(df_entry["Entry_Isoform"] == row["Entry_Isoform"]) & (
            df_entry["RES_NUM"] >= row["START"]) & (df_entry["RES_NUM"] <= row["END"])]["FLEX"].values))
    return rig

def get_asa_protein(row):
    '''
    Returns the average ASA of the degron
    :param uniprot_isoform:
    :return:
    '''
    try:
        file_f = path_base_features_asa + row["Entry_Isoform"] + ".spd3"
        df_ss = pd.read_csv(file_f, sep="\t", skiprows=1,
                               names=["RES_NUM", "RES", "SS", "ASA", "Phi", "Psi", "Theta(i-1=>i+1)", "Tau(i-2=>i+1)",
                                      "P(C)", "P(E)", "P(H)"])
        df_ss["Entry_Isoform"] = row["Entry_Isoform"]
        values_c = np.nanmean(list(df_ss[(df_ss["Entry_Isoform"] == row["Entry_Isoform"]) & (df_ss["RES_NUM"] >= row["START"]) & (
                df_ss["RES_NUM"] <= row["END"])]["P(C)"].values))
        values_h = np.nanmean(list(df_ss[(df_ss["Entry_Isoform"] == row["Entry_Isoform"]) & (df_ss["RES_NUM"] >= row["START"]) & (
                df_ss["RES_NUM"] <= row["END"])]["P(H)"].values))
        values_e = np.nanmean(list(df_ss[(df_ss["Entry_Isoform"] == row["Entry_Isoform"]) & (df_ss["RES_NUM"] >= row["START"]) & (
                df_ss["RES_NUM"] <= row["END"])]["P(E)"].values))
        values_asa = np.nanmean(list(df_ss[
                                         (df_ss["Entry_Isoform"] == row["Entry_Isoform"]) & (
                                                 df_ss["RES_NUM"] >= row["START"]) & (
                                                 df_ss["RES_NUM"] <= row["END"])]["ASA"].values))
        return values_asa, values_c, values_h, values_e

    except:
        return np.nan, np.nan, np.nan, np.nan
'''
Load the data
'''
def load_pfam_grouped():
    '''
    Loads the data for calculation of features
    :param simulated: Whether perform the z-score calculation
    :param simulated_path: Path of default simulated dataframe
    :return:
    '''
    path_pfam = os.path.join(path_base,"data","PFAM_data.tsv")
    df_pfam = pd.read_csv(path_pfam, sep=",", comment="#")
    df_pfam = df_pfam[df_pfam["TYPE"] == "Domain"][["Entry", "E_START", "E_END", "PFAM_ID"]].drop_duplicates()
    df_pfam_grouped = df_pfam.groupby("Entry", as_index=False).agg({"E_START": concat, "E_END": concat, "PFAM_ID": concat})
    return df_pfam_grouped

'''
Calculate basic properties
'''
def calculate_basic_properties(df):
    '''
    :param df: a dataframe with columns "Entry","Entry_Isoform","START","END","DEGRON"
    :return: includes in the dataset the columns for ASA,DSS,COILD,HELIX,STRAND,ANCHOR,FLEX,CONS,FLANKING_CONS
    '''
    list_scores = []
    for index, row in df.iterrows():
        # get dss
        try:
            dss = get_dss(row)
        except:
            dss = np.nan
        try:
            # get asa, c, h, e
            asa, c, h, e = get_asa_protein(row)
        except:
            asa, c, h, e = np.nan, np.nan, np.nan, np.nan 
            # get anchor
        try:
            anchor = get_anchor(row)
        except:
            anchor = np.nan
        try:
            # get flexibilty
            values_flex = get_flex_protein(row)
        except:
            values_flex = np.nan
        try:
            # get conservation
            cons = get_cons(row)
        except: 
            cons = np.nan
        try:
            # get conservation compared to flaking
            fcons = get_cons_flanking(row)
        except:
            fcons = np.nan

        list_scores.append(
            [row["Entry"],row["Entry_Isoform"], row["START"], row["END"], row["DEGRON"], asa, dss, c, h, e,
             anchor, values_flex, cons, fcons])
    score_degrons = pd.DataFrame(list_scores,
                                           columns=["Entry", "Entry_Isoform", "START", "END", "DEGRON", "ASA_SCORE", "DSS_SCORE", "COIL", "HELIX",
                                                    "STRAND", "ANCHOR_SCORE", "RIG_SCORE", "CONS_SCORE", "FCONS_SCORE"])
    return score_degrons
'''
Functions to include the pfam domain
'''
def find_hit_pfam(row):
    list_pfams = row["PFAM_ID"].split(",")
    if len(list_pfams) == 0 or row["PFAM_ID"]=="None":
        return "None"
    list_start = str(row["E_START"]).split(",")
    list_end = str(row["E_END"]).split(",")
    output = []
    degron_range = range(int(row["START"]),int(row["END"])+1)
    degron_s = set(degron_range)
    for i in range(0,len(list_pfams)):
        domain_range = range(int(list_start[i]),int(list_end[i])+1)
        if len(degron_s.intersection(domain_range)) > 0:
            output.append(list_pfams[i])
    if len(output) == 0:
        return "None"
    return ",".join(output)

def add_pfam(df,df_pfam):
    '''
    :param df: dataframe query
    :param df_pfam: dataframe of grouped pfam
    :return: df with the pfam information
    '''
    df = pd.merge(left=df_pfam[["Entry", "E_START", "E_END", "PFAM_ID"]].drop_duplicates(),
                                       right=df, left_on=["Entry"], right_on=["Entry"],
                                       how="right")
    df["PFAM_ID"].fillna("None", inplace=True)
    df["hit_pfam"] = df.apply(lambda row: find_hit_pfam(row), axis=1)
    df["Domain_pfam"] = df.apply(lambda row: 0 if row["hit_pfam"] == "None" else 1,
                                                                      axis=1)
    df.drop(["E_START", "E_END", "PFAM_ID"], axis=1, inplace=True)
    return df

'''
Global function
'''
def calculate_biochemical_properties_raw(df, lysines=(5,10), ptms=10):
    '''
    :param df:
    :return:
    '''
    df_pfam = load_pfam_grouped()
    dg = calculate_basic_properties(df)
    dh = add_pfam(dg,df_pfam)
    di = find_sorrounding_lysines(dh,limit=lysines[1],init=lysines[0])
    dj = find_sorrounding_phospho(di,limit=ptms)

    list_values = []
    for index, row in dj.iterrows():
        list_values.append(
            [row["Entry"], row["Entry_Isoform"], row["DEGRON"], row["START"], row["END"],row["ASA_SCORE"],row["CONS_SCORE"],row["FCONS_SCORE"],row["COIL"],row["HELIX"],row["STRAND"],row["DSS_SCORE"]
            ,row["RIG_SCORE"],row["ANCHOR_SCORE"],row["Domain_pfam"],row["nflanking_ub_lysines"],row["ub_lysines"],row["nflanking_lysines"],row["any_lysines"],row["nflanking_ptms"],row["ptms_flanking"]])

    zscore_degrons = pd.DataFrame(list_values,
                    columns=["Entry", "Entry_Isoform", "DEGRON", "START", "END","ASA_SCORE","CONS_SCORE",
                                "FCONS_SCORE", "COIL", "HELIX","STRAND","DSS_SCORE", "RIG_SCORE", "ANCHOR_SCORE",
                                "Domain_pfam", "nflanking_ub_lysines","ub_lysines", "nflanking_lysines","any_lysines", "nflanking_ptms","ptms_flanking"])
    zscore_degrons.fillna(0.0, inplace=True)
    df_final = pd.merge(zscore_degrons,df,how="right")
    return df_final
