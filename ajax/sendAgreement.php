<?php

session_start();
include '../connect.php';
include '../classes/client.php';
include '../classes/request.php';



$requester = $_POST['requester'];
$requested = $_SESSION['ID'];
$postID = $_POST['postID'];
$price = $_POST['price'];
$gID =  $_POST['gID'];


$stmt = $con->prepare("SELECT * FROM `request group` WHERE `ID`='$gID' AND `status`='0' 
                        AND ( (`from`='$requester' OR `to`='$requester') OR (`from`='$requested' OR `to`='$requested') ) ");
$stmt->execute();
$count = $stmt->rowCount();
$row = $stmt->fetch();

if($count == 1){

    if(abs($price) == $price){
        $stmt = $con->prepare("INSERT INTO `request`( `price` , `type` , `accept` , `status` , `GroupID` ) 
                                VALUES ( '$price' , 'delivering' , '0' , '1' , '$gID' )");
        $stmt->execute();


    }
    

}



?>