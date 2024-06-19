 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>MetaDegron</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
    <meta name="description" content="Degron">
    <meta name="Haodong Xu" content="Degron">
    <!-- Bootstrap core CSS -->
    <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"> -->
	<link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script>-->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/respond.js/1.4.2/respond.min.js"></script>-->
    <style type="text/css">
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
        .aligncenter {
            text-align: center;
        }
        /*BEGIN: search in the home page */
        .md-input {
            position: relative;
            margin:15px 20px;
        }

        .md-input .md-form-control {
            font-size: 16px;
            padding: 10px 10px 10px 5px;
            display: block;
            border: none;
            border-bottom: 2px solid #CACACA;
            box-shadow: none;
            width: 100%;
            color: rgb(5, 77, 136);
        }

        .md-input label {
            color: rgba(0, 0, 0, 0.5);
            font-size: 16px;
            font-weight: normal;
            position: absolute;
            pointer-events: none;
            left: 5px;
            top: 10px;
            transition: 0.2s ease all;
            -moz-transition: 0.2s ease all;
            -webkit-transition: 0.2s ease all;
        }

        .md-input .highlight {
            position: absolute;
            height: 60%;
            width: 100px;
            top: 25%;
            left: 0;
            pointer-events: none;
            opacity: 0.5;
        }

        .md-input .md-form-control:focus ~ label,.md-input .md-form-control:valid ~ label {
            top: -20px;
            font-size: 16px;
            color: #FFF;
        }

        .md-input .md-form-control:focus  .glyphicon,.md-input .md-form-control:valid .glyphicon {
            color: #FFF;
        }

        .imgbox{
            font-size: 0;
            width: 100%;
            height: 100%;
            text-align: center;
                }
        .imgbox img{
            max-height: 80%;
            max-width: 80%;
            vertical-align: middle;}

        /* END: search in the home page */
    </style>
    <script type="text/javascript">

        function submitForm(obj){
            if(!$("#"+obj.id+"_Input").val()){
                alert("Please input the keyword(s) for search, thanks!");
            }else{
                $("#"+obj.id+"_Form").submit();
            }
        }

    </script>
</head>
<body>
<!-- Fixed navbar -->
<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">AI based method for predicting E3 targeted degrons</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse justify-content-end">
            <ul class="nav navbar-nav navbar-right">
                <li class="active"><a href="#">Home</a></li>
                <li><a href="Prediction.php">Prediction</a></li>
                <li><a href="result.php">Results</a></li>
                <li><a href="Tutorial.php">Tutorial</a></li>
                <li><a href="Download.php">Download</a></li>
                <li><a href="Contact.php">Contact</a></li>
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</nav>
<!--        </div>-->
<!--    </div>-->
<!--</nav>-->
<!-- Main jumbotron for a primary marketing message or call to action -->
<div class="jumbotron">
    <div class="container">
        <img src="images/Logo.png" class="img-responsive center-block" />
        <div class="row" style="margin:0px;">
            <p style="text-align: justify"> 
            Targeted protein degradation represents a novel but promising therapeutic modality. The interactions between E3 ubiquitin ligase and degradation signal (degron) determine the degradation specificity and maintain cellular homeostasis.
            Although the human genome encodes over 600 E3 ubiquitin ligases, only a small number of targeted degron instances are identified. 
            Here, we build a user-friendly web service, named <strong>MetaDegron</strong> (<u><b>M</b></u>ultimodal f<u><b>e</b></u>ature integrated <u><b>T</b></u>r<u><b>a</b></u>nsformer for E3 <u><b>degron</b></u>) binding prediction.
            The built-in MetaDegron model shows excellent performance by integrating comprehensive featurization strategies and large protein language models.
            MetaDegron will serve the community for exploring biological mechanisms and implications of protein degradation, as well as drug discovery and design on degrons. 
            </p>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-12 text-left">
            <div class="panel panel-default">
                <div class="panel-heading text-left font-title-default" style="font-size: 20px"><span class="glyphicon glyphicon-tasks" aria-hidden="true"></span> MetaDegron workflow </div>
                    <div class="panel-body">
                    <p class="imgbox"><img src="images/index_workflow.png"></p>
                    <p style="text-align: justify;font-size: 18px;">MetaDegron allows batch prediction of targeted degrons of 21 E3 ligases, providing visualization and functional annotation of multiple degron-related structural and physicochemical features.
                    The built-in models of MetaDegron integrates comprehensive featurization strategies and large protein language model, showing great performance in both hybrid- and pure- deep leaning modes.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
 <div class="row">
    <div class="col-md-7 text-left">
        <div class="panel panel-default">
            <div class="panel-heading text-left font-title-default" style="font-size:20px"><span class="glyphicon glyphicon-level-up" aria-hidden="true"></span> Updates</div>
                    <div class="icon-block">
                        <div class="panel-body" style="height: 260px; overflow: auto; border-top:5px solid #EEE;padding-top:10px;font-size:1.1em">                           
                            <span class="glyphicon glyphicon-ok-circle" aria-hidden="true"></span> 9/17/2023: Some bugs have been fixed for the MetaDegron server. <br><br>
                            <span class="glyphicon glyphicon-ok-circle" aria-hidden="true"></span> 7/15/2023: MetaDegron 1.0 was released for prediction of E3 degron binding by deep learning. <br><br>
                            <span class="glyphicon glyphicon-ok-circle" aria-hidden="true"></span> 3/18/2023: The protein language-based model was implemented and evaluated through 10 fold cross-validation and independent test. <br><br>
                            <span class="glyphicon glyphicon-ok-circle" aria-hidden="true"></span> 10/05/2022: Comprehensive featurization strategies were implemented and evaluated for degron predicion. <br><br>
                            <span class="glyphicon glyphicon-ok-circle" aria-hidden="true"></span> 8/17/2022: Dmultimodal features were evaluated by a XGBoost classifier. <br><br>
                            <span class="glyphicon glyphicon-ok-circle" aria-hidden="true"></span> 6/21/2022: We calculated the enrichment of important PTMs around degrons and their structural properties. <br><br>
                            <span class="glyphicon glyphicon-ok-circle" aria-hidden="true"></span> 4/13/2022: Degron motifs were collected from ELM database and literature. <br><br>
                        </div>
                    </div>
                </div>
            </div>
     <div class="col-md-5">
         <div class="panel panel-default">
             <div class="panel-heading text-left font-title-default" style="font-size:20px"><span class="glyphicon glyphicon-wrench" aria-hidden="true"></span> Developers </div>
             <div class="panel-body" style="font-size:1.1em"><br>
                 <ul>
                     <li><a href="https://www.uth.edu/cph/" style="text-decoration: underline;">Center for Precision Health</a>, UTHealth-Houston</li>
                     <ul style="list-style-type: disc">
                         <li>Dr. Shaofeng Lin </li>
                         <li>Mr. Mengqiu Zheng </li>
                         <li>Dr. Zhongming Zhao </li>
                         <li>Dr. Haodong Xu </li>
                     </ul>
                     <br>
                     <li><a href="https://suoshengbao.github.io/" style="text-decoration: underline;">Suo Lab</a>, Bioland Laboratory</li>
                     <ul style="list-style-type: disc">
                         <li>Dr. Shengbao Suo </li>
                         <li>Rui Liu </li>
                     </ul>
                 </ul>
             </div>
         </div>
 </div>
 <hr>
</div>

</div>
<div class="container">
    <div class="row">
        <div class="col-md-12 text-left">
            <div class="panel panel-default">
                <div class="panel-heading text-left font-title-default" style="font-size: 20px"><span class="glyphicon glyphicon-tasks" aria-hidden="true"></span> Citation </div>
                    <div class="panel-body">
					<p style="text-align: justify;font-size: 18px;">&diams; MetaDegron: Multimodal feature-integrated protein language model for predicting E3 ligase targeted degrons. (Submitted)</p>
                    <p style="text-align: justify;font-size: 18px;">&diams; Xu H, Hu R, Zhao Z. DegronMD: Leveraging Evolutionary and Structural Features for Deciphering Protein-Targeted Degradation, Mutations, and Drug Response to Degrons. <em>Mol Biol Evol</em>. 2023;40(12):msad253. [PMID: <a href="https://pubmed.ncbi.nlm.nih.gov/37992195/" target="_blank">37992195</a>]</p>
                    </div>
                </div>
            </div>
        </div>
		<h4 class = "text-justify"><span style="color:#ca2015" >* MetaDegron is free and open to all users and there is no login requirement.</span></h4>
    </div>
</div>



<!-- Footer -->
	<?php require("Footer.php"); ?>
</div>

<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
<script type="text/javascript">
    $(function(){
        $.get('ipaddress.php', function(data) {
            console.log(data);
        });
    });
</script>
</html>