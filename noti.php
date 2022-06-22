<?php
ob_start();

 

include 'init.php';
include 'classes/request.php';
session_start();



if(isset($_SESSION) && isset($_SESSION['ID']) && (getUserType($_SESSION['ID'])=='skilled'  || getUserType($_SESSION['ID']))=='client' ){
	
	$ID = $_SESSION['ID'];
	$type = getUserType($ID);
    $type = $type['type'];


 // create skilled or client object 
 if($type == 'skilled'){
 	  include 'classes/skilled.php';
 	  $user = new skilled();
 	  $user->setID($ID);

 }elseif($type == 'client'){

 	  include 'classes/client.php';
 	  $user = new client();
 	  $user->setID($ID);

 }else{
	//header('location:logout.php');
 }


if($user->getView()=='1'){
	include 'includes/templates/bar-dark.php';
	?>
	
	   <link rel="stylesheet" type="text/css" href="layout/css/noti-dark.css">
	<?php
}else{
	include 'includes/templates/bar.php';
	?>
	 <link rel="stylesheet" type="text/css" href="layout/css/noti.css">
	<?php

}

}else{
	header('location:login.php');
}


if(isset($_POST['clear'])){

	$stmt = $con->prepare("UPDATE `notification` SET `seen`='1' WHERE `to`='$ID'");
    $stmt->execute();
    header('location:noti.php');

}




		//  form proccessing (accept request)
		
		if(isset($_POST['accept'])){


			$reqID = $_POST['ID'];
			$from = $_POST['from'];

			$stmt = $con->prepare("SELECT `post`.`title`,`post`.`ID` as 'postID', 
									`request Group`.`from`, `request Group`.`to` , `request Group`.`ID` as 'gID',
									`request`.`type`  FROM `request`,`request Group`,`post` 
									WHERE 
									`request Group`.`status`='0' AND `request Group`.`post ID`= `post`.`ID` 
									AND `request Group`.`from`='$from'  AND `request`.`ID`='$reqID'");
            $stmt->execute();
			$row = $stmt->fetch();
			
			if($stmt->rowCount()==1 ){

				if($row['from']== $user->getID()){
					$to = $row['to'];
				}
				else{
					$to = $row['from'];

				}

				
				$title = $row['title'];	
				// accept the request
				$user->Request_accept($reqID);

				if($row['type'] == 'acceptance'){

				 	// create chatting request
					$request = new request();
					$request->consract('' ,'', 'chatting' , '0', '1' , $row['gID']);
					// send request
					$user->sendRequest($request);

					sendNote('Request accepted' ,'Your request has been accepted',$user->getID(),$to);
					header('location:noti.php');

				}
				elseif($row['type'] == 'chatting'){

					// send notification about opening the chatting 
					sendNote('Chatting is available' ,'Now you can use the chatting <br><a href="chat-view.php?id='.$user->getID().'&post='.$row['postID'].'"> Go the chat: </a>' ,$user->getID(),$to);
					sendNote('Chatting is available' ,'Now you can use the chatting <br><a href="chat-view.php?id='.$to.'&post='.$row['postID'].'"> Go the chat: </a>' ,'0',$user->getID());
					header('location:noti.php');
					
				}


			}


			
	  
	   }
	 

   
   // end form proccessing (accept request)




   // start proccessing accept delivering request

    if(isset($_POST['accept_deliver'])){

		$reqID =  $_POST['ID'];

		// check if a user is involved in the request so can accept it
		$stmt = $con->prepare("SELECT `request`.`ID`,`request`.`price`,`request group`.`from`,`request group`.`to`, 
		`request group`.`post ID`
		FROM `request`,`request group` WHERE `request`.`type`='delivering' 
		AND `request`.`ID`='$reqID' AND `request`.`GroupID`=`request group`.`ID` 
		AND (`request group`.`from`='$ID' OR `request group`.`to`='$ID')");
        $stmt->execute();
		$count =  $stmt->rowCount();
		$reqID2 = $stmt->fetch();



		if($count == 1 && ($reqID == $reqID2['ID']) ){

			$postID = $reqID2['post ID'];

			$stmt = $con->prepare("SELECT `title` FROM `post` WHERE `ID`='$postID'");
			$stmt->execute();
			$post = $stmt->fetch();

			
			$user->Request_accept($reqID);

			if($reqID2['from']==$ID){
				sendNote('Request accepted' ,'Post name : '.$post['title'].'| Agree on this price: '.$reqID2['price'] ,$reqID2['from'],$reqID2['to']);

			}else{
				sendNote('Request accepted' ,'Post name : '.$post['title'].'| Agree on this price: '.$reqID2['price'],$reqID2['to'],$reqID2['from']);
			}

			header('location:noti.php');

		}
		
		
	

		

	}




   // end proccessing accept delivering request




   
   if(isset($_POST['reject_deliver'])){

	$reqID =  filter_var($_POST['ID']  , FILTER_SANITIZE_NUMBER_INT);

	// check if a user is involved in the request so can accept it
	$stmt = $con->prepare("SELECT `request`.`ID`,`request`.`price`,`request group`.`from`,`request group`.`to`, 
	`request group`.`post ID`
	FROM `request`,`request group` WHERE `request`.`type`='delivering' 
	AND `request`.`ID`='$reqID' AND `request`.`GroupID`=`request group`.`ID` 
	AND (`request group`.`from`='$ID' OR `request group`.`to`='$ID')");
	$stmt->execute();
	$count =  $stmt->rowCount();
	$reqID2 = $stmt->fetch();





	if($count == 1 && ($reqID == $reqID2['ID']) ){

		$postID = $reqID2['post ID'];

		$stmt = $con->prepare("SELECT `title` FROM `post` WHERE `ID`='$postID'");
		$stmt->execute();
		$post = $stmt->fetch();

		
		$user->Request_Reject($reqID);

		if($reqID2['from']==$ID){
			sendNote('Request rejected' ,'Post name : '.$post['title'].'| Disagree on this price: '.$reqID2['price'] ,$reqID2['from'],$reqID2['to']);

		}else{
			sendNote('Request rejected' ,'Post name : '.$post['title'].'| Disagree on this price: '.$reqID2['price'],$reqID2['to'],$reqID2['from']);
		}

		header('location:noti.php');

	}
	
	
	

}




// end proccessing accept delivering request


// reject acceptance request form proccessing

if(isset($_POST['delete_acceptance_request'])){
	

	$reqID = filter_var($_POST['RejectAcceptReqID'],FILTER_SANITIZE_NUMBER_INT);
	$stmt = $con->prepare("SELECT `request group`.`ID` , `request group`.`from`  FROM `request group`,`request` WHERE `request`.`ID`='$reqID' AND `request group`.`to`='$ID' AND `request`.`GroupID`=`request group`.`ID` ");
    $stmt->execute();
    $count = $stmt->rowCount();
    $GroupID = $stmt->fetch();

    if($count == 1){

		$from =  $GroupID['from'];
		$GroupID = $GroupID['ID'];
       
    	$stmt = $con->prepare("UPDATE `request group` SET`status`='1' WHERE `ID`='$GroupID'");
    	$stmt->execute();
		sendNote('Request has been canceled','The user has rejected the request that you sent',$ID,$from);
    	header('location:noti.php');
    }


}



// end rejecr acceptance from proceessing

// reject if chatting 


if(isset($_POST['reject_request_end_submit'])){
	

	$reqID = filter_var($_POST['reject_request_end'],FILTER_SANITIZE_NUMBER_INT);
	$stmt = $con->prepare("SELECT `request group`.`ID` , `request group`.`from`  FROM `request group` WHERE `request group`.`ID`='$reqID' AND `request group`.`to`='$ID'");
    $stmt->execute();
    $count = $stmt->rowCount();
    $GroupID = $stmt->fetch();

    if($count == 1){

		$from =  $GroupID['from'];
	
    	$stmt = $con->prepare("UPDATE `request group` SET`status`='1' WHERE `ID`='$reqID'");
    	$stmt->execute();
		sendNote('Request has been canceled','The user has rejected the request that you sent',$ID,$from);
    	header('location:noti.php');
    }


}





// ---------------------------------











?>


<div class="main">

<div class="container-fluid note">

	<div class="row">

		<div class="col-md-6">

			<!---- start note ----->

			<div class="box">

	<div class="heading">

	 <i class="fas fa-bell"></i>
	   Notification
	<form method="POST" action="" >
		 <button type="submit" name="clear"><span style="font-size:16px;">Clear <i class="far fa-trash-alt"></i></span></button>
	 </form>
    
	</div>

	<?php
	// fetch notficiations
	$stmt = $con->prepare("SELECT * FROM `notification` WHERE `seen`='0' AND `to`='$ID' ORDER BY `ID` DESC");
    $stmt->execute();
	$notes = $stmt->fetchAll();



	

	foreach($notes as $note){
		if($note['from']!=0){
			$from = getUserData($note['from'],'user');
		}
		if($note['from']==0){
			$from  = 'system';
		}

		if($note['from']==1){
			$from  = 'admin';
		}

	
		// start note
		?>

<div class="body">


<div class="note">

	<div class="title">

		<div class="control">
			
			<i class="fas fa-caret-down" id="down" style="margin-right:15px; font-size: 20px;"></i>
			
		</div>

		<h5><?php echo $note['subject'];?></h5>

	</div>


<table>
	<tr>
		<th class="profile"><?php
		
		        if($from =='system' || $from =='admin'){
					?>
					<img class="rounded-circle" src="Admin/uploads/users/user.png" style="width:55px;">
					<?php
				}else{
					?>
					<img class="rounded-circle" src="Admin/uploads/<?php echo $from['type'];?>/<?php echo $from['image'];?>" style="width:55px;">
					<?php
				}
		
		?>
		</th>
		<th class="text"><?php echo $note['text'];?></th>
		<th class="control">
		</th>
	</tr>

	<tr>
		<th class="profile">
			<?php 
			if($from !='system' && $from !='admin'){
			     echo $from['first name'].' '.$from['last name'];
		     }else{
				 echo $from;
			}
			
		?>
		</th>
		<th></th>
		<th class="control" >
			
		</th>
	</tr>
</table>

</div>




</div><!---- end note ----->



		<?php
		// end note


	}

						
	?>

</div>
<!---- end box ----->


			 
		</div>
	
	 <!---- end col  ----->





		<div class="col-md-6"> <!---- start col ----->

						<div class="box">

						<div class="heading">
	                           <i class="fas fa-clipboard-list"></i>
							   Requests
						</div>
	     <?php


$stmt = $con->prepare("SELECT `request`.*,`request Group`.`post ID`,`request Group`.`from` FROM `request`,`request Group` 
WHERE `request Group`.`status`='0' AND `request Group`.`to`='$ID'  AND `request`.`accept`='0' AND `request`.`GroupID` = `request Group`.`ID`");
$stmt->execute();
$count = $stmt->rowCount();

$request = $stmt->fetchAll();


//#case_1 fetch request came from requesters
         if($count>0){
			 foreach($request as $req){

				$postID = $req['post ID'];

				$stmt2 = $con->prepare("SELECT * FROM `post` WHERE `ID`='$postID'");
                $stmt2->execute();
				$post = $stmt2->fetch();

				if($req['accept'] == 0 && $req['type']!="delivering"){
					// start box 
					?>

<div class="body">


<div class="note">

	<div class="title">

		<div class="control">
			<i class="fas fa-caret-down" id="down" style="margin-right:15px; font-size: 20px;"></i>
			<form method="POST" action="" style="float:right;">
				<input type="text" name="RejectAcceptReqID" value="<?php echo $req['ID']; ?>" hidden>
			    <button type="submit" name='delete_acceptance_request' id='trash_accept'>
			            <i class="fas fa-trash"></i>
		        </button>
		    </form>

		</div>

		<h5><?php 
		    if($req['type']=='acceptance' && $user->getType()=='client'){
				echo 'Hiring request';
			}
			elseif($req['type']=='acceptance' && $user->getType()=='skilled'){
				echo 'service request';
			}
			elseif($req['type']=='chatting'){
				echo 'Chating request';
			}
				
		
		?></h5>

	</div>



<table>
	<tr>
		<th class="profile"><img class="rounded-circle" src="Admin/uploads/users/user.png" style="width:55px;"></th>
		<th class="text">Post name :<?php echo $post['title'];?></th>
		<th class="control">

        <form method="POST" action="">
			<input type='text' name='from' value='<?php echo $req['from'];  ?>' hidden>
			<input type='text' name='ID' value='<?php echo $req['ID'];  ?>' hidden>
			<button type="submit" class="btn btn-success" name="accept">Accept</button>
		</form>


		</th>
	</tr>

	<tr>
		<th class="profile"><?php 
		         $from = $req['from'];
		         $stmt = $con->prepare("SELECT `first name`,`last name` FROM `user` WHERE  `ID`='$from' ");
				 $stmt->execute();
				 $name =  $stmt->fetch();

				 echo $name['first name'].' '.$name['last name'];
			
	
	    ?></th>
		<th></th>
		<th class="control" ></th>
	</tr>
</table>

</div><!---- end note ----->

<?php

		}

			 }


			}
	

?>


<?php


$ID = $user->getID();

if($type == 'skilled'){

$stmt = $con->prepare("SELECT `request`.`ID` as 'reqID' ,`request`.`price` ,`request group`.* FROM `request` ,`request group` 
WHERE  `request`.`accept`='0' AND `request`.`type`='delivering' AND `request group`.`ID`= `request`.`GroupID` 
AND `request group`.`status`='0' AND (`request group`.`from`='$ID' OR `request group`.`to`='$ID')");
$stmt->execute();
$request =  $stmt->fetchAll();


// start foreach
foreach($request as $req){

	$postID = $req['post ID'];

	

	$stmt2 = $con->prepare("SELECT * FROM `post` WHERE `ID`='$postID'");
	$stmt2->execute();
	$post = $stmt2->fetch();


	if($req['from']==$ID){
		$requester = $req['to'];
	}else{
		$requester = $req['from'];

	}


	$stmt = $con->prepare("SELECT `first name`,`last name`,`image` FROM `user` WHERE  `ID`='$requester' ");
	$stmt->execute();
	$name =  $stmt->fetch();

		// start box 
		?>

<div class="body">


<div class="note">

<div class="title">

<div class="control">
<i class="fas fa-caret-down" id="down" style="margin-right:15px; font-size: 20px;"></i>
	<form method="POST" action="" style="float:right;">
		<input type="text" name="reject_request_end" value="<?php echo $req['ID']; ?>" hidden>
		<button type="submit" name='reject_request_end_submit' id='trash_accept'>
			<i class="fas fa-trash"></i>
		</button>
	</form>

</div>

<h5>Hiring agreement</h5>

</div>



<table>
<tr>
<th class="profile"><img class="rounded-circle" src="Admin/uploads/users/user.png" style="width:55px;"></th>
<th class="text">Post name :<?php echo $post['title'];?></th>
<th class="control">

<form method="POST" action="">

<input type="text" name="ID" value="<?php echo $req['reqID']; ?>" hidden>
<button type="submit" class="btn btn-success" name="accept_deliver">Accept</button>
</form>






</th>
</tr>

<tr>
<th class="profile"><?php 
	

	 echo $name['first name'].' '.$name['last name'];


?></th>
<th>Price : <?php echo $req['price'];  ?>$</th>
<th class="control" >

<form method="POST" action="">
<input type="text" name="ID" value="<?php echo $req['reqID']; ?>" hidden>
<button type="submit" class="btn btn-danger" name="reject_deliver">Reject</button>
</form>

</th>
</tr>
</table>

</div><!---- end note ----->


	
<?php


}
// end foreach

}

////#case_2 fetch request came from provider as delivering Hiring or serives








?>




</div><!---- end body ----->





					<?php
					// end box





		 
		 
		  
		 
		 ?>



	


     </div>
    <!---- end box ----->


			


		</div> <!---- end col ----->


		
	</div> <!---- end row ----->

</div><!---- end container ----->



</div><!---- end main ----->



<script type="text/javascript">

$('.main').css('margin-top',$('.nav').height()/1.8);

	$(document).ready(function(){
		$('.note table').css('display','none');

		$('.note .title').click(function(){


			
			 if($(this).parent().children('table').is(':visible') !=true){
         
                 $(this).parent().children('table').show();

              

			 }else{

			 	  	  $(this).parent().children('table').hide();

			 	  
			 }

		
		
			
		})



	});






</script>
