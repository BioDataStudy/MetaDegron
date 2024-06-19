
<?php
require 'global.func.php';

if(isset($_POST["msg"])){
  $msg=$_POST["msg"];
}else{
    $msg="OK";
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST["submit_page"])) {
			//echo $TCR_input."<br/>";
          $job_id = $_POST["job_id_pre"]."_".$_POST["job_id_txt"];
          $TCR_input = $_POST["TCR_input"];
          $hla_ls = $_POST["hlaid"];

          if ( !file_exists( "jobs/".$job_id ) && !is_dir( "jobs/".$job_id ) ) {
                  mkdir( "jobs/".$job_id,0777 );
                  $txt = "";
                  # $jobinfo_fp = fopen("jobs/".$job_id."/job_info.txt", "w");
                  $txt.= "job_id:\t".$job_id."\n";
                  $txt.= "Degron_ls:\t".implode(",", $hla_ls)."\n";
                  
					
					$fafile="jobs/".$job_id."/Sequence.fasta";
                  $input_fp = fopen($fafile, "w");
                  fwrite($input_fp, $TCR_input);
                  fclose($input_fp);
				//echo $hla_ls."<br/>";
				//echo $TCR_input."<br/>";
				$cmd="/tool/anaconda3/envs/tens37/bin/python ./Degron_prediction.py -inf '".$fafile."' -out jobs/".$job_id." -mof";
				foreach ($hla_ls as $value){
					$cmd=$cmd." '".$value."'";
				}
				# $cmd=$cmd." ; zip -r jobs/".$job_id.".zip jobs/".$job_id."/* >/dev/null &";
				$cmd=$cmd." >/dev/null &";
				echo($cmd);
				exec($cmd);
				header('Location: result.php?retrieve_id='.$job_id);
				$txt.= $cmd."\n";
				# exec("/tool/python3.7.10/bin/python3 -V",$out);
				# exec("/tool/anaconda3/envs/tens37/bin/python -V",$out);
				# $txt.= implode($out)."\n";
				# fwrite($jobinfo_fp, $txt);
				# fclose($jobinfo_fp);
            }else{
              $msg="Job ID (".$job_id.") exists. Please change to another one.";
            }
		

    }else{
         // $msg="Wrong access !!!";
         // header('Location: index.php');
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>MetaDegron: Submitting</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
    <meta name="description" content="DeepTRP">
    <meta name="Haodong Xu" content="DeepTRP">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables -->
    <!-- Bootstrap -->
	<script src="js/jquery.min.js"></script>
    <!--<link rel="stylesheet" href="css/metroStyle/metroStyle.css" type="text/css"> 
    <script type="text/javascript" src="js/jquery.ztree.core.js"></script>
    <script type="text/javascript" src="js/jquery.ztree.excheck.js"></script>
    <script type="text/javascript" src="js/jquery.ztree.exhide.js"></script>
    <script type="text/javascript" src="js/fuzzysearch.js"></script>-->
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
            padding-top: 48px;
            padding-bottom: 20px;
            margin-bottom: 30px;
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
         left: 45%;
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
                <li><a href="result.php">Result</a></li>
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
<?php if($msg == 'OK'){  ?>
    <div class="container">
        <input type="hidden" name="page_content" id="page_content" value="<?php echo $msg; ?>">
        <input type="hidden" name="job_id" id="job_id" value="<?php echo $job_id; ?>">
        <h3><span style="color: #7386D5;">Submitting a job...</span></h3>   
        <hr style="margin-top: 0; ">
        <div class="row" style="font-size:1.89rem;">
          <div class="col-md-12">
            <p>XXX.</p>
            <p><b>Important note:</b> Each job may take 1-10 minutes to finish. You may retrive the results anytime using the <b>Job Identifier (JID e.g., JOB1234_1234567890)</b>. Please do keep a record of the JID.</p>
          </div>

        </div>
        <!-- Central Modal Small -->
        <div class="modal fade" id="submitModalLg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
            aria-hidden="true" data-backdrop='static' data-keyboard="false">

            <!-- Change class .modal-sm to change the size of the modal -->
            <div class="modal-dialog modal-lg" role="document">

                <div class="modal-content">
                  <div class="modal-header"><h4 class="modal-title w-100" id="myModalLabel">Submitting job...</h4></div>
                  <div class="modal-body">
                    <center> 
                      <p>Your job <?php echo $job_id; ?> is submitting...<br> This page will be directed to the job status page in <span id="seconds"></span> seconds, if all checks are passed.</p>
                      <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                      </div>
                    </center>
                  </div>
              </div>
            </div>
            <!-- // Central Modal Small -->  
        </div>
        <hr>
        <br>
    </div> <!-- ^container  -->
 <?php } else { ?>
    <!-- visit page drectly -->
    <div class="container">
      <br>
      <h2><span style="color: #F00;">Error: </span></h2> 
      <hr>
      <div class="col-md-12">
        <h4><span style="color: #F00;"><?php echo $msg; ?></span></h4>
        <input type="hidden" name="page_content" id="page_content" value="<?php echo $msg; ?>">
      </div>
    </div>
<?php } ?>
</div> <!-- ^content  -->




<footer>
    <div class="container">
        <hr>
        <div class="row">
            <div class="col-md-4"><a href="https://www.uth.edu/" target="_blank">
                    <img src="images/SBMI.jpg" width=100% alt="@"></a>
            </div>
            <div class="col-md-8">
                <p>
                    <span style="font-size:1.20em;"><strong>Copyright &copy;</strong>
                    <span> 2009-Present - <a href="https://www.uth.edu" style="text-decoration: underline;">The University of Texas Health Science Center at Houston (UTHealth)</a><BR/></span>
                    <a href="https://www.uth.edu/index/policies.htm"><span style="font-family: Arial;">Site Policies</span></a>
                    <span style="font-family: Arial;"> | </span>
                    <a href="https://uthealthemergency.org/"><span style="font-family: Arial;">Emergency Information</span></a></span>
                </p>
            </div>
        </div>
    </div>
    <br><br>
</footer>

<!-- Font Awesome JS -->
<script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js"></script>
<script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js"></script>
<!-- Popper.JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
<!-- Bootstrap JS -->
<script src="js/bootstrap.min.js"></script>
<!-- jQuery Custom Scroller CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.concat.min.js"></script>

<script type="text/javascript">
var page_content = $('#page_content').val();

if(page_content=="OK"){
    $(function () {
        var job_id = $('#job_id').val();
        var seconds = 5;
        var done_flag = 0;

        //Redirct to job status page
         $('#submitModalLg').modal('show');

         $("#seconds").empty();
         $("#seconds").html("<strong>"+seconds+"</strong>");

         var check_timer = setInterval(function(){
           seconds = seconds-1;
           if (seconds == 0) {
             if(done_flag==1){
               clearInterval(check_timer);
               window.location.href = "result.php?retrieve_id="+job_id;
             }else{
               seconds += 5;
             }

           }
           $("#seconds").empty();
           $("#seconds").html("<strong>"+seconds+"</strong>");

         }, 1000);

        // check job
        
        $.post( "check.php", { type:"check",job_id: job_id}, 
          function(data) {
             // alert("aa"+data+"cc");
          })
          .done(function(data) {
                // alert("aa"+data+"bb");
                if(data=="CHECKED"){
                  done_flag=1; 
                  //$.post( "run_job.php",{ type:"run",job_id: job_id}); 
                }else{
                  // clearInterval(check_timer);
                    var url = "submit_job.php";
                    var form = $('<form action="' + url + '" method="post">' +
                       '<input type="hidden" name="msg" value="' + data + '" />' +
                       '</form>');
                    $('body').append(form);
                    form.submit();
                }
          })
          .fail(function(data) {
            // alert( "error"+data );
          })
          .always(function(data) {
            // alert( "finished" + data);
          });
    });
} else{

}

</script>




</body>
</html>
