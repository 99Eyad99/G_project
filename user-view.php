<?php
ob_flush();

include 'init.php';
session_start();



if(isset($_SESSION) && isset($_SESSION['ID']) && (getUserType($_SESSION['ID'])=='skilled'  || getUserType($_SESSION['ID']))=='client' ){

	$allow = 1;
	include 'includes/templates/lowerNav.php';
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
	   <link rel="stylesheet" type="text/css" href="layout/css/user-view-dark.css">
	<?php
}else{
	include 'includes/templates/bar.php';
	?>
	 <link rel="stylesheet" type="text/css" href="layout/css/user-view.css">
	<?php

}

	
}else{

	$allow = 0;
	

	?>
	  <link rel="stylesheet" type="text/css" href="layout/css/user-view.css">

	<?php
	include 'includes/templates/upperNav.php';
	include 'includes/templates/lowerNav.php';
 }





// check GET request
if(isset($_GET['id']) && !empty($_GET['id'])){
      
     // check user is exist
    if(checkItem('ID' ,'user' ,$_GET['id']) == 1){
        $ID =  filter_var($_GET['id'],FILTER_SANITIZE_NUMBER_INT);
        $userData  = getUserData($ID , 'user');
        // portfilo posts
		$post = Get_Port_Posts($ID);

		$stmt = $con->prepare("SELECT * FROM  `post` WHERE `creator ID`='$ID' AND `approved`='1' AND `section ID`='4000002' AND `deleted_at` IS NULL");
		$stmt->execute();
		$services = $stmt->fetchAll();



  if($userData['activated']==1){
  
        
        // start user veiw 
        ?>
<div class="main"><!--- start main --->

        <div class="container-fluid all" style="margin:0;"><!--start container-->

        	<div class="row"> <!--start row-->


        		<!--start col -->
        		<div class="col-sm-6 col-md-4">
        			     	<h1 class="text-center">Profile <i class="fas fa-id-card" style="margin-top: 15px;"></i></h1>

        			   <div class="view-box">
        	<div class="profile">

        		<div class="profile-img">

        		<img class="img-fluid" src="Admin/uploads/<?php echo $userData['type']?>/<?php echo $userData['image']; ?>">
        		</div>

        		<table>
        			<tr>
        				<th>First name</th>
        				<td><?php echo $userData['first name'];?></td>     			
        			</tr>

        			<tr>
        				<th>Last Name</th>
        				<td> <?php echo $userData['last name'];?></td>       
        			</tr>


        			<tr>
        				<th>type</th>
        				<td><?php echo $userData['type'];?></td>        			
        			</tr>

        		</table>







        		
        	</div>

        </div>

        			

        		</div>
        		<!--end col -->


        		<!-- start  col 2 -->
        		<div class="col-sm-6 col-md-8">

<?php

  

   if($userData['type']=='skilled'){
	   ?>

	     <h1 class="text-center" style="margin-top: 15px;">Portfolio <i class="fas fa-briefcase"></i></h1>

	   <?php


?>  



        
                      <!-- start inner container-->

	<div class="container-fluid post">
											  
        <!-- start inner row-->
        <div class="row">
							
		<?php

		// start display posts ------------------------------
		foreach($post as $p){
			
			?>
			
        	<!-- start inner col-->
			<div class="col-md-6 col-lg-4" style="margin-top: 20px;">
				<div class="card" style="width: 100%; height:100%">
  					<img src="Admin/uploads/posts/<?php echo $p['image'];?>" 
  					class="card-img-top">
  					<div class="card-body">
    					<h5 class="card-title"><?php echo $p['title'];?></h5>
  					</div>
				</div>				
			</div><!-- end inner col-->
			<?php

			}
			?>
			</div>

			<h1 class="text-center" style="margin-top: 15px;">Services <i class="fas fa-briefcase"></i></h1>

			<div class="row">
				<?php 
				foreach($services as $Hp){
					?>

			<div class="col-md-6 col-lg-4" style="margin-top: 15px;">

			<a href="post-view.php?id=<?php echo $Hp['ID'];  ?>">
				<div class="card service" style="height: 95%; margin-buttom:15px;" > 		
		   			<img src="Admin/uploads/posts/<?php echo $Hp['image'];?>" class="card-img-top" alt="..." style="height: 150px;">
	  				<div class="card-body">
						<h5 class="card-title"><?php echo $Hp['title'];?></h5>
	
						<hr>
						<label style="font-size: 17px;">Rating</label>
						<span class="fa fa-star" id="1"></span>
        				<span class="fa fa-star" id="2"></span>
        				<span class="fa fa-star" id="3"></span>
        				<span class="fa fa-star" id="4"></span>
        				<span class="fa fa-star" id="5"></span>
						<hr> 

						<span class="price">Starting at : <?php echo $Hp['price'];?>$</span>
				<?php

				if(isset($_SESSION['ID']) && (getUserType($_SESSION['ID'])=='skilled'  || getUserType($_SESSION['ID']))=='client'){
				?>	
	  				<form method="POST" action="">	
	  					<input value="<?php echo $Hp['ID'];?>" id="pID"  hidden>
	  					<button  name="add_fav"  type="submit" id="add-fav" class="btn btn-warning add-fav" style="float: right; background-color: transparent;
						border: none;">

						<?php 
						if(in_array($Hp['ID'],$user->fav)){
						?>
       						<i id="save" class="fas fa-bookmark"></i>
						<?php
						}else{	
    					?>
							<i id="not=save" class="far fa-bookmark"></i>
    					<?php
						}
						?>
						</button>
					</form>
					<?php
				}
				?>
	  			</div>

      			</div>
    		</a>
		</div>

			<?php
		}
            ?>	
			</div>

    <?php


		}

		}


		
// end display posts ----------------------------------
		?>
	</div>









	








        			</div>
        			<!-- end inner container-->
			


        		</div>
        		<!--end col 2 -->





        		
        	</div> <!--end row--> 	
        </div><!--end container--> 



		</div><!--- end main --->

     





        <?php
        // end user veiw 
    }


}




ob_end_flush();

?>


<script>

$('.main').css('margin-top',$('.nav').height()*1.3);

</script>

<script type="text/javascript" src="layout/js/save_to_fav.js"></script>

