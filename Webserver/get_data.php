<?php
require 'global.func.php';

if(isset($_POST["type"])){

  if($_POST["type"]=="TCR"){
    $msg = file_get_contents('model/test_TCR.txt');
    echo $msg;
  } else if($_POST["type"]=="antigen"){
    $msg = file_get_contents('model/test_antigen.txt');
    echo $msg;
  }else {
    
  }


}else{
  echo "Wrong access";
  header('Location: index.php');
}

?>