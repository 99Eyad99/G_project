<?php

include 'init.php';
session_start();







?>


<form class="login" action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">
	<h4 class="text-center">Admin login</h4>
	<input id="name" class="form-control"  type="text" name="name" placeholder="username" autocomplete="off">
	<input id="pass" class="form-control" type="password" name="pass" placeholder="password" autocomplete="new-password">
	<input  class="btn btn-primary btn-block" type="submit" name="submit" value="login">
</form>


<?php

if(isset($_POST['submit'])){

	$name = $_POST['user'];
	$pass = $_POST['pass'];
	$hashedPass = sha1($pass);

	$stmt = $con->prepare("SELECT 
	ID , first name , last name , password 
		FROM admin
WHERE 
	 ID=? 
AND 
   password =? 
AND 
   GroupID=1
LIMIT 1");

//12345678
$stmt->execute(array($user,$pass));
$row=$stmt->fetch();
$count = $stmt->rowCount();



if($count==1 && $row['GroupID']==1){
   $_SESSION['username'] = $row['username'];
   $_SESSION['ID'] = $row['userID'];

   header('location:dashborad.php');

}
else{
echo 'wrong username or passowrd';
}

}





?>

