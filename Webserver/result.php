<?php
require 'global.func.php';
$retrieve_flag = 0;
if(isset($_GET["retrieve_id"]) && trim($_GET['retrieve_id']) != '' && !empty($_GET['retrieve_id'])) {
    //if visit result page directly, show the results retrive page.
    $retrieve_flag = 1;
    $retrieve_id = trim($_GET["retrieve_id"]);
    if(substr($retrieve_id,7,1)=="A"){
        header("Location: report.php?retrieve_id=".$retrieve_id,true, 301);
        exit(); 
    }

  }else{
    $retrieve_flag = 0;    
  }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>MetaDegron: Result</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
    <meta name="description" content="DeepTR">
    <meta name="Haodong Xu" content="DeepTR">
	<link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables -->
    <script src="js/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="js/jquery.dataTables.js"></script>
    <script type="text/javascript" charset="utf8" src="js/dataTables.rowGroup.min.js"></script>


    <style type="text/css">
        body{
            font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
            font-size: 14px;
            line-height: 1.42857143;
            color: #333;
        }
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
       .modal-dialog{
         position: absolute;
         left: 50%;
         /* now you must set a margin left under zero - value is a half width your window */
         margin-left: -312px;
         /* this same situation is with height - example */
         height: 500px;
         width: 900px;
         top: 35%;
         margin-top: -250px;
      } 
      .modal-body p {
        font-size: 18px;
      }

	.display_false{display: none;}
	.display_true{}
	#result_table{text-align:center; }
	#result_table th{text-align:center; }
	#result_table td{vertical-align:middle; }
	
	#Dialog{height:auto; display:none;}
	#SubHide{display:none;}
	#Dialog th{border:1px solid black; text-align:center; padding:5px; background-color:#C8E3EE;}
	#Dialog td{border:1px solid black; text-align:center; padding:5px; }
	.Seq{width:600px; height:80px; text-align:left; overflow-y:auto; }
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
                <li><a href="Prediction.php">Prediction</a></li>
                <li class="active"><a href="result.php">Results</a></li>
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


    <!-- Page Content  -->
    <div id="content">
		
        <div class="container">
            <input type="hidden" name="page_content" id="page_content" value="<?php echo $retrieve_flag; ?>">
        <?php if($retrieve_flag==0) {?>
            <h3><span style="color: #7386D5;">Result retrieval</span></h3>
			<h4><button id="SubShow" onclick="submission_list()">Submission Jobs >>></button><button id="SubHide" onclick="Submission_closed()">Submission Hide <<<</button></h4>
			<div id="Dialog">
				<table id="TabDia"  cellpadding="5">
				</table>
			</div>
            <hr>
            <!-- row -->
            <div class="row" >
                <div class="col-md-12" style="font-size:1.89rem;">
                    <p><strong>Important note:</strong> Please do keep a record of the <strong>Job Identifier (JID, e.g., JOB1234_abc123XYZ)</strong>. The result files will be kept for 30 days on our server, then will be deleted. Please download and save your files in time. You can always retrieve the results using the Job Identifier (within 30 days).</p>
                    <p><strong>Example result:</strong> A quick example result page can be checked <strong><u style="color:#ca2015"><a href="result.php?retrieve_id=Example" target="_blank">here</a></u></strong>.</p>

                  </div>
                
            </div>
            <!-- /row -->        

             <div class="row">
                <div class="col-md-12" style="font-size: 1.79rem;">
                  <h3><span style="color: #7386D5;">Please input the Job Identifier:</span></h3>
				<hr>
                  <form id="retrive_form" action="result.php" method="get" enctype="multipart/form-data">
                      <div class="row" > 
                        <div class="col-md-6" style="max-width:600px; margin-left:30px; ">
                         <input type="text" id="retrieve_id_input" required="" name="retrieve_id" placeholder="Input job identifier here" class="form-control">
                        </div>
                        <div class="col-md-2"> 
                          <button class="btn btn-primary" id="retrieve_btn">Retrieve</button>
                        </div>
                      </div>
                  </form>
                </div>
            </div>
        <?php }?>

        <?php if($retrieve_flag==1) {?>
            <h4>
                <span style="color: #7386D5;font-size: 2.29rem;">Job Identifier: </span>
                <span id="id_label" style="font-size: 22px;"><?php echo $retrieve_id;?></span>
            </h4>   
            <hr>
            <!-- row -->
            <div class="row">
                <div class="col-md-12" style="font-size:1.89rem;">
                    <p><strong><u>Important note:</u></strong> Please do keep a record of the <strong>Job Identifier (Job ID, e.g., JOB1234_abc123XYZ)</strong>. The result files will be kept for 30 days on our server, then will be deleted. Please download and save your files in time. You can always retrieve the results using the Job Identifier (within 30 days).</p>
                    <p>The job may take 3-5 minutes to finish. This page will be updated automatelly, you do not need to refresh it.</p>
                </div>
            </div>
            <!-- /row -->        
            <hr>
            <input type="hidden" name="retrieve_id" id="retrieve_id" value="<?php echo $retrieve_id;?>">
            <param name="selected_tissue" id="selected_tissue" value="all">
            <h4>
                <span style="color: #7386D5;font-size: 2.29rem;">Running status:&nbsp;<span id="status_txt">Checking...</span></span>
            </h4>   
            <hr>
            <div class="row">
                <div class="col-md-1 text-center">
                  <div id="checking_icon" class="spinner-border text-primary display_true mystatus" style="width: 1.4rem; height: 1.4rem;" role="status">
                    <span class="sr-only">Checking...</span>
                  </div>
                  <div id="pending_icon" class="spinner-border text-warning display_false mystatus" style="width: 1.2rem; height: 1.2rem;" role="status">
                    <span class="sr-only">Pending...</span>
                  </div>
                  <div id="running_icon" class="spinner-grow text-success display_false mystatus" style="width: 1.4rem; height: 1.4rem;" role="status">
                    <span class="sr-only">Running...</span>
                  </div>
                  <div id="finish_icon" class="badge badge-pill badge-success display_false mystatus" style="width: 1.2rem; height: 1.2rem;" role="status">
                    <span class="sr-only">Finished</span>
                  </div>
                  <div id="failed_icon" class="badge badge-pill badge-danger display_false mystatus" style="width: 1.2rem; height: 1.2rem;" role="status">
                    <span class="sr-only">Failed</span>
                  </div>
                  <div id="noexists_icon" class="badge badge-pill badge-secondary display_false mystatus" style="width: 1.2rem; height: 1.2rem;" role="status">
                    <span class="sr-only">Failed</span>
                  </div>
                </div>
                <div class="col-md-3 text-left">
                  <span class="text-right">Job status: </span><span id="job_status" class="text-left">Checking...</span>
                </div>
                <!--<div class="col-md-4 text-left">
                  <span class="text-right">Submit time: </span><span id="submission_time" class="text-left">Checking...</span>
                </div>
                <div class="col-md-4 text-left">
                   <span class="text-right">Finish time: </span><span id="finish_time" class="text-left">Checking...</span>
                </div>-->
            </div>
            <hr>
            <div class="progress display_true" id="progress_checking">
                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%"></div>
            </div>

            <div class="progress display_false" id="progress_pending">
                <div class="progress-bar progress-bar-striped progress-bar-animated bg-warning" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
            </div>
            <div class="progress display_false" id="progress_runing">
                <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 50%"></div>
            </div>
            <div class="progress display_false" id="progress_finish">
                <div class="progress-bar bg-success" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
            </div>
            <div class="progress display_false" id="progress_failed">
                <div class="progress-bar bg-danger" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
            </div>
            <div class="progress display_false" id="progress_noexists">
                <div class="progress-bar bg-danger" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
            </div>

            <div class="col-md-12 display_false" id="error_link" style="margin-top:30px;">
              <div class="row">
                <div class="col-md-12">
                  <h4 style="width: 100%"><span style='color:red;'>The job you requested does not exist on our XX server! </span></h4>
                </div>
             </div>
            </div>
            <br>
            <div class="display_false" id="result_table_div">
              <h4><span style="color: #7386D5;font-size: 2.29rem;">Results table:</span></h4>
              <table id="result_table" class="table table-sm table-hover border-top" cellspacing="0" width="100%">
                <thead>
                  <tr>
                    <th class="th-sm">Entry</th>
                    <th class="th-sm">E3 ligase</th>
                    <th class="th-sm">Degron instance</th>
                    <th class="th-sm">Degron type</th>
                    <th class="th-sm">START</th>
                    <th class="th-sm">END</th>
                    <th class="th-sm">Score</th>
                    <th class="th-sm">Detail</th>
                  </tr>
                </thead>
                <tbody id="result_tbody">
                  <!-- Results will be show here -->
                </tbody>
              </table>
            </div>
            <div class="row text-center display_false" id="download_link">
              <div class="col-md-12">
                <a class="btn btn-primary btn-sm" href="jobs/<?php echo $retrieve_id; ?>.zip" role="button">Download results</a>
              </div>
            </div>

        <?php }?>
            <br><br>
            
        </div> <!-- ^container  -->
    </div> <!-- ^content  -->

<!-- Footer -->
	<?php require("Footer.php"); ?>



    <!-- jQuery CDN - Slim version (=without AJAX) -->
    <!-- <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script> -->
    
    <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.concat.min.js"></script>-->
    <!-- <script src='js/plotly-2.6.3.min.js'></script>-->
    <script type="text/javascript">
//############################################       
    var page_content = $('#page_content').val();
    var retrieve_id = $('#retrieve_id').val();
    var allele_dict ={};
	// $('#download_link').append(retrieve_id);

	function fileExists(url){
		var isExists;
		$.ajax({url:url, async:false, type:'HEAD', error:function(){isExists=0;},success:function(){isExists=1}});
		if(isExists==1){
			return true;
		}else{
			return false;
		}
	}
	function Sub_del(index){
		var storage=window.localStorage;
		storage.removeItem($("#Sub_"+index).data("info"));
		$("#Sub_"+index).remove();
	}
	function submission_list(){
		var storage=window.localStorage;
		var out="<tr><th width='100px'>No.</th><th>Job Identifier</th><th>E3 ligases</th><th>Sequence</th><th width=100>Console</th></tr>";
		var index=1;
		for(var i=0; i < storage.length; i++){
			var key=storage.key(i);
			if(key.match(/MetaDegron_/)){
				var one=storage.getItem(key).split(",,");
				out=out+"<tr id='Sub_"+index+"' data-info='"+key+"'><td>"+index+"</td><td><a href='result.php?retrieve_id="+one[0]+"'>"+one[0]+"</a></td>";
				out=out+"<td>"+one[1]+"</td>";
				one[2]=one[2].replace(/\n/g,'<br/>');
				out=out+"<td ><div class='Seq'>"+one[2]+"</div></td>";
				out=out+"<td><button onclick='Sub_del("+index+")'>Delete</button></td>";
				out=out+"</tr>\n";
				index++;
			}
		}
		$("#TabDia").html(out);
		$("#SubHide").show();
		$("#SubShow").hide();
		$("#Dialog").show();
		// alert(out);
	}
	function Submission_closed(){
		$("#SubShow").show();
		$("#SubHide").hide();
		$("#Dialog").hide();
	}
	function check_result(){
		fafile="jobs/"+retrieve_id+"/Sequence.fasta";
		fa_status = fileExists(fafile);
		prefile="jobs/"+retrieve_id+"/prediction.txt";
		pre_status = fileExists(prefile);
		//$('#download_link').append("<br/>"+prefile);
		if(pre_status==true){
			$.ajax({url:prefile, dataType:'text', success: function(data){
					var out="";
					var all=data.split("\n");
					//for(l=1; l<all.length-1; l++){
					//	out=out+"<tr>";
					//	one=all[l].split("\t");
					//	out=out+"<td name="+one[0]+" rowspan=1>"+one[0]+"</td>";
					//	out=out+"<td>"+one[1]+"</td>";
					//	out=out+"<td>"+one[2]+"</td>";
					//	out=out+"<td>"+one[3]+"</td>";
					//	out=out+"<td>"+one[4]+"</td>";
					//	out=out+"<td>"+one[5]+"</td>";
					//	out=out+"<td>"+one[6]+"</td>";
					//	out=out+"<td><a>"+one[7]+"</a></td>";
					//	out=out+"</tr>";
					//}
					var id="";
					for(l=1; l<all.length-1; l++){
						out=out+"<tr>";
						one=all[l].split("\t");
						if(one[0]!=id){
							tmp=one[0].replace(/ /g,'_');
							out=out+"<td name="+tmp+" rowspan=1>"+one[0]+"</td>";
							id=one[0];
						}
						out=out+"<td>"+one[1]+"</td>";
						out=out+"<td>"+one[2]+"</td>";
						out=out+"<td>"+one[3]+"</td>";
						out=out+"<td>"+one[4]+"</td>";
						out=out+"<td>"+one[5]+"</td>";
						out=out+"<td>"+one[6]+"</td>";
						if(one[7]=="Yes"){
							out=out+"<td><a href='Degron.php?JID="+retrieve_id+"&PID="+one[0]+"&Degron="+one[2]+"'>More...</a></td>";
						}else{
							out=out+"<td></td>";
						}
						out=out+"</tr>";
					}
					$('#result_tbody').html(out);
					id="";
					var row=1;
					for(l=1; l<all.length-1; l++){
						one=all[l].split("\t");
						if(one[0]!=id){
							id=one[0];
							row=1;
						}else{
							row=row+1;
							$("td[name='"+id.replace(/ /g,'_')+"']").attr("rowspan", row);
						}
					}
					one=all[all.length-1].split("\t");
					if(one[0]==id){
						row=row+1;
						$("td[name='"+id+"']").attr("rowspan", row);
					}
					// $('#result_table').DataTable({columnDefs: [ {targets: [ 0],visible: true} ]});
					$('#result_table_div').removeClass("display_false");
					$('#result_table_div').addClass("display_true");
				}
			});
			// Running status: 
			$('#status_txt').empty();
			$('.mystatus').removeClass("display_true");
			$('.mystatus').addClass("display_false");
			$('#finish_icon').removeClass("display_false");
			$('#finish_icon').addClass("display_true");
			//
			$('.progress').removeClass("display_true");
			$('.progress').addClass("display_false");
			$('#progress_finish').removeClass("display_false");
			$('#progress_finish').addClass("display_true");
			$('#job_status').empty();
			$('#job_status').html("Finshed");
			// Download
			// $('#download_link').removeClass("display_false");
			// $('#download_link').addClass("display_true");
		}else if(fa_status==true){
			//
			$('#status_txt').empty();
			$('#status_txt').html("Running");
			//
			$('.mystatus').removeClass("display_true");
			$('.mystatus').addClass("display_false");
			$('#pending_icon').removeClass("display_false");
			$('#pending_icon').addClass("display_true");
			$('#job_status').empty();
			$('#job_status').html("Running");
			//
			$('.progress').removeClass("display_true");
			$('.progress').addClass("display_false");
			$('#progress_runing').removeClass("display_false");
		}else{	// No fasta file
			$('#status_txt').empty();
			$('#status_txt').html("<span style='color:red;'>Error！</span>");
			// 
			$('#job_status').empty();
			$('#job_status').html("Failed");
			$('.mystatus').removeClass("display_true");
			$('.mystatus').addClass("display_false");
			$('#failed_icon').removeClass("display_false");
			$('.progress').removeClass("display_true");
			$('.progress').addClass("display_false");
			$('#progress_failed').removeClass("display_false");
			$('#id_label').empty();
			$('#id_label').html("<span style='color:red;'>"+retrieve_id+"</span>");
			//
			$('#error_link').removeClass("display_false");
			$('#error_link').addClass("display_true");
		}
	}

	check_result();
	var check_timer = setInterval(check_result, 1000*10);

</script>
</body>

</html>