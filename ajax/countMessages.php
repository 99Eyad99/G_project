<?php


include '../connect.php';
session_start();

if(isset($_SESSION)){
   $ID = $_SESSION['ID'];

$stmt = $con->prepare("SELECT `chat`.`ID` FROM `chat`,`request group`,`request` 
WHERE `seen`='0' AND `request group`.`status`='0' AND `chat`.`request ID`=`request`.`ID` 
AND `request`.`GroupID`=`request group`.`ID` AND (`sender_ID`='$ID' OR `receiver_ID`='$ID')");
$stmt->execute();
$count =  $stmt->rowCount();



if($count>0){
    echo $count;
 }



}















?>


