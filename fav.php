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
	   <link rel="stylesheet" type="text/css" href="layout/css/fav-dark.css">
	<?php
  
}else{
       include 'includes/templates/bar.php';
	?>
    
	    <link rel="stylesheet" type="text/css" href="layout/css/fav.css">
	<?php

}

?>

<div class="main"><!--- start main --->



<h1 class="text-center">My favorite <i class="fas fa-cart-arrow-down"></i></h1>

<div class="container-fuild post_container">

	<div class="row" id="output">



   		 
    <?php   

	
	// fetch fav posts  

         foreach($user->fav as $f){

         	$stmt = $con->prepare("SELECT * FROM `post` WHERE `ID`='$f'");
            $stmt->execute();
            $Hp =  $stmt->fetch();


            ?>  

<div class="col-12 col-sm-6 col-md-4 col-lg-3" style="margin:10px 0px 10px 0px;">
				<div class="card" style="height:100%;"> 
				
		   <img src="Admin/uploads/posts/<?php echo $Hp['image'];?>" class="card-img-top" alt="..." style="height: 150px;">
	  <div class="card-body">
		<a href="post-view.php?id=<?php echo $Hp['ID'];?>"><h5 class="card-title"><?php echo $Hp['title'];?></h5></a>


	

		<?php

		if(getUserType($Hp['creator ID'])['type'] == 'skilled'){
			?>

        <hr>

        <label style="font-size: 17px;">Rating</label>
		<span class="fa fa-star" id="1"></span>
        <span class="fa fa-star" id="2"></span>
        <span class="fa fa-star" id="3"></span>
        <span class="fa fa-star" id="4"></span>
        <span class="fa fa-star" id="5"></span>


			<?php
		}
		
		?>
		
		
	
		<hr> 

		<span class="price">Starting at : <?php echo $Hp['price'];?>$</span>
		<?php  	if(isset($_SESSION['ID']) && (getUserType($_SESSION['ID'])=='skilled'  || getUserType($_SESSION['ID']))=='client'){
			?>

<form method="POST" action="">
	
	<input value="<?php echo $Hp['ID'];?>" id="pID" name="pID"  hidden>
	<button name="add_fav"  type="submit" id="add-fav" class="btn btn-warning add-fav" style="float: right; background-color: transparent;
	  border: none;">
  
	  

	  <?php
  
	  if(in_array($Hp['ID'],$user->fav)){
		 
			?>
			   <i id="save" class="fas fa-bookmark"></i>
	       <?php
		  
          
		 
		  
	  }else{


		  ?>
			  	<i id="not-save" class="far fa-bookmark"></i>

		  <?php

	  }
		  
		  
		  ?>
	  </button>
	
  
	  </form>


          <?php
		} ?>
	  
	
	  </div>

      </div>



	  

      </div>

<?php  
            }


		


    ?>


		
	</div>
	

</div>


</div><!--- end main --->


<script type="text/javascript">

$('.main').css('margin-top',$('.nav').height()/2);


</script>




 <script type="text/javascript" src="layout/js/save_to_fav.js"></script>




