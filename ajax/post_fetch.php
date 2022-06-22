<?php



include '../connect.php';
include '../includes/functions/fun.php';

session_start();
if(isset($_SESSION['ID'])){
	$ID = $_SESSION['ID'];
	$type = getUserType($ID);

	if($type['type']=='client'){
	include '../classes/client.php';
	 $user = new client();
 	  $user->setID($ID);

}
elseif($type['type']=='skilled'){
	include '../classes/skilled.php';
	 $user = new skilled();
 	  $user->setID($ID);
}

}





$search = trim($_POST['search'],'\'"');
$search_by = $_POST['by'];
$where = $_POST['where'];


     
$stmt = $con->prepare("SELECT `post`.*,`user`.`type` FROM `post`,`user` 
	                   WHERE `post`.`creator ID`=`user`.`ID` AND `$search_by` LIKE '%$search%' AND `post`.`section ID`='4000002' AND  `post`.`approved`='1'AND `post`.`deleted_at` IS NULL  ");
$stmt->execute();
$posts= $stmt->fetchAll();



foreach($posts as $hp){
    if($hp['type']==$where){
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