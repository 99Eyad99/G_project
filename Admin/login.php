<?php


include 'init.php';
session_start();



?>




<link rel="stylesheet" type="text/css" href="layout/css/admin.css">

<form class="login" action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">

<?php

if(isset($_POST['submit'])){
	if( $_SERVER['REQUEST_METHOD']=='POST'){
		

		
		$ID = $_POST['ID'];
		$pass = $_POST['pass'];
		$hashedPass = sha1($pass);
		if(!empty($ID) && !empty($hashedPass)){

			
		// selecting
	
		$stmt = $con->prepare("SELECT 
		ID , password 
			FROM admin
	WHERE 
		 ID=$ID 
	AND 
	   password ='$hashedPass'
	
	LIMIT 1");
	
	$stmt->execute();
	// create session
	$row=$stmt->fetch();
	

	
	
	// create session
	

	if($stmt->rowCount()==1){
		$_SESSION['ID'] = $row['ID'];
	    $_SESSION['password'] = $row['password'];
		//transfer to admin dashboard
		header('location:dashboard.php');
	}
	else{
// if not correct
		echo '<div class="alert-danger text-center" style=>';
        echo '<h5> ID or password is not correct </h5>';
		echo '</div>';
	}
	
// if empty
		}else{

			echo '<div class="alert-danger text-center">';
            echo '<h5>ID or password is empty</h5>';
		    echo '</div>';

		}
	
	
	
	
	
	

	
	}
		  
	
	
	
	
	
	
	
	
	

	}




?>
	<h4 class="text-center">Admin login</h4>
	<input id="ID" class="form-control"  type="text" name="ID" placeholder="ID" autocomplete="off">
	<input id="password" class="form-control" type="password" name="pass" placeholder="password" autocomplete="new-password">
	<input  class="btn btn-primary btn-block" type="submit" name="submit" value="login">
</form>





