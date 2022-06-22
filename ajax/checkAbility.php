<?php

include '../connect.php';

$cID = filter_var($_POST['cID'] , FILTER_SANITIZE_NUMBER_INT);
$uID =  filter_var($_POST['uID'] , FILTER_SANITIZE_NUMBER_INT);

$stmt = $con->prepare("SELECT `ID` FROM `comment` WHERE `ID`='$cID' AND `creator ID`='$uID'");
$stmt->execute();
$count = $stmt->rowCount();

echo $count;






?>