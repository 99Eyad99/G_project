<?php

include 'init.php';
include 'includes/templates/upperNav.php';
include 'includes/templates/lowerNav.php';

include 'sender.php';

if(!isset($_GET['id'])){

	if(isset($_POST['submit'])){

		$email = filter_var($_POST['email'],FILTER_SANITIZE_EMAIL);

		if(!filter_var($_POST['email'],FILTER_VALIDATE_EMAIL)){

			alert('alert alert-danger text-center','Email is not valid' , 
				'width:80%; margin:10px 0px 0px 10%; padding:10px; ');


		}else{

			$stmt = $con->prepare("SELECT `activated` FROM `user` WHERE `email`='$email'");
	    	$stmt->execute();
			$is_activated = $stmt->fetch();

	
			if(checkItem( 'email' , 'user' ,$email) == 1 && $is_activated['activated'] == 1){


				// fetch submitted email

				$stmt = $con->prepare("SELECT `token` FROM `user` WHERE `email`='$email'");
	    		$stmt->execute();
				$token = $stmt->fetch();
				$token = $token['token'];

				if($stmt->rowCount()==1){

					sendForActivate($email , 'Password reset' , 
							'<h2>Dear user</h2> <br>
							<label style="font-size:18px;">To reset your password press the link below </label> <br>
						 ' , '<a href="http://localhost/SFW/resetPassword.php?id='.$token.'"> reset password </a>');
	
				 	alert('alert alert-info text-center','reset password link has sent to your email' , 
					'width:80%; margin:10px 0px 0px 10%; padding:10px; ');
	
				}

			}
			
		}

	}

}elseif(isset($_GET['id']) && checkItem('token' , 'user' , $_GET['id']) == 1){

	$token = $_GET['id'];
	$stmt = $con->prepare("SELECT `ID`,`email` FROM `user` WHERE `token`='$token'");
	$stmt->execute();
	$data = $stmt->fetch();

	$ID = $data['ID'];
	$email = $data['email'];

	if($stmt->rowCount()==1){

		       // fetch tokens and check if generate token is unique
			   $stmt = $con->prepare("SELECT `token` FROM `user`");
			   $stmt->execute();
			   $tokens_count = $stmt->rowCount();
			   $tokens = $stmt->fetchAll();
			   $token = $token = bin2hex(random_bytes(16));
			   $found = 0;
			   $check = 0;
			   do{
				  $token = $token = bin2hex(random_bytes(16));
				  if($tokens_count > 0){
					  foreach($tokens as $t){
	  
						  if($t['token'] != $token){
							  $check = 1;
						  }else{
							  $check = 0;
						  }	
					  }  
				   $found = $check;
			   }
			   }while($found == 0);
	  
			   if($check == 1){
				  
				  // after activating the account the token will be changed 
				  $stmt = $con->prepare("UPDATE `user` SET `token` = '$token' WHERE `ID` = '$ID' ");
				  $stmt->execute();
			   }

			   $password =  bin2hex(random_bytes(8));
			
			   // encryption
			   $ecryptoPass = sha1($password);

		       $stmt = $con->prepare("UPDATE `user` SET `password`='$ecryptoPass' WHERE `ID`='$ID'");
			   $stmt->execute();

			 
				sendForActivate($email , 'Your new passwrd' , 
								'<h2>Dear user</h2> <br>
						<label style="font-size:18px;">Your new password is :'.$password.'</label> <br> ' , '');

	 			alert('alert alert-info text-center','Your new password has sent to your email' , 
	 					'width:80%; margin:10px 0px 0px 10%; padding:10px; ');

	

	}


}




?>

<link rel="stylesheet" type="text/css" href="layout/css/signup.css">

<div class="main">


<h1>Reset password</h1>


<form method="POST" action="">
	<label for="email">Email</label>
	<input type="email" name="email" class="form-control" id="email" placeholder="Enter your email" required>


	<button type="submit" name="submit" class="btn btn-primary">submit</button>
</form>


</div>