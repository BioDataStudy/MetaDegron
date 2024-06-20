import numpy as np
import pandas as pd
import os,re,sys,random
from allennlp.commands.elmo import ElmoEmbedder
import torch
import tensorflow as tf
import keras
from keras import backend as K
K.clear_session()
from keras import layers, optimizers, models, regularizers
from keras.layers import *
from keras.models import *
from keras.regularizers import l2
from keras.optimizers import Adam
from keras.callbacks import ModelCheckpoint, EarlyStopping
from pathlib import Path

def build_model(nodes, dropout, seq_length, weight_decay_lstm=1e-6, weight_decay_dense=1e-3):
    
    embedding_input = Input(shape=(seq_length, 1024))
    
    left1 = Conv1D(128, 8, padding='valid',activation='relu',strides=1)(embedding_input)
    left1 = MaxPooling1D(pool_size=2, strides=None, padding='valid')(left1) 
    
    left2 = Bidirectional(LSTM(nodes, input_shape=(seq_length, 1024), return_sequences=True, dropout=dropout,
                    recurrent_dropout=0.2, kernel_regularizer=l2(weight_decay_lstm),
                    recurrent_regularizer=l2(weight_decay_lstm), bias_regularizer=l2(weight_decay_lstm)))(
        embedding_input)
                    
    left2 = Bidirectional(LSTM(nodes // 2, dropout=dropout, return_sequences=True, recurrent_dropout=0.5,
                    kernel_regularizer=l2(weight_decay_lstm), recurrent_regularizer=l2(weight_decay_lstm),
                    bias_regularizer=l2(weight_decay_lstm)))(left2)                
                    
    left1 = Dense(nodes)(left1)
    left1 = LeakyReLU(alpha=0.01)(left1)
    out_left1 = Flatten()(left1)
    
    left2 = Dense(nodes)(left2)
    left2 = LeakyReLU(alpha=0.01)(left2)
    out_left2 = Flatten()(left2)
    

    seq_input = Input(shape=(seq_length,))
    right = Embedding(27, 10, input_length=seq_length)(seq_input)
    right = Bidirectional(
        LSTM(nodes, return_sequences=True, dropout=dropout,
                    recurrent_dropout=0.2, kernel_regularizer=l2(weight_decay_lstm),
                    recurrent_regularizer=l2(weight_decay_lstm), bias_regularizer=l2(weight_decay_lstm)))(right)
    
    right = Dense(nodes)(right)
    right = LeakyReLU(alpha=0.01)(right)
    out_right = Flatten()(right)

    hidden = concatenate([out_left1, out_left2, out_right])

    hidden = Dense(nodes, kernel_regularizer=l2(weight_decay_dense), bias_regularizer=l2(weight_decay_dense))(
        hidden)

    hidden = LeakyReLU(alpha=0.01)(hidden)

    out = Dense(1, activation='sigmoid', kernel_regularizer=l2(weight_decay_dense),
                       bias_regularizer=l2(weight_decay_dense))(hidden)

    model = models.Model(inputs=[embedding_input, seq_input], outputs=out)

    model.compile(optimizer="adam", loss='binary_crossentropy', metrics=['accuracy'])

    return model