<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>MetaDegron: Prediction</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="description" content="Degron">
    <meta name="Haodong Xu" content="Degron">
	<!-- Bootstrap core CSS -->
	<!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"> -->
	<link href="css/bootstrap.min.css" rel="stylesheet">
	
    <script src="js/jquery.min.js"></script>
    <link rel="stylesheet" href="css/metroStyle/metroStyle.css" type="text/css"> 
    <script type="text/javascript" src="js/jquery.ztree.core.js"></script>
    <script type="text/javascript" src="js/jquery.ztree.excheck.js"></script>
    <script type="text/javascript" src="js/jquery.ztree.exhide.js"></script>
    <script type="text/javascript" src="js/fuzzysearch.js"></script>
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
        .table>thead>tr>th {padding: 5px;}
        .table_scroll {
            height:300px;
            overflow-y:auto;    
            /*margin-top:20px;*/
        }
		button{margin-right:10px; }
		#hla_table th{text-align:center; }
    </style>
</head>
<body>
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
                <li class="active"><a href="Prediction.php">Prediction</a></li>
                <li><a href="result.php">Results</a></li>
                <li><a href="Tutorial.php">Tutorial</a></li>
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

<div class="container">
    <h3><span style="color: #7386D5;">Multiple feature integrated transformer for E3 targeted degron prediction with protein language model</span></h3>
        <hr style="margin-top: 0; ">
            <div class="row">
              <div class="col-md-12" style="font-size:2.09rem;text-align: justify;">
                <p>
                MetaDegron is a user-friendly web service for predicting E3 targeted degrons.
                This tool enables users to perform batch prediction of targeted degron for <b>21</b> E3 ligases, 
                while also providing functional annotations and visualizations of various degron-related structural and physicochemical features.</p>

                <p><strong>Important note:</strong> Each job may take 3-5 minutes to finish. You may retrive the 
                results anytime using the <strong>Job Identifier (JID, e.g., JOB1234_1234567890)</strong>. Please do keep a record of the JID.</p>
				 <p><strong>Example result:</strong> A quick example result page can be checked <strong><u style="color:#ca2015"><a href="result.php?retrieve_id=Example" target="_blank">here</a></u></strong>.</p>
              </div>

            </div>
           
            <hr>
            <h3><span style="color: #7386D5;">Input parameters</span></h3> 

            <br>
			<form id="submit_form" action="submit_job.php" method="post" enctype="multipart/form-data" >
				<fieldset class="form-group">
					<div class="row" style="height:500px; width:920px; border: 1px solid #ccc; margin:0 auto; padding:10px; border-radius:10px;">
						<div style="padding:10px;">
							<label for="job_id_pre" style="font-size:1.8rem;">Job identifier: </label>
							<input required readonly type="text" id="job_id_pre" name="job_id_pre" style="width:110px; margin-right: 3px; background-color: #eee; border-radius:3px;">
							<input required="" type="text" id="job_id_txt" name="job_id_txt"  style="width:110px;" placeholder="Enter a descriptive title for your job">
						</div>
                        <br>
						<input type="hidden" name="submit_page" id="submit_page" value="submit_page">  
						<div class="col-md-2" style="width: 262px;">
							<label for="hla_allele" style="font-size:1.8rem;">E3 ligase: </label>
							<br/>
							<input type="text"  id="key" name = "key" placeholder="Search E3"/>
							<br/>
							<div class="table_scroll overflow-auto" style="max-height: 300px; min-height:310px; border: 1px solid #ccc; margin-top: 5px; width:242px;">
							<ul id="hla_tree" class="ztree" style="margin-left: 10px;"></ul>
							</div>
							
						</div>
						<div>
							<div style="height:300px;">
								<div class="col-md-2" style="width:240px;">
									<label for="hla_allele" style="font-size:1.8rem;">E3 selection: </label>
									<div class="table_scroll" style="max-height: 305px; min-height:305px; border: 1px solid #CCC;  ">
										<table class="table  table-sm" id = "hla_table">
											<thead>
											<tr>
												<th class="text-center">Check</th>
												<th>Degron(s)<br/>(n = <span id="checkCount" style="color: red;">0</span>; <a style="cursor: pointer;" onclick="clearhla()"><u>clear all</u></a>) </th>
											</tr>
											</thead>
											<tbody id="hla_tbody">
											</tbody>
										</table>
									</div>
								</div>
								<div class="col-md-2" style="width:388px">
									<label for="seq_input" style="font-size:1.8rem;">Sequence: </label>
									<p style="font-size:1.7rem;color: #7386D5;">Enter protein sequence(s) in fasta format, which starts with a '>' followed with protein/peptide name. </u></p>
									<textarea class="form-control" required="" name="TCR_input" id="TCR_input" rows="6" style="width:65%; min-width: 370px; height:225px; " placeholder="INPUT FORMAT (FASTA): "></textarea> 
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<label class="col-md-2 control-label" style="font-size:1.8rem;">Console: </label>
								<br/>
								<p>
									<button type="button" class="btn btn-primary" onclick="set_example();">Example</button>
									<button type="button" class="btn btn-secondary" onclick="location.reload();">Reset</button>
									<button type="button" class="btn btn-success" onclick="submit_form()">Submit</button>
								</p>
							</div>
						</div>
						
					</div>
				</fieldset>
			</form>

            <p style="font-size:2.2rem;color: #ca2015;">How to use MetaDegron for E3 targeted degron prediction</p>
            <p style="font-size:1.8rem">1. Select one or more E3 ligases. The current version of MetaDegron supports <b>21</b> E3 ligase prediction.</p>
            <p style="font-size:1.8rem">2. Enter protein sequence(s) in fasta format, which starts with a '>' followed with protein/peptide name.</p>
            <p style="font-size:1.8rem">3. Click the Submit button. 
            </p>
            <br>
</div>

<!-- Footer -->
	<?php require("Footer.php"); ?>


<!-- Font Awesome JS -->
<script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js"></script>
<script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js"></script>
<!-- Popper.JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
<!-- Bootstrap JS -->
<!-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>-->
<!-- jQuery Custom Scroller CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.concat.min.js"></script>

<script type="text/javascript">
$(document).ready(function () {
    $("#sidebar").mCustomScrollbar({
        theme: "minimal"
    });

    $('#sidebarCollapse').on('click', function () {
        $('#sidebar, #content').toggleClass('active');
        $('.collapse.in').toggleClass('in');
        $('a[aria-expanded=true]').attr('aria-expanded', 'false');
    });
});

function getRndInteger(min, max) {
    return Math.floor(Math.random() * (max - min) ) + min;
}

function set_job_id(){
    str = getRndInteger(1000,10000);

    $('#job_id_pre').empty();
    $('#job_id_pre').val('JOB'+str);

    myDate = new Date();
    str = myDate.getTime().toString();
    str = str.slice(3);

    $('#job_id_txt').empty();
    $('#job_id_txt').val(str);

};
set_job_id();

function reset(){
    location.reload();  
}


//##################
var setting = {
    check: {
        enable: true,
    },
    data: {
        simpleData: {
        enable: true
        }
    },
    callback: {
        onCheck:onCheck
    }
};

<?php
// If strict types are enabled i.e. declare(strict_types=1);
$Tree_str = file_get_contents('model/Model-Tree.txt');
if($Tree_str==false){
    echo("alert('Error in reading configuration file, please contact the author.')");
    $tree_str = '{id:"Error", pId:"-1", name:"Error in reading configuration file, please contact the author. ",chkDisabled:true}';
}else{
    $tree_str=$Tree_str;
}

?>

var zNodes =[<?php echo $tree_str; ?>];

$(document).ready(function(){
    $.fn.zTree.init($("#hla_tree"), setting, zNodes);
    fuzzySearch('hla_tree','#key',null,true); //initialize fuzzysearch function
});

var hla_array = {};
var limit = 20;
var count_n = 0;

function onCheck(e, treeId, treeNode) {
    
    $("#hla_tbody").empty();
    $("#checkCount").text("0");

    var treeObj = $.fn.zTree.getZTreeObj("hla_tree");
    var nodes = treeObj.getCheckedNodes(true);
    for (var i=0, l=nodes.length; i<l; i++) {                   
        if(!nodes[i].isParent){
            if(!hla_array.hasOwnProperty(nodes[i].id)){
                hla_array[nodes[i].id] = {"id":nodes[i].id,"name":nodes[i].name}
            }    
        }
    }

    var nochecked_nodes = treeObj.getCheckedNodes(false);
    for (var i=0, l=nochecked_nodes.length; i<l; i++) {
        if(!nochecked_nodes[i].isParent){
            if(hla_array.hasOwnProperty(nochecked_nodes[i].id)){
                delete hla_array[nochecked_nodes[i].id];   
            }    
        }
    }


    var tr_str = "";
    count_n = 0;
    for (var node_i in hla_array) {
        if(count_n == limit){
            alert("More than "+limit+" were selected, only the first "+limit+" will be used !");
            break;
        }
        tr_str += "<tr>";
        tr_str += "<td align='center'><input class='hla_checkbox' type='checkbox' name='hlaid[]' value='"+node_i+"' checked></td>";
        tr_str += "<td>"+hla_array[node_i].name+"</td>";
        tr_str += "</tr>";
        
        count_n += 1;

    }

    $("#hla_tbody").html(tr_str);
    $("#checkCount").text(count_n);
    
}

function clearhla() {
hla_array = {};
var zTree = $.fn.zTree.getZTreeObj("hla_tree");
zTree.checkAllNodes(false);
    
    $("#hla_tbody").empty();
    $("#checkCount").text("0");
    
}

$(document).on("click","input.hla_checkbox",function(){ 
    if($(this).is(":checked")){
        console.log("Checkbox is checked.");
    }else if($(this).is(":not(:checked)")){
        var hlaid = $(this).val();
        var currentRow=$(this).closest("tr"); 
        currentRow.remove();
        count_n -= 1;

        $("#checkCount").text(count_n);

        if(hla_array.hasOwnProperty(hlaid)){
            delete hla_array[hlaid];   
        }
        
        var zTree = $.fn.zTree.getZTreeObj("hla_tree"),
        nodes = zTree.getCheckedNodes(true);
        for (var i=0, l=nodes.length; i<l; i++) {
            if(nodes[i].id == hlaid){
                zTree.checkNode(nodes[i], false, false, false);
            }
        }

        if(count_n==0){
            zTree.checkAllNodes(false);
        }
    }
        
});

function submit_form(){

    var TCR_input = $('#TCR_input').val()
    var antigen_input = $('#antigen_input').val()

    var hla_ls = Array();
    $("input[name='hlaid[]']").each(function () {
        hla_ls.push($(this).val());
    });

    if(TCR_input.trim()==""){
        alert("Sequence input is empty!");
    }else if(hla_ls.length==0){
		alert("No E3 ligase is selected");
    }else{
		var $job_id=$("#job_id_pre").val()+"_"+$("#job_id_txt").val();
		var $info=$job_id+",,"+hla_ls+",,"+TCR_input;
		var storage=window.localStorage;
		i="MetaDegron_"+(storage.length+1);
		storage.setItem(i,$info);
		// alert(i+": "+ storage.getItem(i));
		$( "#submit_form" ).submit();
    }
}


function set_example(){
    $.post('get_data.php', {type: 'TCR' }, function(data) {
            $("#TCR_input").val(data);     
        });
    // set HLA alleles
    var tr_str = "";
    count_n = 0;
    hla_example = ['FBXW7','CDC20'];
    for (node_i of hla_example) {
        if(count_n == limit){
            alert("More than "+limit+" were selected, only the first "+limit+" will be used !");
            break;
        }
        tr_str += "<tr>";
        tr_str += "<td align='center'><input class='hla_checkbox' type='checkbox' name='hlaid[]' value='"+node_i+"' checked></td>";
        tr_str += "<td>"+node_i+"</td>";
        tr_str += "</tr>";
        
        count_n += 1;

    }

    $("#hla_tbody").html(tr_str);
    $("#checkCount").text(count_n);

}
</script>

</body>
</html>
