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

 }else{
	//header('location:logout.php');
 }


if($user->getView()=='1'){
	include 'includes/templates/bar-dark.php';
	?>
	
	   <link rel="stylesheet" type="text/css" href="layout/css/posts-dark.css">
	<?php
}else{
	include 'includes/templates/bar.php';
	?>
	 <link rel="stylesheet" type="text/css" href="layout/css/posts.css">
	<?php

}


	
}else{

	
	

	?>
	 <link rel="stylesheet" type="text/css" href="layout/css/posts.css">
	   	 

	<?php
	include 'includes/templates/upperNav.php';
	include 'includes/templates/lowerNav.php';


}

?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<div class="main">


	 <!--- start search ----->

 <div class="search">
     
            <form method="POST">
               
                <label form="search" for="search">Search for post <i class="fas fa-search"></i></label>
                <div class="row">
                    <div class="col-md-8">
                               <input id="search" id="search" type="text" name="search" class="form-control">
                    </div>


                    <div class="col-md-4">


                <select id="search-by" name="search-by" class="form-control">
                     <option value="title">Title</option>
                     <option value="price">Price</option>
                   </select>
                        
                    </div>

                </div>
            </form>

</div>

 <!--- end search ----->



	<!--- start container ----->
	<div class="container-fluid posts">

		<div class="row" id="output"><!--- row row ----->


<?php

if($_GET['view']=='hire'){

	// fetch posts  -----------------------------

  	$stmt = $con->prepare("SELECT * FROM `post` WHERE `approved`='1' AND `section ID`='4000002' AND `deleted_at` IS NULL");
    $stmt->execute();
    $Hpost =  $stmt->fetchAll();


// end fetch posts -----------------------

    ?>


<?php

   // start loop 
   foreach($Hpost as $hp){
   	     
   	      $userType =getUserType($hp['creator ID']);


          // start if ----------------------------------
   	      if($userType['type']=='client'){
                   // diplays posts
   	      	?>

   	    <!--- start col-3 ----->
			<div class="col-12 col-sm-6 col-md-4 col-lg-3" style="margin-top: 10px;">
	<a href="post-view.php?id=<?php echo $hp['ID'];?>">
	<div class="card" style="width: 100%; height:100%;"> 
				
		   <img src="Admin/uploads/posts/<?php echo $hp['image'];?>" class="card-img-top" alt="..." style="height: 150px;">
	  <div class="card-body">

		<h5 class="card-title"><?php echo $hp['title'];?></h5>

		<hr> 

		<span class="price">Starting at : <?php echo $hp['price'];?>$</span>
		<?php

		if(isset($_SESSION['ID']) && (getUserType($_SESSION['ID'])=='skilled'  || getUserType($_SESSION['ID']))=='client'){

			?>
		
	  <form method="POST" action="">
	
	  <input value="<?php echo $hp['ID'];?>" id="pID"  hidden>
	  <button  name="add_fav"  type="submit" id="add-fav" class="btn btn-warning add-fav" style="float: right; background-color: transparent;
		border: none;">

		

		<?php if(in_array($hp['ID'],$user->fav)){

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
	    <!--- end col-3 ----->


   	      	<?php
   	      	 // end display posts




   	      }
   	      // end if ---------------------------------------




       
   }
   // end loop


		

?>
                

	</div><!--- end row ----->
		

	</div>

	<!--- end container ----->

	<!--- start js live search ----->
<script type="text/javascript">






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
            where: where
           
        }
        ,
        success:function(data){
            $('#output').html(data);
      
        }
    })

    

    
    
})
	



	
</script>
<!--- end js live search ----->





    <?php



}// end GET[hire]
elseif($_GET['view']=='service'){

// fetch posts  -----------------------------

  	$stmt = $con->prepare("SELECT * FROM `post` WHERE `approved`='1' AND `section ID`='4000002'AND `deleted_at` IS NULL");
    $stmt->execute();
    $Hpost =  $stmt->fetchAll();


// end fetch posts -----------------------

     // start loop 
   foreach($Hpost as $hp){
   	     
   	      $userType =getUserType($hp['creator ID']);


          // start if ----------------------------------
   	      if($userType['type']=='skilled'){
                   // diplays posts
   	      	?>

   	    <!--- start col-3 ----->
			<div class="col-12 col-sm-6 col-md-4 col-lg-3" style="margin-top: 10px;">
	<a href="post-view.php?id=<?php echo $hp['ID'];?>">
	<div class="card" style="width: 100%; height:100%;"> 
				
		   <img src="Admin/uploads/posts/<?php echo $hp['image'];?>" class="card-img-top" alt="..." style="height: 150px;">
	  <div class="card-body">

		<h5 class="card-title"><?php echo $hp['title'];?></h5>

		<hr>

		<label style="font-size: 17px;">Rating</label>
		<span class="fa fa-star" id="1"></span>
        <span class="fa fa-star" id="2"></span>
        <span class="fa fa-star" id="3"></span>
        <span class="fa fa-star" id="4"></span>
        <span class="fa fa-star" id="5"></span>

		<hr> 

		<span class="price">Starting at : <?php echo $hp['price'];?>$</span>
		<?php

		if(isset($_SESSION['ID']) && (getUserType($_SESSION['ID'])=='skilled'  || getUserType($_SESSION['ID']))=='client'){

			?>
		
	  <form method="POST" action="">
	
	  <input value="<?php echo $hp['ID'];?>" id="pID"  hidden>
	  <button  name="add_fav"  type="submit" id="add-fav" class="btn btn-warning add-fav" style="float: right; background-color: transparent;
		border: none;">

		

		<?php if(in_array($hp['ID'],$user->fav)){

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
	    <!--- end col-3 ----->
	
<?php

}
}

?>


</div>
</div>


</div>


<!--- start js live search ----->
<script type="text/javascript">


$("#search").keyup(function(){


    var search_by = $('#search-by').val();
    var search_input = $('#search').val();
    var where = 'skilled';



        $.ajax({
        method:'POST',
        url:'ajax/post_fetch.php',
        data:{
            search:search_input,
            by:search_by,
            where: where
           
        }
        ,
        success:function(data){
            $('#output').html(data);
      
        }
    })

    

    
    
})
	



	
</script>
<!--- end js live search ----->









<?php
}// end GET[service]

?>

<script >

	$(document).ready(function(){
		$('.main').css('margin-top',$('.nav').height());

	})    

</script>

















	



</div>




 
 <script type="text/javascript" src="layout/js/save_to_fav.js"></script>



	









