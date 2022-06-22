<?php
ob_flush();

include_once 'init.php';


session_start();

if(!isset($_SESSION['ID'])){
    	header('location:login.php');
}


$ID = $_SESSION['ID'];


// get use type

 $type = getUserType($ID);
 $type = $type['type'];

 // create skilled or client object 
 if($type == 'skilled'){

 	  include 'classes/skilled.php';
 	  $user = new skilled();
 	  $user->setID($ID);

 }else{

 	  include 'classes/client.php';
 	  $user = new client();
 	  $user->setID($ID);
 }


if($user->getView()=='1'){
    include 'includes/templates/bar-dark.php';
	?>
	   <link rel="stylesheet" type="text/css" href="layout/css/chat-view-dark.css">
	<?php
}else{
    include 'includes/templates/bar.php';
	?>
	    <link rel="stylesheet" type="text/css" href="layout/css/chat-view.css">
	<?php

}




if(isset($_GET['id']) && is_numeric($_GET['id']) && isset($_GET['post']) && is_numeric($_GET['post'])){
	// GET variables
	$id = filter_var($_GET['id'],FILTER_SANITIZE_NUMBER_INT);
	$postID = filter_var($_GET['post'],FILTER_SANITIZE_NUMBER_INT);


	// get contact data
	$contact = getUserData($id , 'user');

    // get request ID
	$stmt = $con->prepare("SELECT `request`.`status` ,`request`.`ID` ,
            `request group`.`from`,`request group`.`to` 
	        FROM `request`,`request group`  WHERE `request group`.`status`='0' AND `request`.`type`='chatting' 
			AND `request group`.`post ID`='$postID'
			AND `request`.`GroupID`=`request group`.`ID` 
	        AND (`request group`.`from`='$id' OR  `request group`.`to`='$id') ");
    $stmt->execute();
	$count = $stmt->rowCount();
    $reg =$stmt->fetch();





     // which means chatting is allowed between both sides
	$allow = 0;
	$from = '';
	$to ='';
	 
	if($count == 1){

		$status = $reg['status'];
    	$regID = $reg['ID'];

		if($reg['from'] == $ID && $reg['to']==$id){
			$allow = 1;
			$from = $ID;
			$to =$id;

	
	 	}
	 	elseif( $reg['to'] == $ID && $reg['from']==$id){
			$allow = 1;
			$to = $ID;
			$from = $id;
		
	 	}
	 
	}



	// start display chat
	if($allow == 1){

		$stmt = $con->prepare("SELECT `title` FROM `post` WHERE `ID`='$postID'");
		$stmt->execute();
		$postTitle = $stmt->fetch();
		$postTitle = $postTitle['title'];




				// start display chat
				?>

<div class="main">



				<div class="chat-view">
		
		<div class="heading">
		
			<a href="chat.php" style="color:white; text-decoration: none;"><h4><i class="fas fa-arrow-left"></i> 			<?php  echo $postTitle;  ?></h4></a>
			<div class="row profile">
			<img class="rounded-circle" src="Admin/uploads/<?php echo $contact['type'];?>/<?php echo $contact['image'];?>" style="width:70px;">
				<h4 class="name"><?php echo $contact['first name'].' '.$contact['last name'];?></h4>
				
			</div>
		
		</div>
		
		<div class="contant">

	
		
			
		
		</div>
		
		
		<div class="input row">
			
			<form method="POST" action=''>
				<textarea name="message" id="message"></textarea>
				<button type="submit" name="send" id="send"><i class="fas fa-paper-plane" style="margin:5px 0px 15px 10px"></i></button>
			</form>
		
		</div>
		
		
		</div>
		
		
				<?php
				// end display chat

	}
	// end allow

	
	


		
	} 
   // end isset GET


// if chating is enabled the user will be able to send messages
   


// detect if messages are seen 
if(isset($_SESSION['ID'])){

	$stmt = $con->prepare("UPDATE `chat` SET `seen`='1' WHERE `post ID`='$postID' AND `request ID`='$regID' 
                          AND (`sender_ID`='$ID' OR `receiver_ID`='$ID')");
    $stmt->execute();

}

    


?>

</div>

<script type="text/javascript">


$('.main').css('margin-top',$('.nav').height()/1.9);




	$(".input form").submit(function(e){
          e.preventDefault();
     });

	   
	 $("#send").click(function(){
	

	
    	var to = '<?=$id?>';
    	var from = '<?=$user->getID()?>';
    	var message = $('#message').val();
		var post = '<?=$postID?>';
		var reg = '<?=$regID?>';
		var status ='<?=$status?>';

		if(status==1){
			$.ajax({
				method:'POST',
				url:'ajax/sendMessage.php',
				data:{
					to:to,
					from:from,
					message:message,
					post:post,
					reg:reg,
		}
		,
			success:function(data){
				$('#message').val('');
	
			}

		})

		}else{
			$('#message').val('Chat is disabled');

		}	


	
	
})


 // get requests and notifications
 function loadMessages(){
  
  setInterval(function(){
	  var post = '<?=$postID?>';
	  var id = '<?=$id?>';
	  var reg = '<?=$regID?>';

   var xhttp = new XMLHttpRequest();
   xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) { 
	     document.querySelector(".contant").innerHTML =  xhttp.responseText;
		 var target = document.querySelector(".contant");
         target.scrollTop = target.scrollHeight;
    }
   };
   xhttp.open("GET", 'ajax/getMessages.php?post='+post+'&id='+id+'&req='+reg, true);
   xhttp.send();

  },1000);


 }
loadMessages();




      






	

</script>


