<?php

function redirect($msg , $s=2 , $where){

    echo '<div style="width:80%; margin: 20px 0px 0px 10%;" 
    class="alert alert-info text-center"> <strong>'.$msg.'</strong></div>';
    
   header("refresh:$s;url=$where");
   

}

function user_data($colum , $table , $val){
    global $con;
    $stmt = $con->prepare("SELECT * FROM $table WHERE `$colum`='$val'");
    $stmt->execute();

    return $stmt->fetch();

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
 $item=?");

$stmt->execute(array($val));
$count = $stmt->rowCount();

return $count;


}

function clacItems($item , $table){

    global $con;
    $stmt = $con->prepare("SELECT $item FROM $table ");

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

// confirm 


function confirm($txt){

   $confirm = '<div></div>';

}

// used to check if the post is exist and approved
function checkPost($ID){

    global $con;
   $stmt = $con->prepare("SELECT `ID` FROM `post` WHERE `ID`=$ID AND `approved`='1' AND `deleted_at` IS NULL");
   $stmt->execute();
   $count = $stmt->rowCount();
   return $count;

}





/*

// send a request to open chat
function sendRequestChat($requester , $requested , $postID , $type){

    global $con;
    $stmt = $con->prepare("INSERT INTO `request`(`requester`, `provider`, `post ID`,`type`) 
    VALUES ('$requester','$requested','$postID' , '$type')");
    $stmt->execute();
    return $stmt;

}

*/


// send notification 
function sendNote($subject ,$text,$form,$to){

    global $con;
    $stmt = $con->prepare("INSERT INTO `notification`(`subject`, `text`, `from`, `to`, `seen`) 
    VALUES ('$subject','$text','$form','$to','0')");
    $stmt->execute();
    return $stmt;

}

// get portiflo posts

function Get_Port_Posts($ID){

    global $con;
    $stmt = $con->prepare("SELECT * FROM `post` WHERE 
    `section ID`='4000010' AND `approved`='1' AND `creator ID`='$ID' AND `deleted_at` IS NULL");
    $stmt->execute();
    return $stmt->fetchAll();

}


function sendMessage($to , $from , $message , $post , $req){

    global $con;
    $stmt = $con->prepare("INSERT INTO `chat`(`sender_ID`, `receiver_ID`, `post ID`, `request ID`, `message`, `Time`) VALUES ('$from','$to','$post','$req','$message',now())");
    $stmt->execute();
    return $stmt;
}

// chatting allowance check
/*
function check_chat($userID , $contactID , $postID){
    global $con;
    $stmt = $con->prepare("SELECT * FROM `request` WHERE `requester`='$ID' 
    AND `provider`='$id' AND `post ID`='$postID' AND `type`='chating'");
    $stmt->execute();


}

*/

function clacRating($ID){

    global $con;
    $stmt = $con->prepare("SELECT `star` FROM `rating` WHERE 
                           `post ID`='$ID' ");
    $stmt->execute();
    $rating = $stmt->fetchAll();
    $rows = $stmt->rowCount();
    $rate = 0;

    foreach($rating as $r){
        $rate += $r['star'];
    }

    if($rows>0){
        $count = $rate / $rows;
        return $count;
    }

  

}

function ratingStatus($ID , $postID){
    
    global $con;
    $stmt = $con->prepare("SELECT `ID` FROM `rating` WHERE `creator_ID`='$ID' AND `post ID`='$postID'");
    $stmt->execute();
    $count_RC_time = $stmt->rowCount();
    $count  = $stmt->fetch();

    return $count;

}



function commentStatus($ID , $postID){
    
    global $con;
    $stmt = $con->prepare("SELECT `ID` FROM `comment` WHERE `post ID`='$postID' AND `creator ID`='$ID' AND `deleted_at` IS NULL ");
    $stmt->execute();
    $count_RC_time = $stmt->rowCount();
    $count = $stmt->fetch();
    return  $count;
     
}


?>