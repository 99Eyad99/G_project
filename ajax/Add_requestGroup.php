<?php


include '../connect.php';
include '../classes/request_group.php';


$from = $_POST['requester'];
$to = $_POST['requested'];
$postID = $_POST['postID'];



$reqGroup = new request_group();
$reqGroup->consract('' , $from ,  $to , $postID , '0');
$reqGroup->create();

echo 1;


?>