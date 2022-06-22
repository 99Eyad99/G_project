<?php
ob_start();


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
	   <link rel="stylesheet" type="text/css" href="layout/css/chat-dark.css">
	<?php
}else{
    include 'includes/templates/bar.php';
	?>
	    <link rel="stylesheet" type="text/css" href="layout/css/chat.css">
	<?php

}


 // disable -----------------------------------------------------------------------;

if(isset($_POST['disable'])){


	$regID = filter_var($_POST['regID'],FILTER_SANITIZE_NUMBER_INT);


	$stmt = $con->prepare("SELECT `request`.`ID`, `request group`.`from` , `request group`.`to`,
	 `post`.`title`  FROM `request`,`request group`,`post`  
	WHERE `request`.`ID`='$regID' AND `request group`.`status`='0' 
	AND  `request`. `GroupID`=`request group`.`ID`
	AND  `request group`.`post ID` = `post`.`ID` AND
	(`request group`.`from`='$ID' OR `request group`.`to`='$ID')  ");
	$stmt->execute();
    $data = $stmt->fetch();

	

	

	if($data['ID'] == $regID){
		$regID=$data['ID'];


		$stmt = $con->prepare("UPDATE `request` SET `status`='0' WHERE `ID`='$regID'");
		$stmt->execute();

		header('location:chat.php');

		

	}

	

	


}

// -------------------------------------------------------------------------------------


//  ---------------------------   enable   --------------------------------

if(isset($_POST['enable'])){

	$regID = filter_var($_POST['regID'],FILTER_SANITIZE_NUMBER_INT);


	$stmt = $con->prepare("SELECT `request`.`ID`, `request group`.`from` , `request group`.`to`,
	 `post`.`title`  FROM `request`,`request group`,`post`  
	WHERE `request`.`ID`='$regID' AND `request group`.`status`='0' 
	AND  `request`. `GroupID`=`request group`.`ID`
	AND  `request group`.`post ID` = `post`.`ID` AND
	(`request group`.`from`='$ID' OR `request group`.`to`='$ID')  ");
	$stmt->execute();
    $data = $stmt->fetch();

	

	

	if($data['ID'] == $regID){
		$regID=$data['ID'];


		$stmt = $con->prepare("UPDATE `request` SET `status`='1' WHERE `ID`='$regID'");
		$stmt->execute();

		header('location:chat.php');

		

	}





	
}

//----------------------------- end ---------------------------






  $stmt = $con->prepare("SELECT `request group`.`from`,`request group`.`to` ,`request group`.`post ID` ,
  `request`.`ID` as 'regID',`request`.`status` as 'reqStatus'
   FROM `request`,`request group` 
   WHERE `request group`.`status`!='1' AND `request`.`type`='chatting' AND `request`.`accept`='1' 
  AND `request group`.`ID`=`request`.`GroupID` AND (`request group`.`from`='$ID' or `request group`.`to`='$ID')");
  $stmt->execute();
  $data = $stmt->fetchAll();








?>

<div class="main"><!--- start main ---->

<div class="chat">
	<div class="heading row">

	     <h1>Messages <i class="fas fa-comment"></i></h1>
	</div>

	<div class="messages">

		<?php
        // display available chats
		foreach($data as $d){

			// take contact data
			if($ID == $d['to']){
				$contact = getUserData($d['from'] , 'user');
				 // take post data 
				 $postID = $d['post ID'];
				 $stmt = $con->prepare("SELECT `title` FROM `post` WHERE `ID`='$postID'");
				 $stmt->execute();
				 $post =  $stmt->fetch();




				?>
				
		<a href="chat-view.php?id=<?php echo $d['from'];?>&post=<?php echo $postID;?>">
			<div class="chat-label">
				<div class="control">
					<?php if($d['reqStatus']==1){
						?> 
					<form method="POST">
						<input type="text" name="regID" value="<?php  echo $d['regID']; ?> " hidden>
						<button style="float:right; padding: 2px;" type="submit" name="disable" class="btn btn-danger">
						   Disable <i class="fas fa-ban"></i>
					    </button>
					</form>

						<?php

					}elseif($d['reqStatus']==0){
						?> 
					<form method="POST">
					    <input type="text" name="regID" value="<?php  echo $d['regID']; ?> " hidden>

						<button style="float:right; padding: 2px;" type="submit" name="enable" class="btn btn-success">
						   Enable <i class="far fa-check-circle"></i></i>
					    </button>
					</form>
						
						<?php
					}
					?>
				
				</div>
				<table>
					  <tr>
						<th><img class="rounded-circle" src="Admin/uploads/<?php echo $contact['type'];?>/<?php echo $contact['image'];?>" style="width: 60px;"></th>
						<th><?php echo $post['title']; ?></th>
					  </tr>

					  <tr>
						  <th><?php echo $contact['first name'].' '.$contact['last name'];?></th>
						  <td><?php?></td>
						  <td></td>
					  </tr>
				  </table>
			   </div>
		</a>

	     
			<?php
	        // end chat label -----------------------------------------------------------

			} // end if case #1
			elseif($ID == $d['from']){
				
				$contact = getUserData($d['to'] , 'user');
				// take post data 
				$postID = $d['post ID'];
				$stmt = $con->prepare("SELECT `title` FROM `post` WHERE `ID`='$postID'");
				$stmt->execute();
				$post =  $stmt->fetch();

			   ?>
			   
	   <a href="chat-view.php?id=<?php echo $d['to'];?>&post=<?php echo $postID;?>">
		   <div class="chat-label">
			   <table>
					 <tr>
					   <th><img class="rounded-circle" src="Admin/uploads/<?php echo $contact['type'];?>/<?php echo $contact['image'];?>" style="width: 60px;"></th>
					   <th><?php echo $post['title']; ?></th>
					 </tr>

					 <tr>
						 <th><?php echo $contact['first name'].' '.$contact['last name'];?></th>
						 <td><?php?></td>
						 <td></td>
					 </tr>
				 </table>
			  </div>
	   </a>

		
		   <?php
		   // end chat label -----------------------------------------------------------





			}
			// end if case #2

			
		




		}
		// end display
		
		?>

	

		

    </div>
</div>

<?php


?>


</div><!--- end main ---->



<script>


$('.main').css('margin-top',$('.nav').height()/1.9);



</script>
