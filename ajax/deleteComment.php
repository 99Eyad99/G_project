<?php

include '../connect.php';

$cID = filter_var($_POST['cID'] , FILTER_SANITIZE_NUMBER_INT);

$stmt = $con->prepare("UPDATE `comment` SET `deleted_at`=now() WHERE `ID`='$cID '");
$stmt->execute();

if($stmt){
    echo 1;
}








?>