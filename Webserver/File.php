<?php
	$file=$_GET["file"];
	$id=$_GET["id"];
	$row=$id-1;
	//$file=str_replace("&amp;amp; ","& ",$file);
	$fp = new SplFileObject($file, 'rb');
	$fp->seek($row);
	$data=$fp->current();
	$file =null;
	echo "$data";
?>



