<?php
include '../connect.php';
session_start();

if(isset($_SESSION)){
   $ID = $_SESSION['ID'];
}



$stmt = $con->prepare("SELECT `ID` FROM `notification` WHERE `to`='$ID' 
                       AND `subject` !='Request acceptance' AND  `seen`='0' ");
$stmt->execute();
$notification_num =  $stmt->rowCount();

$stmt2 = $con->prepare("SELECT `request`.`ID` FROM `request`,`request group`
                        WHERE `request group`.`to`='$ID' 
                        AND `request`.`accept`='0' 
                        AND `request group`.`status` = '0'
                        AND `request group`.`ID`=`request`.`GroupID`");
$stmt2->execute();
$requests_num =  $stmt2->rowCount();



if($notification_num + $requests_num  > 0){
    echo $notification_num +$requests_num ;
}







?>
