<?php 
ob_start();
include 'init.php';
include 'includes/templates/upperNav.php';
include 'includes/templates/lowerNav.php';
include 'sender.php';


// functions


// start add account funtion -------------------------------------
function addAccount($email,$F_name,$L_name,$password,$Feild,$type){

	// start discover and collect input errors -------------------------------------
$erorr = array();

if(empty($email)){
$erorr[] = alert('alert alert-danger text-center','Email is empty' , 
	'width:80%; margin:10px 0px 0px 10%; padding:10px; ');

}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
$erorr[] = alert('alert alert-danger text-center','Email is invalid' , 
	'width:80%; margin:10px 0px 0px 10%; padding:10px; ');
}

if(checkItem('email' , 'user' , $email)){
$erorr[] = alert('alert alert-danger text-center','Email is already used' , 
	'width:80%; margin:10px 0px 0px 10%; padding:10px; ');

}

if(empty($F_name)){
$erorr[] = alert('alert alert-danger text-center','First name is empty' , 
	'width:80%; margin:10px 0px 0px 10%; padding:10px; ');

}


if(empty($L_name)){
$erorr[] = alert('alert alert-danger text-center','Last name is empty' , 
	'width:80%; margin:10px 0px 0px 10%; padding:10px; ');

}

if(empty($password)){
$erorr[] = alert('alert alert-danger text-center','Password is empty' , 
	'width:80%; margin:10px 0px 0px 10%; padding:10px; ');
}else{

	if(!preg_match('/^(?=.*[A-Za-z])/', $password)) {
		$erorr[] = alert('alert alert-danger text-center','Password must conatin atleast one letter' , 
						'width:80%; margin:10px 0px 0px 10%; padding:10px; ');
	}

	if(strlen($_POST['password'])<8){
		$erorr[] = alert('alert alert-danger text-center','Password length must greater or equal than 8' , 
			'width:80%; margin:10px 0px 0px 10%; padding:10px; ');
			
	}

}


if(empty($type)){
$erorr[] = alert('alert alert-danger text-center','Type is not selected' , 
	'width:80%; margin:10px 0px 0px 10%; padding:10px; ');
}

if(!empty($Feild) &&  $type == 'client'){

$erorr[] = alert('alert alert-danger text-center','Feild is not needed for client' , 
'width:80%; margin:10px 0px 0px 10%; padding:10px; ');

}

if(empty($Feild) &&  $type == 'skilled'){

$erorr[] = alert('alert alert-danger text-center','Feild is requried for skilled' , 
'width:80%; margin:10px 0px 0px 10%; padding:10px; ');

}

$types = ['skilled' ,'client'];


if(!in_array($type,$types)){
$erorr[] = alert('alert alert-danger text-center','type is invalid' , 
	'width:80%; margin:10px 0px 0px 10%; padding:10px; ');
}

// end discover and collect input errors ...........................................................


// display error alerts
if(count($erorr)>0){
	foreach($erorr as $e){
		echo $e;
	}
}

// .......................


// start ensure that token is unique ---------------------

// fetch tokens 
global $con;
$stmt = $con->prepare("SELECT `token` FROM `user`");
$stmt->execute();
$tokens_count = $stmt->rowCount();
$tokens = $stmt->fetchAll();

$token = $token = bin2hex(random_bytes(16));


$found = 0;
$check = 0;
do{

$token = $token = bin2hex(random_bytes(16));
if($tokens_count>0){
	foreach($tokens as $t){
		if($t['token']!=$token){
			$check = 1;
		}else{
			$check = 0;
		}	
	}
	$found = $check;


}

}while($found == 0);




if(count($erorr)==0){

	$password = sha1($password);


    
	$stmt = $con->prepare("INSERT INTO `user`
	(`password`, `token`, `first name`, `last name`, `email`,
	 `register Date`, `type`, `Feild`, `activated`, `view`) 
	VALUES ('$password','$token','$F_name','$L_name','$email',now(),'$type','$Feild','0','0')");
	$stmt->execute();

	if($stmt){
		
		alert('alert alert-success text-center','registerd successfully' , 
	'width:80%; margin:10px 0px 0px 10%; padding:10px; ');

	alert('alert alert-info text-center','Check your email to find your ID and activation link' , 
	'width:80%; margin:10px 0px 0px 10%; padding:10px; ');

	$stmt = $con->prepare("SELECT `ID`,`token` FROM `user` WHERE `email`='$email'");
	$stmt->execute();
	$data = $stmt->fetch();

	

	sendForActivate($email , 'Login and activation' , 
	'<h1>Congratulations, you did register successfully</h1> <br>
	<label style="font-size:18px;">Your ID is '.$data['ID'].'</label> <br>
	<label style="font-size:18px;"> Press the link to activate your account </label><br>
	 ' , 
	"<a href='http://localhost/SFW/activate.php?token=".$data['token']."'>Activate'</a>");

	
	}




}






}
// end function



?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>


<link rel="stylesheet" type="text/css" href="layout/css/signup.css">

<div class="main">


<h1>Register now</h1>




<div class="alertsArea">


</div>

<?php

if(isset($_POST['submit'])){

	$email = filter_var($_POST['email'],FILTER_SANITIZE_EMAIL);
    $F_name = filter_var($_POST['F_name'],FILTER_SANITIZE_STRING);
    $L_name = filter_var($_POST['L_name'],FILTER_SANITIZE_STRING);
	$password = $_POST['password'];
    $Feild =filter_var($_POST['Feild'],FILTER_SANITIZE_STRING);
    $type = filter_var($_POST['type'],FILTER_SANITIZE_STRING);
	

	addAccount($email,$F_name,$L_name,$password,$Feild,$type);




}



?>



<form method="POST" action="">
	<label for="email">Email</label>
	<input type="email" name="email" class="form-control" id="email" required>

	<div class="row">

		<div class="col-md-6">
	<label for="F_name">First Name</label>
	<input id="F_name" type="text" name="F_name" class="form-control" required>
	</div>

    <div class="col-md-6">
	<label for="L_name">Last Name</label>
	<input id="L_name" type="text" name="L_name" class="form-control" required>
	</div>

	</div>

	<div class="row">

		<div class="col-md-6">
	<label for="password">password</label>
	<input id="password" type="password" name="password" class="form-control" required>
	</div>

		<div class="col-md-6">
	<label for="Feild">Field</label>
	<input id="Feild" type="text" placeholder="Enter only if account type is skilled" name="Feild" class="form-control">
	</div>

	</div>



	<label for="select" required>account type</label>
	<select class="form-control" id="select" name="type">
		<option value="client">client</option>
		<option value="skilled" id="skilled">skilled</option>
	</select>

	<button type="submit" id="submit" name="submit" class="btn btn-primary">submit</button>
</form>



</div>


<script>

/*	
$("form").submit(function(e){
    e.preventDefault();
  });

$(document).ready(function(){

	$('#submit').click(function(){

// input 

var email = $('#email').val();
var firstName = $('#F_name').val();
var lastName = $('#L_name').val();
var password = $('#password').val();
var feild = $('#Feild').val();
var type = $('#select').val();


// ------------------------------

function addAccount(email,firstName,lastName,password,feild,type){

	
	$.ajax({
	  method:'POST',
	  url:'ajax/addAccount.php',
	  data:{
		   email:email,
		   firstName:firstName,
		   lastName:lastName,
		   password:password,
		   feild:feild,
		   type:type	
		}
		,
	success:function(data){
		//$('.alertsArea').HTML(data);
		alert(data);
	}

})


}




addAccount(email,firstName,lastName,password,feild,type);


 
})








})






/*





</script>



