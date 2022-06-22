<?php

session_start();

include_once '../init.php';



if(!isset($_SESSION['ID'])){
	header('location:login.php');
}

$ID = $_SESSION['ID'];



// get use type

 $type = getUserType($ID);
 $type = $type['type'];

 // create skilled or client object 
 if($type == 'skilled'){
 	  include '../classes/skilled.php';
       
 	  $user = new skilled();
 	  $user->setID($ID);

 }else{

 	  include '../classes/client.php';
 	  $user = new client();
 	  $user->setID($ID);

 }

 $fav= $user->getFav();


if(count($fav)>0){

    foreach($user->fav as $pID){
      

      $stmt = $con->prepare("SELECT * FROM `post` WHERE `ID`='$pID' ");
      $stmt->execute();
      $Hp= $stmt->fetch();

      // card 
      ?>

<div class="col col-md-4 col-lg-4">
				<div class="card" style="max-width:18rem; min-width:15rem;"> 
				
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

      // end card

    


    }



}






?>


<script>



$(".card form").submit(function(e){
    e.preventDefault();
  });

	/*

// add or delete from fav list

$(".btn-warning").click(function(){


	


if($(this).children().attr('id')=='save'){
	$(this).children('.fa-bookmark').remove();
	$(this).html('<i id="not-save" class="far fa-bookmark"></i>');

}else{
	
	$(this).children('.fa-bookmark').remove();
	$(this).html('<i id="save" class="fas fa-bookmark"></i>');

}



var post_ID = $(this).parent().children('#pID').val();

$.ajax({
method:'POST',
url:'ajax/add_to_fav.php',
data:{
	pID:post_ID
}
,
success:function(data){
	


}

})

})


*/


</script>