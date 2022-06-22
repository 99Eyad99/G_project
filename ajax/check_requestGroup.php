<?php

include '../connect.php';
include '../classes/request_group.php';


$from = $_POST['requester'];
$to = $_POST['requested'];
$postID = $_POST['postID'];


$stmt = $con->prepare("SELECT `ID` FROM `request group` WHERE 
                      `from`='$from' AND `to`='$to' AND `post ID`='$postID' AND `status`='0'");
$stmt->execute();
$count= $stmt->rowCount();
$row =  $stmt->fetch();

// create new request group if no request group is exist
if($count == 0){

    $reqGroup = new request_group();
    $reqGroup->consract('' , $from ,  $to , $postID , '0');
    $reqGroup->create();

    echo 1;
  
}






?>