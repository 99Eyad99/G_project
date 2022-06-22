<?php

include '../connect.php';
include '../includes/functions/fun.php';
include '../classes/request.php';

session_start();

$ID = $_SESSION['ID'];

$requester = $_POST['requester'];
$requested = $_POST['requested'];
$postID = $_POST['postID'];
$price = $_POST['price'];


$type =  getUserType($ID);

if($type['type'] == 'client'){
    include '../classes/client.php';
    $user = new client();
    $user->setID($ID);


}
elseif($type['type'] == 'skilled'){

    include '../classes/skilled.php';
    $user = new skilled();
    $user->setID($ID);

}



$stmt = $con->prepare("SELECT `ID` FROM `request group` WHERE 
                      `from`='$requester' AND `to`='$requested' AND `post ID`='$postID' AND `status`='0'");
$stmt->execute();
$count = $stmt->rowCount();
$row =  $stmt->fetch();



    // create request 

if($count == 1){

    $request = new request();
    // create acceptance request
    $request->consract('' , $price , 'acceptance' , '0', '1' , $row['ID']);
    // send request
    $user->sendRequest($request);

}

    







?>

