<?php



$dsn='mysql:host=localhost;dbname=g_project';
$user='root';
$pass='';
$option = array(
    PDO::MYSQL_ATTR_INIT_COMMAND =>'SET NAMES utf8'
);

try{
    $con = new PDO($dsn,$user,$pass,$option);
    $con->setAttribute(PDO::ATTR_ERRMODE , PDO::ERRMODE_EXCEPTION);
    //echo 'you are connected';
}
catch(PDOException $e){
   // echo 'Falied to connect'.' ',$e->getMessage();
}

    $ID =  $_GET['ID'];
   

$stmt = $con->prepare("SELECT `ID` FROM `fav list` WHERE `user ID`=$ID");
$stmt->execute();
$data= $stmt->fetch();
return json_encode($data);



?>