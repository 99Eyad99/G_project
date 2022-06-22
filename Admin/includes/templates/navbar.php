<?php
$AdminName = $_SESSION['username'];
$ID = $_SESSION['ID'];

$stmt = $con->prepare("SELECT username , image FROM `users` WHERE userID=$ID ");
$stmt->execute();
$row = $stmt->fetch();






?>







<div class="toggle">
	<i class="fas fa-bars"></i>
</div>


<div class="nav">
	<ul> 
	<div class="profile">
	
	     <img class="rounded-circle" src="includes\templates\users\<?php echo $row['image']; ?>"></img>
		 <h4><?php echo $row['username']; ?></h4>
	</div>

	    
	   
		<li>
			<a href="profile.php?do=manage">
				<span class="icon"><i class="far fa-address-card"></i></span>
				<span class="title">profile</span>
			</a>
		</li>
		<li>
			<a href="../main.php">
				<span class="icon"><i class="fas fa-home"></i></span>
				<span class="title"></span>
			</a>
		</li>
		<li>
			<a href="dashborad.php">
				<span class="icon"><i class="fas fa-solar-panel"></i></span>
				<span class="title">dashboard</span>
			</a>
		</li>
		<li>
			<a href="members.php?do=manage">
				<span class="icon"><i class="fas fa-users"></i></span>
				<span class="title">members</span>
			</a>
		</li>
		<li>
			<a href="categories.php?do=manage">
				<span class="icon"><i class="fas fa-sitemap"></i></span>
				<span class="title">categoires</span>

			</a>
		</li>

		<li>
			<a href="items.php?do=manage">
				<span class="icon"><i class="fas fa-box"></i></span>
				<span class="title">items</span>

			</a>
		</li>
	
		<li>
			<a href="comments.php?do=manage">
				<span class="icon"><i class="fas fa-comments"></i></span>
				<span class="title">comments</span>

			</a>
		</li>

		<li>
			<a href="">
				<span class="icon"><i class="far fa-chart-bar"></i></span>
				<span class="title">statistics</span>

			</a>
		</li>

		<li>
			<a href="logout.php">
				<span class="icon"><i class="fas fa-sign-out-alt"></i></span>
				<span class="title">log out</span>

			</a>
		</li>
	
	</ul>

</div>





<style type="text/css">
.profile{
	margin: 5px 5px 5px -35px;
}
.profile img{
	width:70px;
}

.profile h4{
	margin-top:5px;
	color:white;
	
}


.nav{
	top:0;
	position: absolute;
	width: 85px;
	background-color: #34495e;
	transition: 0.5s;
	overflow: hidden;
	margin-bottom: -3000; /* any large number will do */
  padding-bottom: 3000px; 

	
}
	
.nav:hover{
	width: 220px;

}

.nav ul{
	position: absolute;
	top: 0;
	left: 0;
	width: 100%;

}

.nav ul li{
	position: relative;
	width: 100%;
	list-style: none;
}

.nav ul li:hover{
	background-color: #3498db;
}

.nav ul li a{
	position: relative;
	display: block;
	width: 100%;
	display: flex;
	text-decoration: none;
	color: #fff;
}


.nav ul li a .icon{
	position: relative;
	display: block;
	min-width: 60px;
	height: 60px;
	line-height: 60px;
	text-align: center;
}

.nav ul li a .icon .fa{
	font-size: 24px;
}

.nav ul li a .title{
	position: relative;
	display: block;
	padding: 0 10px;
	height: 60px;
	line-height: 60px;
	text-align: start;
	white-space: nowrap;
}

.toggle{
    position: absolute;
	top: 0;
	right:  0;
	width: 60px;
	height: 60px;
	cursor: pointer;
    margin-bottom: 10px;
}



.toggle svg{
font-size: 35px;
margin: 10px 4px 4px 10px;
}



</style>

<script type="text/javascript">

	 // toggle hide show
        $(".toggle").mousedown(function(){
            $(".nav").toggle();
             $(".nav i").removeClass();
                      
       });
	




</script>




