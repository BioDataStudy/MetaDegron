import numpy as np
import pandas as pd
import seaborn as sns
import matplotlib.pyplot as plt
from scipy import stats
from collections import defaultdict  
from sklearn.metrics import accuracy_score, auc, average_precision_score, confusion_matrix, roc_curve, precision_recall_curve, roc_auc_score, recall_score, precision_score

def calculate_metrics(gt, pred_score):

    scores_two = np.array([[1-s, s] for s in pred_score])
    pred = np.argmax(scores_two,axis = 1)

    confusion = confusion_matrix(gt, pred)

    TP = confusion[1, 1]
    TN = confusion[0, 0]
    FP = confusion[0, 1]
    FN = confusion[1, 0]

    P = TP / float(TP + FP)
    R = TP / float(TP + FN)

    print("the result of sklearn package")
    auc = roc_auc_score(gt,pred_score)
    PPV = TP / float(TP + FP)
    accuracy = accuracy_score(gt,pred)
    recal = recall_score(gt,pred)
    precision = precision_score(gt,pred)
    F1_score = (2*recal*precision)/(recal+precision)
    auprc = average_precision_score(gt,pred_score)
    
    return auc, auprc, accuracy, recal, F1_score