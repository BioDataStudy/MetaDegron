# %%
import sys, os, math, tempfile, datetime, time, copy, re
import argparse
import matplotlib.pyplot as plt
from collections import Counter, defaultdict
from keras.models import model_from_json
from math import log
import numpy as np
import matplotlib.pyplot as plt
from keras.layers import Input, Dense
from keras.models import Model 
import pandas as pd
from scipy.stats import percentileofscore
from pathlib import Path
import multiprocessing.pool
from allennlp.commands.elmo import ElmoEmbedder
import torch
from pathlib import Path
from sklearn.preprocessing import LabelEncoder
from calculate_AA_properties import *
os.environ["CUDA_VISIBLE_DEVICES"] = "-1"

dict_E3_degrons = {'CBL': ['CBL APS motif', 'CBL MET motif', 'CBL PTK motif'],
                    'CDC20': ['APC D box', 'APC ABBA/Cdc20','APC ABBA motif','APC KEN box','APC TPR motif'],
                    'COP1': ['COP1 motif'], 'DTL': ['DTL PIP motif 1', 'DTL PIP motif 2'],
                    'CUL3': ['KLHL3/KLH2 motif', 'KLHL17 motif'], 'KLHL3': ['KLHL3/KLH2 motif'],
                    'KLHL2': ['KLHL3/KLH2 motif'], 'KEAP1': ['KEAP1 motif 1', 'KEAP1 motif 2'],
                    'KLHL17': ['KLHL17 motif'], 'MDM2': ['MDM2 motif'], 'VHL': ['VHL motif'],
                    'FBXW7': ['FBXW7 motif 1', 'FBXW7 motif 2'], 'SKP2': ['SKP2 Fbox motif'],
                    'BTRC': ['BTRCP motif'], 'SIAH2': ['SIAH motif'], 'SIAH1': ['SIAH motif'],
                    'SPOP': ['SPOP motif'], 'FBXL2': ['FBXL2 motif'], 'FBXO31': ['FBX031 motif'],
                    'NEDD4': ['ITCH motif'], 'ITCH': ['ITCH motif']}

covariates =["ASA_SCORE","DSS_SCORE","FCONS_SCORE","COIL","ANCHOR_SCORE","nflanking_ptms","nflanking_ub_lysines","HELIX","STRAND","RIG_SCORE","Domain_pfam"]
order = ["Entry","Hit","DEGRON","E3","START","END","Prob_DEGRON","ID_in_UniProt"]

# path_base = "/public/home/hxu6/projects/degron/DegronsDB/" ### Must change the path
path_base = "./" ##must change this path

output_classifier_random  = os.path.join(path_base,"data","classifier_random.pickle")
uid_list = np.load(path_base + '/data/uniprot_list.npy', allow_pickle = True)

path_output_classes = os.path.join(path_base,"data","elm_class_202309.txt")
df_dregon_types = pd.read_csv(path_output_classes,sep="\t")
d_motifs = {}
for index,row in df_dregon_types.iterrows():
    d_motifs[row["Motif"]]=re.compile(row["Regex"])
    
class Elmo_embedder():
    def __init__(self, model_dir="/www/MetaDegron/model/uniref50_v2", weights="weights.hdf5",
                 options="options.json", threads=1000):
        if threads == 1000:
            torch.set_num_threads(multiprocessing.cpu_count() // 2)
        else:
            torch.set_num_threads(threads)

        self.model_dir = Path(model_dir)
        self.weights = self.model_dir / weights
        self.options = self.model_dir / options
        self.seqvec = ElmoEmbedder(self.options, self.weights, cuda_device=-1)

    def elmo_embedding(self, x, start=None, stop=None):
        assert start is None and stop is None, "deprecated to use start stop, please trim seqs beforehand"

        if type(x[0]) == str:
            x = np.array([list(i.upper()) for i in x])
        embedding = self.seqvec.embed_sentences(x)
        X_parsed = []
        for i in embedding:
            X_parsed.append(i.mean(axis=0))
        return X_parsed

# elmo_embedder = Elmo_embedder(threads=60)   

def read_fasta(fasta_file):
    try:
        fp = open(fasta_file)
    except IOError:
        exit()
    else:
        fp = open(fasta_file)
        lines = fp.readlines()
        fasta_dict = {} 
        gene_id = ""
        for line in lines:
            if line[0] == '>':
                if gene_id != "":
                    fasta_dict[gene_id] = seq
                seq = ""
                gene_id = line.strip()[1:]
            else:
                seq += line.strip()        
        fasta_dict[gene_id] = seq       
    return fasta_dict  

def AA_encoding(seq_extended):
    amino = "ABCDEFGHIJKLMNOPQRSTUVWXYZ-"
    encoder = LabelEncoder()
    encoder.fit(list(amino))
    seq_transformed = np.array(
        list(map(encoder.transform, np.array([list(i.upper()) for i in seq_extended]))))   
    return seq_transformed[0]

def import_model(main_dir = '/www/MetaDegron/model/LSTM'):
    json_f = open(main_dir + "/degron_DL.json", 'r')
    loaded_model_json = json_f.read()
    json_f.close()
    loaded_model = model_from_json(loaded_model_json)
    loaded_model.load_weights(main_dir + '/degron_DL.h5')
    return loaded_model

def pred_and_write_metrics_datatable(fasta_file, out_file, degron_motif):
    # degron_motif = ['APC KEN box','APC D box','FBXW7 motif 1']
    # fasta_file = '/public/home/hxu6/projects/degron/DegronsDB/data/test.fasta'
    ID_sequences = read_fasta(fasta_file)

    l_results=[]
    uid_degron = list(ID_sequences.keys())

    for E3_query in degron_motif:
        degron_motif_q = dict_E3_degrons[E3_query]
        
        for uid in uid_degron:
            seq_local = ID_sequences[uid]
            for motif in degron_motif_q:
                for m in re.finditer(d_motifs[motif], seq_local):
                    l_results.append([uid,uid,E3_query,m.group(),motif,m.start()+1,m.end()])
                
    df_matches_seq = pd.DataFrame(l_results,columns=["Entry","Entry_Isoform","E3","Hit","DEGRON","START","END"])   

    all_data = pd.DataFrame()
    
    df_matches_uniprot = df_matches_seq.loc[df_matches_seq["Entry"].isin(uid_list)]
    if len(df_matches_uniprot) > 0:
        print('Calculating biochemical properties...')
        df_properties_degrons = calculate_biochemical_properties_raw(df_matches_uniprot.drop_duplicates(),lysines=(3,20),ptms=11)
        clf = pickle.load(open(output_classifier_random, "rb"))
        p_probs = clf.predict_proba(df_properties_degrons[covariates])
        df_matches_uniprot["Prob_DEGRON"] = [l[1] for l in p_probs]
        df_matches_uniprot["ID_in_UniProt"] = 'Yes'
        
        re_names_pp = {'ASA_SCORE': 'ASA Score', 'CONS_SCORE': 'Conservative score', 'FCONS_SCORE': 'Relative conservative score', 
        'COIL': 'Coil probability','HELIX': 'a-helix probability', 'STRAND': 'β-sheet probability','RIG_SCORE': 'Rigidity',
        'DSS_SCORE': 'Disorder', 'ANCHOR_SCORE': 'Binding stabilization', 'Domain_pfam': 'Structured domains',
        'nflanking_ptms': 'Flanking PTMs number', 'nflanking_lysines': 'Flanking Lysine number'}
        
        df_properties_degrons.rename(columns=re_names_pp, inplace=True)
        df_properties_degrons1 = df_properties_degrons.round(4)
        
        all_data = pd.concat([all_data, df_matches_uniprot])

    #####################
    df_matches_not_uniprot = df_matches_seq.loc[~df_matches_seq["Entry"].isin(uid_list)]

    if len(df_matches_not_uniprot) > 0:
        print('Deep learning models are making predictions...')
        model_DL = import_model()
        elmo_embedder = Elmo_embedder(threads=60)
        shift, slicesize = 24, 49

        d_nouid_degron_pos = {}
        for index, row in df_matches_not_uniprot.iterrows():
            if row["Entry"] not in d_nouid_degron_pos.keys():
                d_nouid_degron_pos[row["Entry"]] = ["_".join([str(row["START"]), str(row["END"]), row["Hit"], row["DEGRON"],row["E3"]])]
            else:
                d_nouid_degron_pos[row["Entry"]].append("_".join([str(row["START"]), str(row["END"]), row["Hit"], row["DEGRON"],row["E3"]])) 

        nouid_degron = list(d_nouid_degron_pos.keys())
        for index, uid in enumerate(nouid_degron):
            headers = d_nouid_degron_pos[uid]
            seq_local = ID_sequences[uid]
            seq_len = len(seq_local)
            seq_local = seq_local.upper()
            seq_local_list = np.array(list(seq_local))

            X_embedding = elmo_embedder.elmo_embedding(seq_local_list)
            protein_pad_global = np.zeros((seq_len + (shift * 2), 1024), dtype=np.float32)

            for i in range(0, seq_len, 1):
                protein_pad_global[i + (shift)] = X_embedding[i]

            protein_pad_local = ["-"] * (seq_len + (shift * 2))
            for i in range(0, seq_len, 1):
                protein_pad_local[i + (shift)] = seq_local[i]
            protein_pad_local = "".join(protein_pad_local)

            X_local, all_seq_transformed, all_seq_elmo_embedding = [], [], []
            for head in headers:
                head_arr = head.split("_")
                start_origin = int(head_arr[0])-1
                stop_origin = int(head_arr[1])
                motif = head_arr[2]
                degron = head_arr[3]
                E3_d = head_arr[-1]
                start = int(head_arr[0]) -1 + shift
                stop = int(head_arr[1]) + shift
                median_pos = (start+stop-1)//2
                slice_start = median_pos - slicesize // 2
                slice_stop = slice_start + slicesize
                query_seq = protein_pad_local[slice_start:slice_stop]
                seq_transformed = AA_encoding([query_seq])
                all_seq_transformed.append(seq_transformed)
                seq_elmo_embedding = protein_pad_global[slice_start:slice_stop]
                all_seq_elmo_embedding.append(seq_elmo_embedding)
                X_local.append([uid, uid, E3_d, motif, degron, start_origin+1, stop_origin])

            probs_ = model_DL.predict([all_seq_elmo_embedding, all_seq_transformed]) 
            df_matches_seq_f = pd.DataFrame(X_local,columns=["Entry","Entry_Isoform","E3","Hit","DEGRON","START","END"])             
            df_matches_seq_f['Prob_DEGRON'] = probs_
            df_matches_seq_f["ID_in_UniProt"] = 'No'

            all_data = pd.concat([all_data, df_matches_seq_f])
        
    # tmp_folder = '/public/home/hxu6/projects/degron/DegronsDB/prediction/' # Please change this path to save result
    tmp_folder = out_file # Please change this path to save result

    all_data1 = all_data[["Entry","E3", "Hit","DEGRON","START","END","Prob_DEGRON","ID_in_UniProt"]]
    all_data1['Prob_DEGRON'] =  all_data1['Prob_DEGRON'].astype(float)
    # all_data1['Prob_DEGRON'] = all_data1['Prob_DEGRON'].apply(lambda x: format(x, '.4f'))
    all_data2 = all_data1.sort_values(by=['Entry','Prob_DEGRON'],ascending=[True, False])
    
    re_names = {'E3': 'E3 ligase', 'Hit': 'Degron instance', 'DEGRON': 'Degron type',
            'START': 'Start', 'END': 'End', 'Prob_DEGRON': 'Score'}
    all_data2.rename(columns=re_names, inplace=True)    
    all_data2 = all_data2.round(4)

    path_output_dataframe = tmp_folder + '/prediction.txt'
    all_data2.to_csv(path_output_dataframe,sep="\t",index=False)

    path_properties_dataframe = tmp_folder + '/prediction_properties.txt'
    df_properties_degrons1.to_csv(path_properties_dataframe,sep="\t",index=False)         
    
    print('Predictions completed, please check the results...')

def main(args): 
    degron_motif = args.motif
    # print(degron_motif)
    fasta_file = args.inputfile
    out_file = args.outfile
    # degron_motif = ['APC KEN box','APC D box','FBXW7 motif 1']
    # fasta_file = '/public/home/hxu6/projects/degron/DegronsDB/data/test.fasta'
    pred_and_write_metrics_datatable(fasta_file, out_file, degron_motif)

if __name__ == '__main__':
    parser = argparse.ArgumentParser(description="Degrons prediction by Deep learning")
    parser.add_argument('-inf', '--inputfile', type=str,
                        help='One file containing predicted sequences.')
    parser.add_argument('-mof', '--motif', type=str, nargs='+',
                        help='Degron motif, spaces separated if more than one.')  
    parser.add_argument('-out', '--outfile', type=str, 
                    help='The output of the predicted result.') 
                     
    args = parser.parse_args()
    main(args)
    # python /public/home/hxu6/projects/degron/DegronsDB/python_202309/Degron_prediction.py -inf '/public/home/hxu6/projects/degron/DegronsDB/data/test.fasta' -mof 'CDC20' 'FBXW7' -out '/public/home/hxu6/projects/degron/DegronsDB/prediction/'