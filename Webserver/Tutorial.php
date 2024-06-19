<!DOCTYPE html>
<html>
<head>
    <title>MetaDegron: Tutorial</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <style>
        img {
            margin-bottom: 10px;
            margin-top: 15px;
        }
        .jumbotron {
            padding-top: 28px;
            padding-bottom: 5px;
            margin-bottom: 8px;
            color: inherit;
            background-color: #d5d3d3;
        }
        /* Custom Styles */
        ul.nav-tabs{
            width: 300px;
            margin-top: 20px;
            margin-left: -20px;
            border-radius: 4px;
            border: 1px solid #ddd;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.067);
            z-index: 9999 !important;
        }
        ul.nav-tabs li{
            margin: 0;
            border-top: 1px solid #ddd;
        }
        ul.nav-tabs li:first-child{
            border-top: none;
        }
        ul.nav-tabs li a{
            margin: 0;
            padding: 8px 16px;
            border-radius: 0;
        }
        ul.nav-tabs li.active a, ul.nav-tabs li.active a:hover{
            color: #fff;
            background: #0088cc;
            border: 1px solid #0088cc;
        }
        ul.nav-tabs li:first-child a{
            border-radius: 4px 4px 0 0;
        }
        ul.nav-tabs li:last-child a{
            border-radius: 0 0 4px 4px;
        }
        ul.nav-tabs.affix{
            top: 5px; /* Set the top position of pinned element */
            z-index: 9999 !important;
        }
        #content li p{
            font-size: 17px;
            text-align: justify;
        }
        #content li p i{
            font-style: italic;
            font-weight: bold;
         }
        #content p{
            font-size: 19px;
            text-align: justify;
        }
        #content h3{
            color: #ca2015;
        }
        #Fin_page ul li ul li{
            font-size: 17px;
            text-align: left;
        }
        .imgbox{
            font-size: 0;
            width: 100%;
            height: 100%;
            text-align: center;
                }
        .imgbox img{
            max-height: 100%;
            max-width: 100%;
            vertical-align: middle;}
    </style>
</head>
<body data-spy="scroll" data-target="#myScrollspy">
<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.php">AI based method for predicting E3 targeted degrons</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse justify-content-end">
            <ul class="nav navbar-nav navbar-right">
                <li><a href="index.php">Home</a></li>
                <li><a href="Prediction.php">Prediction</a></li>
                <li><a href="result.php">Results</a></li>
                <li class="active"><a href="Tutorial.php">Tutorial</a></li>
                <li><a href="Download.php">Download</a></li>
                <li><a href="Contact.php">Contact</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="jumbotron">
    <div class="container">
        <img src="images/Logo.png" class="img-responsive center-block" />
    </div>
</div>

<div class="container-fluid" style="width: 60%; margin-top: 30px; min-width: 1250px;">
    <div class="row">
        <div class="col-md-3" id="myScrollspy">
            <ul class="nav nav-tabs nav-stacked" data-spy="affix" data-offset-top="300" style="font-size: 15px">
                <li><a href="#section-1" style="font-weight: bold">1. Introduction</a></li>
                <li><a href="#section-2" style="font-weight: bold">2. Model</a></li>
                <li><a href="#section-3" style="font-weight: bold">3. Model performance</a></li>
                <li><a href="#section-4" style="font-weight: bold">4. General webserver pipeline</a></li>
                <li><a href="#section-5" style="font-weight: bold">5. Usage</a></li>
                <li><a href="#section-6" style="font-weight: bold">6. Citation</a></li>
            </ul>
        </div>
        <div class="col-md-9" id="content">
            <h3 id="section-1">1.Introduction</h3>
                    <p>
                    Protein degradation at the spatial and temporal regulation is essential for many cellular processes, including cell cycle progression, signaling, differentiation, and growth,
                    whereas its dysregulation has been implicated in almost all hallmarks of cancer. The rapid development of technologies for targeted protein degradation, such as proteolytic targeted chimeras (PROTACs),
                    enabled many previously non-druggable proteins, providing new insights for drug discovery and design1. Over 80% of intracellular protein degradation in cells is mainly regulated by the ubiquitin-proteasome system (UPS).
                    E3 ubiquitin ligases and degrons, which are short linear motifs embedded within the sequences of modular proteins used by E3 ligases to target proteins, represent the fundamental parts of the UPS.
                    A key property of degron is transferability, i.e., in most cases, transplanting degron from an unstable protein onto another protein accelerates the degradation of that protein, which makes it promising for targeted protein degradation. 
                    In this work, we first systematically analyzed the multimodal biometrics of degron. We found sequences around degron are more evolutionarily conserved, and significantly more phosphorylation and ubiquitination sites are enriched.
                    Structurally, degron was more likely to be located in protein structure regions with high disorder, high solvent accessibility, poor stability and weak rigidity.
                    Based on these findings, wHere, we build a user-friendly web service, named MetaDegron (Multiple feature integrated Transformer) for E3 degron binding prediction. 
                    The built-in MetaDegron model shows excellent performance with AUC value >0.89 by integrating comprehensive featurization strategies and large protein language models.
                    MetaDegron will serve the community for exploring biological mechanisms and implications of protein degradation, as well as drug discovery and design on degrons. 
                </p>
                    <hr>

            <h3 id="section-2">2. Model</h3>
                <p class="imgbox"><img src="images/model.png"></p>
                <center><h4><b>Figure 1</b>. The workflow of MetaDegron for E3 targeted degron prediction.</h6></center>

                <p>First, multiple characteristic features, i.e., disorder, solvent accessibility and secondary structure (COIL, HELIX and SHEET), rigidity, stabilization upon binding, flanking conservation, structured domains, degron-associated phosphorylation sites, degron-associated ubiquitinated lysines, were calculated for each degron instances and random peptides. All characteristic features were evaluated by a XGBoost classifier trained on known degron instances. Moreover, for embedding of degrons and their surrounding sequence, we employed a pre-trained transformer model (SeqVec) to represent each degron by 1024-dimensional embedding. SeqVec was implemented based on the language model using the deep bi-directional LSTM (BLSTM) architecture for protein sequences transferring the knowledge obtained by predicting the next amino acid in 33 million proteins (UniRef50). These representations are capable of accurately depicting the biochemical features for each amino acid. Finally, we leveraged the trained numeric vector encodings of degron instances and all characteristic features for learning the bingding between E3 and degron. We constructed a fully connected deep-learning network based on the output of these two submodels, leading to a final layer with a single neuron for predicting the E3 targeted degron.</p>    
                <!-- <p style="text-align: center"><img height="500" width="600" src="images/transformer.png"></p> -->
                <!-- <center><h4><b>Figure 2</b>. The architecture of transformer model.</h6></center> -->
                <hr>
            <h3 id="section-3">3. Model performance</h3>
			

            <p class="imgbox"><img src="images/MetaDegron-X.jpg"></p>
				<center><h4><b>Figure 2</b>. Implementation and validation of MetaDegron performance.</h6></center>
				<p>Remarkably, the known degrons exhibited a higher degree of solvent accessibility and binding stability compared to the random peptides (Figure 2A, 2B), suggesting their importance in recognition by degradative enzymes. Furthermore, they were found to be preferentially located in protein disordered regions (Figure 2C), highlighting their distinctive localization patterns. Additionally, the analysis revealed a specific preference of degrons for coiled coil regions rather than α-helix regions (Figure 2D, 2E). It was also observed that degrons tend to occur in lower flexibility regions (Figure 2F). These findings provided valuable insights into the structural characteristics of degrons and indicate potential determinants for degron recognition and degradation. Subsequently, the XGBoost classifier (called MetaDegron-X) was constructed using these discerning features for E3 targeted degron. The performance of MetaDegron-X, as assessed by the AUC values, was promising.  Specifically, the AUC values ranged from 0.81 to 0.90 in a five-fold cross-validation, with an average AUC value of 0.87 (Figure 2G). Furthermore, validation of the developed MetaDegron-X was carried out on an independent testing dataset. The performance of MetaDegron-X was superior, as denoted by the AUC value of 0.86 (Figure 2H). </p>
				
			<p class="imgbox"><img src="images/MetaDegron-D.jpg"> </p>
				<center><h4><b>Figure 3</b>. Implementation and validation of MetaDegron performance.</h6></center>
				<p>By incorporating a deep learning framework, MetaDegron-D was capable of solely operating on protein sequences. This novel approach utilized a hybrid architecture comprising cutting-edge deep learning networks (Figure 3A), such as protein language models, word embeddings, convolution, and BLSTM, as thoroughly detailed in the methodology section. This deep learning framework allowes MetaDegron-D to leverage the full potential of these advanced networks and their ability to extract high-level features from protein sequences. The performance evaluation of MetaDegron-D demonstrated its great predictive capabilities. Through a five-fold CV approach, we obtained an average AUC value of 0.90. Furthermore, the AUC values ranged from 0.89 to 0.92, indicating consistent and reliable performance (Figure 3B). Additionally, when tested with an independent dataset, MetaDegron-D achieved an improved AUC value of 0.90 (Figure 3C). </p>
				<p>To further explore the capabilities of the MetaDegron framework, we employed the method implemented in Becht et al. (Becht et al., 2019) to visualize the degrons and random peptides based on their features at each network layer (Figure 3D-I). As expected, the feature representations of the input layer for both degrons and random peptides exhibited significant overlap and mixing (Figure 3D). However, as the framework underwent training, a clear distinction between degrons and random peptides emerged, resulting in more separated clusters within the feature space (Figure 3H, 3I). </p>
			<hr>
            <h3 id="section-4">4. General webserver pipeline:</h3>
            <p class="imgbox"><img src="images/server_workflow.png"></p>
            <center><h4><b>Figure 4</b>. General pipeline for MetaDegron server.</h6></center>
             <p>Our webserver provides user-friendly interfaces for users to submit jobs, check job status, and retrieve results.</p>
            <hr>
            <h3 id="section-5">5. Usage:</h3>
            <h3>Input</h3>
                <p class="imgbox"><img src="images/input.png"></p>
                <center><h4><b>Figure 5</b>. Job submission form.</h4></center>
                <div style="font-size:18px">
                    <ol style="padding-left: 50px;">
                        <li><strong>Job identifier: </strong>Job identifier can be generated automatically or customized by the submitter. It is confidential to other users and can be used for job status monitoring and result retrieval.(See <a href="result.php">Results page</a>).It is required.</li>
                        <li><strong>E3 ligase:</strong> The MetaDegron 1.0 server supports 21 E3 ligase prediction. We constructed a classification tree of E3 ligase.
                         Users can quickly retrieve and submit candidate E3 through the search box and tree map. </li>
                        <li><strong>Sequence:</strong> User can directly copy one or more proteins with FASTA format in the input box.</li>
                        <li><strong>Operation buttons:</strong> Submit, reset the submission form, or access the example dataset.</li> 
                        
                    </ol>
                </div>
                <hr>
                <h3>Output</h3>
                <p class="imgbox"><img src="images/Output.png"></p>
                <center><h4><b>Figure 6</b>. The prediction output.</h4></center>
                <p> After finishing the submitted job, the prediction results will be visualized with specific information, including the “Entry”, “E3 ligase”, “Degron instance”, “Degron type”, “Start”, “End”, and “Score” (Figure 6B). It displays the detailed information for degron and source protein (Figure 7). The properties of degron (Figure 7A) and information of source protein (Figure 7B) are displayed as well. In addition, the structure of source protein is presented with 3Dmol.js (Rego and Koes, 2015), and the degron instance is marked with highlights. Moreover, the multiple sequence alignment (MSA) of degron instance and source protein are visualized by using the ProViz tool (Jehl et al., 2016) (Figure 7C), and the interacting E3s or deubiquitinating enzymes (DUBs) of source protein are provided in a tabular list and an interactive network based on the Cytoscape.js (Franz et al., 2023) (Figure 7D).
                </p>
				<p class="imgbox"><img src="images/Annotation.png"></p>
                <center><h4><b>Figure 7</b>. The feature properties of selected degron and the annotations of source protein.
                <hr>
            <h3 id="section-6">6. Citation:</h3>
            <p> Please cite: <br/>
			<span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> MetaDegron: Multimodal feature-integrated protein language model for predicting E3 ligase targeted degrons. <br/>
			<span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> Xu H, Hu R, Zhao Z. DegronMD: Leveraging Evolutionary and Structural Features for Deciphering Protein-Targeted Degradation, Mutations, and Drug Response to Degrons. <em>Mol Biol Evol</em>. 2023;40(12):msad253. [PMID: <a href="https://pubmed.ncbi.nlm.nih.gov/37992195/" target="_blank">37992195</a>]
            </p>
			<br/>

        </div>
    </div>
</div>
<!-- Footer -->
	<?php require("Footer.php"); ?>

</body>
</html>