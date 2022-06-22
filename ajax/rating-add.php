<?php

session_start();
$ID = $_SESSION['ID'];

include '../connect.php';
include '../includes/functions/fun.php';


$rate =  filter_var($_POST['rate']  , FILTER_SANITIZE_NUMBER_INT);
$postID = filter_var($_POST['postID']  , FILTER_SANITIZE_NUMBER_INT );
$creatorID = $_POST['creatorID'];


$stmt = $con->prepare("SELECT `title` FROM `post` WHERE `ID`='$postID'");
$stmt->execute();
$title = $stmt->fetch();
$title  = $title['title'];


if(ratingStatus($ID , $postID ) == 0){

    $stmt = $con->prepare("INSERT INTO 
    `rating`(`star`, `creator_ID`, `post ID`) 
    VALUES ('$rate','$ID',' $postID')");
    $stmt->execute();

   sendNote("Rating alert" ,"Your post got rating ".$rate." starts : <br> <a href=post-view.php?id=".$postID.">".$title."</a>",'0',$creatorID);

}
else{

    $stmt = $con->prepare("UPDATE `rating` SET `star`='$rate',`creator_ID`='$ID',`post ID`='$postID' 
                           WHERE `creator_ID`='$ID' AND `post ID`='$postID' ");
    $stmt->execute();

    sendNote("Rating alert" ,"Your post got rating ".$rate." starts : <br> <a href=post-view.php?id=".$postID.">".$title."</a>",'0',$creatorID);


}





?>