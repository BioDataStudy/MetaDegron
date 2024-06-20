from sklearn.preprocessing import LabelEncoder
import numpy as np

def AA_encoding(seq_extended):
    amino = "ABCDEFGHIJKLMNOPQRSTUVWXYZ-"
    encoder = LabelEncoder()
    encoder.fit(list(amino))
    seq_transformed = np.array(
        list(map(encoder.transform, np.array([list(i.upper()) for i in seq_extended]))))   
    return seq_transformed[0]