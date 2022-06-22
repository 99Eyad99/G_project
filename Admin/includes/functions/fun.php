<?php

function redirect($msg , $s=2 , $where){

    echo '<div style="width:80%; margin: 20px 0px 0px 10%;" 
    class="alert alert-info text-center"> <strong>'.$msg.'</strong></div>';
    header("refresh:$s;url=$where");

}

function getUserData($id , $table){
    global $con;
    $stmt = $con->prepare("SELECT * FROM $table WHERE `ID`=$id");
    $stmt->execute();

    return $stmt->fetch();

}

function getUserType($id){
    global $con;
    $stmt = $con->prepare("SELECT `type` FROM `user` WHERE `ID`='$id'");
    $stmt->execute();

    return $stmt->fetch();

}


function checkItem($item , $table , $val){

    global $con;
    $stmt = $con->prepare("SELECT 
    $item 
FROM $table
WHERE 
 $item = '$val' ");

$stmt->execute();
$count = $stmt->rowCount();

return $count;


}

function clacItems($item , $table){

    global $con;
    $stmt = $con->prepare(" SELECT $item FROM $table ");

$stmt->execute();
$count = $stmt->rowCount();

return $count;

}



function getLastest($select , $table , $order  , $limit){

    global $con;
    $stmt = $con->prepare("SELECT $select FROM $table ORDER BY $order LIMIT $limit ");
    $stmt->execute();
    $rows = $stmt->fetchAll();

    return $rows;

}


// alert danager 

function alert($type,$txt , $style){

    echo '<div class="'.$type.'" style="'.$style.'"> <strong>'.$txt.'</strong> </div>';

}



// send notification 
function sendNote($subject ,$text,$form,$to){

    global $con;
    $stmt = $con->prepare("INSERT INTO `notification`(`subject`, `text`, `from`, `to`, `seen`) 
    VALUES ('$subject','$text','$form','$to','0')");
    $stmt->execute();
    return $stmt;

}

















?>