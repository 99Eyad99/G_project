<?php


ob_start();
include 'init.php';
include 'includes/templates/upperNav.php';
include 'includes/templates/lowerNav.php';
include 'sender.php';


?>



<h1>Contact us</h1>


 <h2 class="contact">swfoffical@gmail.com <i class="far fa-envelope"></i></h2>



</form>

<style>
    body{
	background-color: #130f40;
}

 form{
	width: 50%;
	margin: 50px 0px 20px 25%;
}

form label{
	color: white;
	font-size: 20px;
}

h1{
	color: white;
	text-align: center;
	margin-top: 20px;
	margin-bottom: 20px;
}

.contact{
    color: white;
	text-align: center;
	margin-top: 60px;
}


button{
	margin-top: 20px;
	margin-bottom: 20px;
	float: right;
	
}


</style>