<?php
include '../connect.php';
session_start();

if(isset($_SESSION)){
   $ID = $_SESSION['ID'];

   $stmt = $con->prepare("SELECT * FROM `fav list` WHERE `user ID`='$ID'");
$stmt->execute();

$count =  $stmt->rowCount();

if($count>0){
   echo $count;
}

 



}







?>
