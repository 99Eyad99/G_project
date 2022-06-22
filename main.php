<?php

include 'init.php';
session_start();



if(isset($_SESSION) && isset($_SESSION['ID']) && (getUserType($_SESSION['ID'])=='skilled'  || getUserType($_SESSION['ID']))=='client' ){
	
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

 }


if($user->getView()=='1'){
	include 'includes/templates/bar-dark.php';
	?>
	
	   <link rel="stylesheet" type="text/css" href="layout/css/main-dark.css">
	<?php
}else{
	include 'includes/templates/bar.php';
	?>
	 <link rel="stylesheet" type="text/css" href="layout/css/main.css">
	<?php

}




	
}else{

	
	

	?>
	   <link rel="stylesheet" type="text/css" href="layout/css/main.css">
	<?php
	include 'includes/templates/upperNav.php';
	include 'includes/templates/lowerNav.php';


}


 





?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>




<div class="main">


    
<h1 id="h1">Hiring Posts <a href="Posts.php?view=hire"><button type="button" class="btn btn-primary">view more</button></a></h1>
	<div  class="container-fuild hiring_posts">
	<div class="row" id='output'>
	<?php



  $stmt = $con->prepare("SELECT `post`.* FROM `post`,`user` 
                         WHERE `section ID`='4000002' AND `approved`='1' AND `deleted_at` IS NULL
						 AND `creator ID`=`user`.`ID` AND `user`.`type`='client' ORDER BY `post`.`ID` DESC
                         LIMIT 4 ");
  $stmt->execute();
  $H_post = $stmt->fetchAll();


	
    // start display posts

    foreach($H_post as $Hp){
  
			
			?>

<div class="col-12 col-sm-6 col-md-4 col-lg-3" style="margin: 10px 0px 10px 0px;">

    <a href="post-view.php?id=<?php echo $Hp['ID'];  ?>">
		<div class="card" style="height: 100%;"> 
		   <img src="Admin/uploads/posts/<?php echo $Hp['image'];?>" class="card-img-top" alt="..." style="height: 150px;">
	  <div class="card-body">
		<a href="post-view.php?id=<?php echo $Hp['ID'];?>"><h5 class="card-title"><?php echo $Hp['title'];?></h5></a>


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
      </a>

<?php  
            

        

    }
	// end display posts

	?>

	
		


   </div><! --end row-- >

   </div>


   <h1>Services offered by skilleds <a href="posts.php?view=service"><button type="button" class="btn btn-primary">view more</button></a></h1> 
   
	<div  class="container-fuild hiring_posts">
	<div class="row" id='output'>
	<?php


  $stmt = $con->prepare("SELECT `post`.* FROM `post`,`user` 
                         WHERE `section ID`='4000002' AND `approved`='1' AND  `deleted_at` IS NULL
						 AND `creator ID`=`user`.`ID` AND `user`.`type`='skilled' ORDER BY `post`.`ID` DESC
                         LIMIT 4 ");
  $stmt->execute();
  $H_post = $stmt->fetchAll();

    // start display posts

    foreach($H_post as $Hp){


		


			?>
			<div class="col-12 col-sm-6 col-md-4 col-lg-3" style="margin: 10px 0px 10px 0px;">

	<a href="post-view.php?id=<?php echo $Hp['ID'];  ?>">
				<div class="card" style="height: 100%;"> 
				
		   <img src="Admin/uploads/posts/<?php echo $Hp['image'];?>" class="card-img-top" alt="..." style="height: 150px;">
	  <div class="card-body">

		<a href="post-view.php?id=<?php echo $Hp['ID'];?>"><h5 class="card-title"><?php echo $Hp['title'];?></h5></a>
		

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

		

		<?php if(in_array($Hp['ID'],$user->fav)){

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



	  

      </div>
    </a>

<?php  


    }
	// end display posts

	?>

			
		


   </div><! --end row-- >

  
  



</div>

<script type="text/javascript" src="layout/js/save_to_fav.js"></script>

<script type="text/javascript">


$('.main').css('margin-top',$('.nav').height()/1.9);



	// start live search

$("#search").keyup(function(){


    var search_by = $('#search-by').val();
    var search_input = $('#search').val();
    var where = 'client';


        $.ajax({
        method:'POST',
        url:'ajax/post_fetch.php',
        data:{
            search:search_input,
            by:search_by,
            where : where
            
        }
        ,
        success:function(data){
            $('#output').html(data);

            
      
        }
    })

     

    
})

 
// end live search

	

</script>

