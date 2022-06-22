<?php 

include 'admin/init.php';
include 'includes/templates/upperNav.php';
include 'includes/templates/lowerNav.php';

session_start();


?>



<link rel="stylesheet" type="text/css" href="layout/css/login.css">


<div class="main">


<h1>Login</h1>



<?php
    // start form proccessing
	

	if(isset($_POST['submit'])){
        
		// input variables

		$ID = filter_var($_POST['ID'],FILTER_SANITIZE_NUMBER_INT);
	    $password = filter_var(sha1($_POST['password']),FILTER_SANITIZE_STRING);
		//----------------------------------------------------------------------
		
	
		// array that collect errors
		$error = array();

     // collecting errors
		if(empty($ID)){
			$error[] = alert('alert alert-danger', 'ID is empty' , 
            'width:80%; margin:5px 0px 0px 10%; padding:5px;');
		}

		if(empty($password)){
			$error[] = alert('alert alert-danger', 'password is empty' , 
            'width:80%; margin:5px 0px 0px 10%; padding:5px;');
		}


	// end collecting errors ------------------------------

		// display alert of errros
		if(count($error)>0){
			foreach($error as $e){
				echo $e;
			}

		}

		// end display alert of errros  -------------


		if(count($error)==0){

			// check if enter data exist in the database 
			$stmt = $con->prepare("SELECT `ID` FROM `user` 
			WHERE `ID`='$ID' AND `password`='$password' AND `activated`='1' LIMIT 1");
			$stmt->execute();
			$data = $stmt->fetch();
			$row = $stmt->rowCount();

			if($stmt){
				if($row == 1){
					$_SESSION['ID'] = $data['ID'];
					header('location:main.php');
				}else{
					alert('alert alert-danger', 'incorrect data try again' , 
            			'width:80%; margin:5px 0px 0px 10%; padding:5px;');

				}
			}



			


		}

		

	



	}
    // end form procceessing
    ?>





<form method="POST" action="login.php">
	<label form="ID">ID</label>
	<input type="text" name="ID" class="form-control" id="ID" required>

	<label form="password">password</label>
	<input type="password" name="password" class="form-control" id="password" required>


	<div class="row"><span>i can't remember your password </span><a href="resetPassword.php"> forget password</a></div>




	<button type="submit" name="submit" class="btn btn-primary">submit</button>

	<br>

	<div class="row"><span>i don't have an account </span><a href="signup.php"> signup</a></div>

	
</form>



</div>