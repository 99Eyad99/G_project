<?php

include '../connect.php';

$ID = $_POST['postID'];

echo $ID;

$stmt = $con->prepare("UPDATE `post` SET `approved`='1' WHERE `ID`='$ID'");
$stmt->execute();









?>