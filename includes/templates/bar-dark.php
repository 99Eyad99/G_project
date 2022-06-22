<?php?>
<div class="nav" style="position: fixed; 
	z-index: 1000;
	width:100%;
	top:0;">

<div class="lowerNav">

<nav class="nav">
<a class="nav-link active" aria-current="page" href="main.php">home</a>
  <a class="nav-link active" aria-current="page" href="Posts.php?view=hire">find a job</a>
  <a class="nav-link" href="posts.php?view=service">find a service</a>
  <a class="nav-link" href="findSkilled.php">find a skilled</a>
  <a class="nav-link" href="contact_us.php">contact us</a>
  <a class="nav-link" href="about_us.php">about us</a>
 
</nav>

</div>





<style type="text/css">

.lowerNav{
  width: 100%;
  background-color: black;
  

}

.lowerNav .nav-link{
  color: white;
}
  



</style>

<div class="topbar">
    
    <div class="toggle"></div> 
	

    <div class="profile row">

<div class="noti row">


	
		<a href="chat.php" style="margin-right:15px;">	
			<i class="fas fa-comment"></i>	
			<label class="mess" style="font-size:14px;">1</label>
	    </a>
			
	<a href="fav.php"  style="margin-right:15px;">
	       <i class="fas fa-shopping-cart"></i>
	       <label class="cart" style="font-size:14px;"></label>	
   </a>


   <a href="noti.php" style="margin-right:15px;">
	       <i class="far fa-bell"></i>
	       <label class="notes" style="font-size:14px;"></label>	
   </a>
	
	
		

</div>

<div class="row admin_img">
<img class="rounded-circle"  src="Admin/uploads/<?php echo $type;?>/<?php echo $user->getImage();?>"  style="width: 45px;">
<i class="fas fa-caret-down" style="color:white;"></i>

</div>

</div>   

    

</div>


    <!--  below top bar --->

    <div class="profile-drop-down">
       <ul>
             <li><a href="profile.php">My profile <i class="fas fa-id-badge"></i></a></li>
			<li><a href="dashboard.php">dashboard <i class="fas fa-desktop"></i></a></li>
			<li><a href="add-post.php">Add post</a></li>
            <li><a href="logout.php">logout <i class="fas fa-sign-out-alt"></i></a></li>      
        </ul>
</div>


</div>




<style>



.topbar{
	
	width: 100%;
	right: 0;
	background-color: black;
	height: 60px;
	display: flex;
	justify-content: space-between;
	align-items: center;

}

.topbar .profile{

	float: right;
	margin-right: 30px;	
}

.topbar .profile  .noti{
	text-decoration:none;
	color:white;
	font-size: 30px;
	margin: 12px 35px 0px 0px;
		
}


.topbar .profile  .noti a{
	text-decoration:none;
	color:white;

}

.topbar .profile  .noti a label{
	padding: 5px;
	border-radius: 50%;
	background-color: #c0392b;
	margin-left: -5px;
	position: absolute;
}



.topbar .profile  .noti .cart{
	font-size:13px; 
	padding:5px; 


}

.topbar .profile .admin_img{
	padding: 5px;
}




.profile-drop-down{
	background-color: black;
	min-width:150px;
	margin-left:calc(100% - 160px) ;
	margin-right: 15px;


}

.profile-drop-down ul{
	width: 100%;
}

.profile-drop-down ul li{
	list-style: none;
	width: 100%;


}

.profile-drop-down ul li a{
	display: block;
	text-decoration: none;
	font-size: 18px;
	color: white;
	line-height: 35px;
	text-align: center;
}

.profile-drop-down ul li a:hover{
	


}




</style>



<script type="text/javascript">




// start drop-down 

$(document).ready(function(){

// hide the below element
$('.profile-drop-down').css('display','none');


$('.admin_img').click(function(){
if($('.profile-drop-down').is(':visible') == true){
    $('.profile-drop-down').slideUp(1000);

}
else{
    $('.profile-drop-down').slideDown(1000);
}

});



});

// end drop down

// get cart count

function loadDoc() {
  
  setInterval(function(){

   var xhttp = new XMLHttpRequest();
   xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) { 
	     document.querySelector(".cart").innerHTML =  xhttp.responseText;
	
    }

   };
   xhttp.open("GET", 'ajax/cartCount.php', true);
   xhttp.send();

  },1000);


 }
 loadDoc();


 // get requests and notifications


 function loadNote() {
  
  setInterval(function(){

   var xhttp = new XMLHttpRequest();
   xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) { 
	     document.querySelector(".notes").innerHTML =  xhttp.responseText;
	
    }
   };
   xhttp.open("GET", 'ajax/getNotifications.php', true);
   xhttp.send();

  },1000);


 }
 loadNote();



  // get not seen messages

  function loadMessages() {
  
  setInterval(function(){

   var xhttp = new XMLHttpRequest();
   xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) { 
	     document.querySelector(".mess").innerHTML =  xhttp.responseText;
	
    }
   };
   xhttp.open("GET", 'ajax/CountMessages.php', true);
   xhttp.send();

  },1000);


 }
 loadMessages();








</script>
