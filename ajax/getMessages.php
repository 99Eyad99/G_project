<?php

include '../connect.php';
include '../includes/functions/fun.php';


session_start();

$ID = $_SESSION['ID'];

$post= $_GET['post'];
$id = $_GET['id'];
$req = $_GET['req'];


 $stmt = $con->prepare("SELECT * FROM `chat` WHERE `post ID`='$post' AND `request ID` = '$req'
                        AND (`sender_ID`='$ID' OR `receiver_ID`='$ID') AND (`sender_ID`='$id' OR `receiver_ID`='$id') ");
 $stmt->execute();
 $message =  $stmt->fetchAll();



$user = $contact = getUserData($ID , 'user');


foreach($message as $m){
	if($m['receiver_ID']==$ID){
		$contact = getUserData($m['sender_ID'] , 'user');
		?>

			<div class="received">
			<img class="rounded-circle" src="Admin/uploads/<?php echo $contact['type'];?>/<?php echo $contact['image'];?>" style="width:45px;">
		   <label><?php echo $m['message'];?> </label>

		</div>
	   <?php
	}
	elseif($m['sender_ID']==$ID){
		  

		?>
		 <div class="send">
		     <img class="rounded-circle" src="Admin/uploads/<?php echo $user['type'];?>/<?php echo $user['image'];?>" style="width:45px;">
		     <label><?php echo $m['message'];?></label>
	     </div>
	
	
	   <?php



	}
	


 }



?>
