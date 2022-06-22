<?php

include '../init.php';


// sanitizing the input
$to = filter_var($_POST['to'],FILTER_SANITIZE_NUMBER_INT);
$from = filter_var($_POST['from'],FILTER_SANITIZE_NUMBER_INT);
$reg= filter_var($_POST['reg'],FILTER_SANITIZE_NUMBER_INT);
$post = filter_var($_POST['post'],FILTER_SANITIZE_NUMBER_INT);
$message = filter_var($_POST['message'],FILTER_SANITIZE_STRING);



// collect errors here
$errors = array();

if(empty($to) || !is_numeric($to)){
   $errors[] = 1;
}

if(empty($from) || !is_numeric($from)){
   $errors[] = 1;
}

if(empty($message)){
   $errors[] = 1;
}

if(count($errors)==0){
   sendMessage($to , $from , $message , $post , $reg);
}











?>