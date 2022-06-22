<?php

include '../connect.php';


$stmt = $con->prepare("SELECT * FROM `request` WHERE `status`='1'");
$stmt->execute();
$reqCount = $stmt->rowCount();

$stmt = $con->prepare("SELECT * FROM `post` WHERE `approved`='0'");
$stmt->execute();
$postCount = $stmt->rowCount();

echo $reqCount+$postCount;







?>

<style>

.notes{
    font-size: 15px;
    padding: 5px;
    border-radius: 50%;
    background-color: #c0392b;
    margin-left: -10px;
    position: absolute;
    color: white;
}



</style>

