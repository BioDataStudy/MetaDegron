<?php
require 'global.func.php';

if(isset($_POST["type"]) and $_POST["type"]=="check"){
	$job_id = $_POST["job_id"];
	echo $job_id."<br/>";
	$target_dir = "jobs/".$job_id;
	if(file_exists($target_dir."/prediction.txt")){
		$msg_tmp = "<br>Done <a href='result.php?retrieve_id=".$job_id."'>Job infomtion</a>";
	}else if(file_exists($target_dir."/Sequence.fasta")){
		$msg = "<br>Running: <a href='result.php?retrieve_id=".$job_id."'>Job infomtion</a>";
	}else{
		$msg="Failed in submiting the job.";
	}
	echo $msg;
	
	
	
  // $cmd_str = $python." model/check_predict_job.py  ".$job_id;
  //exec(escapeshellcmd($cmd_str), $results1, $run_status1);
	
//   if(count($results1)==0 || $run_status1!=0){
//     $msg="Failed in submiting the job. ";
//     $msg_tmp = "<br>Check <a href='result.php?retrieve_id=".$job_id."'>Job infomtion</a>";
//     // array_map('unlink', glob($target_dir."/*.*"));
//     // rmdir($target_dir);
//   }else{
//     if (count($results1)==1 and $results1[0]=='Done') {
//            $msg="CHECKED";
// 
//         }else{
//           $msg = $results1[0];
//           $msg_tmp = "<br>Check <a href='result.php?retrieve_id=".$job_id."'>Job infomtion</a>";
//         }
//     }
//   echo $msg;
// 
// }else{
//   echo "Wrong access";
//   header('Location: index.php');
// }
}
?>