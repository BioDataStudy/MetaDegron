<?php
	// Get real visitor IP behind CloudFlare network
	if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
			  $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
			  $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
	}
	$client  = @$_SERVER['HTTP_CLIENT_IP'];
	$forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
	$remote  = $_SERVER['REMOTE_ADDR'];

	if(filter_var($client, FILTER_VALIDATE_IP)){
		$ip = $client;
	}
	elseif(filter_var($forward, FILTER_VALIDATE_IP)){
		$ip = $forward;
	}
	else{
		$ip = $remote;
	}


	// set default timezone
	date_default_timezone_set('America/Chicago'); // CST

	$info = getdate();
	$date = $info['mday'];
	$month = $info['mon'];
	$year = $info['year'];
	$hour = $info['hours'];
	$min = $info['minutes'];
	$sec = $info['seconds'];

	$current_date = "$month/$date/$year $hour:$min:$sec CST";

	$user_ip = $ip;

	$ip_record = $current_date." --> ".$user_ip."\n"; // Output IP address [Ex: 177.87.193.134]
	//echo $ip_record;
	$file = 'ip_record.txt';
	// The new person to add to the file
	// Write the contents to the file, 
	// using the FILE_APPEND flag to append the content to the end of the file
	// and the LOCK_EX flag to prevent anyone else writing to the file at the same time
	$myfile = fopen($file, "a") or die("Unable to open file!");
	fwrite($myfile, $ip_record);
	fclose($myfile);

?>

