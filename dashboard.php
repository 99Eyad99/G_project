<?php
ob_start();


include 'init.php';
session_start();



if(isset($_SESSION) && isset($_SESSION['ID']) && (getUserType($_SESSION['ID'])=='skilled'  || getUserType($_SESSION['ID']))=='client' ){
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

 }

if($user->getView()=='1'){
	include 'includes/templates/bar-dark.php';
	?>
	
	   <link rel="stylesheet" type="text/css" href="layout/css/dashboard-dark.css">
	<?php
}else{
	include 'includes/templates/bar.php';
	?>
	 <link rel="stylesheet" type="text/css" href="layout/css/dashboard.css">
	<?php

}


}else{
	header('location:login.php');
}




// select post that posted by the user

    $stmt = $con->prepare("SELECT * FROM `post` WHERE `creator ID`='$ID' AND `deleted_at` IS NULL ");
    $stmt->execute();
    $posts = $stmt->fetchAll();
// -------------------------------------------


// delete -------------------------------
    if(isset($_POST['delete'])){
    	$postID = $_POST['ID'];

    	// check if exist
    	$exist=0;
    	foreach($posts as $p){
    		if($p['ID'] == $postID && $user->getID() == $p['creator ID'] ){
    			$exist =1;
    		}
        }

       if($exist==1){

       	  $stmt = $con->prepare("DELETE FROM `post` WHERE `ID`='$postID'");
          $stmt->execute();


       }

 }





// end delete ---------------------------


// start delivered ------------------------------

if(isset($_POST['delivered'])){

		$gID= filter_var($_POST['gID'],FILTER_SANITIZE_NUMBER_INT);
		$postID = filter_var($_POST['postID'],FILTER_SANITIZE_NUMBER_INT);

		
        // take requests group ID that user (client) that allowed make this action with them
       	$stmt = $con->prepare("SELECT * FROM `request group` WHERE 
		   `ID`='$gID' AND `post ID`='$postID' AND (`from`='$ID' OR `to`='$ID')");
        $stmt->execute();
		$count = $stmt->rowCount();
        $row = $stmt->fetch();

		if($count==1){

			$stmt = $con->prepare("SELECT `title` FROM `post` WHERE `ID`='$postID'");
            $stmt->execute();
            $title =   $stmt->fetch();
            $title = $title['title'];


			$stmt = $con->prepare("UPDATE `request group` SET `status`='1' WHERE `ID`='$gID'");
            $stmt->execute();


            if($row['from'] == $ID){
            	sendNote('Delivering done' ,'You delivered the service : '.$title,$ID,$row['to']);
				sendNote('Rating and commenting ' 
				,'<br>You share your experience by rating and comment section : <a calss="btn" href="post-view.php?id='.$postID.'">'.$title.'</a>'
				,$ID,$row['from']);

            }
            else{
            	sendNote('Delivering done' ,'You delivered the service : '.$title,$ID,$row['from'] );
            }


		}


}

// end delivered ---------------------------------




    ?>


 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
 <!--- plugin --->
 <link rel="stylesheet" href="plugs/jq-confirm-prompt-box/jq-confirm-prompt-box/dist/css/jq-prompt.min.css">
 <script src="plugs/jq-confirm-prompt-box/jq-confirm-prompt-box/dist/js/jq-prompt.min.js"></script>


 

  <!--- plugin --->

  <div class="main"> <!--- start main --->




<!-- start container --->
  <div class="container-fluid">
  	<div class="row"><!-- start row --->

  			<?php

if($type == 'skilled'){


	?>

<div class="col-md-12"><!-- start col --->

	<h3 class="req-title">My services providing <i class="fas fa-tasks"></i> </h3>
<!--- start delivering -->
<div class="table-responsive req-table">



	<table class="table">
		<tr> 
			<th>Client</th>
			<th>Post</th>
			<th>Price</th>
			<th>status</th>
		</tr>
<?php


$stmt = $con->prepare("SELECT `request`.*,`request Group`.`post ID`,`request Group`.`from`,`request Group`.`to`
                       ,`request Group`.`status` as 'group status'
        FROM `request`,`request Group` WHERE `request Group`.`status`='0' AND `accept`='1' AND `request`.`status`='1' AND `type`='delivering' 
    	AND`request`.`GroupID`=`request Group`.`ID` 
		AND (`request Group`.`from`='$ID' OR `request Group`.`to`='$ID')");
$stmt->execute();
$req = $stmt->fetchAll();




foreach($req as $r){

	if($r['from'] == $ID){

		$user = $r['to'];

		$stmt = $con->prepare("SELECT * FROM `user` WHERE `ID`='$user'");
        $stmt->execute();
        $user = $stmt->fetch();

	}
	elseif($r['to'] == $ID){

		$user = $r['from'];

		$stmt = $con->prepare("SELECT * FROM `user` WHERE `ID`='$user'");
        $stmt->execute();
        $user = $stmt->fetch();

	}


	$post = $r['post ID'];

	$stmt = $con->prepare("SELECT `title` FROM `post` WHERE `ID`='$post'");
    $stmt->execute();
    $post=   $stmt->fetch();

    ?>

    <tr>
			<td>
				<img src="Admin/uploads/<?php echo $user['type']; ?>/<?php echo $user['image']; ?>" class="rounded-circle" style="width: 45px;">
				 <?php echo $user['first name'].' '.$user['last name']; ?>
			</td>
			<td><?php echo $post['title']; ?></td>
			<td><?php echo $r['price'].'$'; ?></td>
			<td>
				<?php 
				  if($r['group status'] == 0){

				  	  ?> <span> <i class="far fa-clock"></i></span> <?php
				  }

				 ?>
			</td>
	</tr>




    <?php



}




	?>

		
	</table>

</div>
<!--- end delivering -->


<div><!-- end col --->


	<?php

}
elseif($type == 'client'){


	
	?>

   <div class="col-md-6"><!-- start col --->

		<h3 class="req-title">hired list <i class="fas fa-tasks"></i> </h3>
<!--- start delivering -->
<div class="table-responsive req-table">



	<table class="table">
		<tr> 
			<th>Skilled</th>
			<th>Post</th>
			<th>Price</th>
			<th>Control</th>
		</tr>
<?php


$stmt = $con->prepare("SELECT `request`.* , `request group`.`ID` as 'gID' ,`request group`.`from` , 
`request group`.`to` ,`request group`.`post ID` FROM `request`,`request group` 
WHERE `request group`.`status`='0' AND `request`.`type`='delivering' 
AND `request`.`accept`='1' AND `request group`.`ID`=`request`.`GroupID` 
AND (`request group`.`from`='$ID' OR `request group`.`to`='$ID')");
$stmt->execute();
$req = $stmt->fetchAll();


foreach($req as $r){

	if($r['from'] == $ID){

		$user = $r['to'];

		$stmt = $con->prepare("SELECT * FROM `user` WHERE `ID`='$user'");
        $stmt->execute();
        $user = $stmt->fetch();

	}
	elseif($r['to'] == $ID){

		$user = $r['from'];

		$stmt = $con->prepare("SELECT * FROM `user` WHERE `ID`='$user'");
        $stmt->execute();
        $user = $stmt->fetch();

	}

	$post = $r['post ID'];

	$stmt = $con->prepare("SELECT `title` FROM `post` WHERE `ID`='$post'");
    $stmt->execute();
    $post=   $stmt->fetch();

    ?>

    <tr>
			<td>
				<img src="Admin/uploads/<?php echo $user['type']; ?>/<?php echo $user['image']; ?>" class="rounded-circle" style="width: 45px;">
				 <?php echo $user['first name'].' '.$user['last name']; ?>
			</td>
			<td><?php echo $post['title']; ?></td>
			<td><?php echo $r['price']; ?></td>
			<td>
				<form method="POST" action="">
					<input type="text" name="postID" value="<?php echo $r['post ID']; ?>" hidden>
					<input type="text" name="gID" value="<?php echo $r['gID']; ?>" hidden>
			        <button type="submit" class="btn btn-success" name="delivered">delivered <i class="fas fa-clipboard-check"></i></button>
			   </form>
			</td>
	</tr>




    <?php



}




	?>

		
	</table>

</div>
<!--- end delivering -->


</div><!-- end col --->




<div class="col-md-6"><!-- start col --->




<h3 class="req-title">Requests  <i class="fas fa-clipboard-list"></i></h3>
<!--- start req -->
<div class="table-responsive req-table">



	<table class="table">
		<tr>
			<th>User name</th>
			<th>Post</th>
			<th>control</th>
		</tr>
<?php

// select request that provided by the user

$stmt = $con->prepare("SELECT `request`.*,`request Group`.`ID` as 'gID', `request Group`.`from` ,
`request Group`.`to` , `request Group`.`post ID` 
FROM `request`,`request Group` 
        WHERE `request Group`.`status`='0' AND `accept`='1' AND `type`='acceptance'
		AND `request Group`.`ID` = `request`. `GroupID`
		AND (`request Group`.`from`='$ID' OR `request Group`.`to`='$ID')");
$stmt->execute();
$requests = $stmt->fetchAll();






// ---------------------------------------------


foreach($requests as $req){

	

	$groupID = $req['gID'];


	$stmt = $con->prepare("SELECT `ID` FROM `request` WHERE `GroupID`='$groupID' AND `type`='delivering' ");
    $stmt->execute();
    $count = $stmt->rowCount();
	


if($count == 0){

			
			// get the requester name
	if($type == 'client' && $req['from'] == $ID  ){
		$userID = $req['to'];
	}
	elseif($type == 'client' && $req['to'] == $ID   ){
		$userID = $req['from'];

	}else{
		$userID = $req['from'];
	}

	
	  

	$stmt = $con->prepare("SELECT `ID`,`first name`,`last name`,'image' FROM `user` WHERE `ID`='$userID'");
	$stmt->execute();
	$name = $stmt->fetch();


	// get title of the post
	$postID = $req['post ID'];
	$stmt = $con->prepare("SELECT `title` FROM `post` WHERE `ID`='$postID'");
	$stmt->execute();
	$title = $stmt->fetch();

	?>

<tr>
		<td><?php echo $name['first name'].' '.$name['last name']; ?></td>
		<td><?php echo $title['title']; ?></td>

		<td>

			<?php

$groupID = $req['gID'];
$stmt = $con->prepare("SELECT `accept` FROM `request` WHERE `request`.`type`='delivering' AND `GroupID`='$groupID'");
$stmt->execute();
$check = $stmt->fetch();

$count = $stmt->rowCount();


							
			
			if($count==1  && $check['accept']==0 ){
				?> 
					<span> Wait for the agree <i class="far fa-clock"></i></span>
				<?php

				
			}else{

				if($type == 'client'){

			


				?>
					<input type="text" name="gID" class="gID" value="<?php echo $req['gID']; ?>" hidden>
					<input type="text" name="Requester" class="Requester" value="<?php echo $name['ID']; ?>" hidden>
					<input type="text" name="post ID" class="post_ID" value="<?php echo $req['post ID']; ?>" hidden>
					<button type="button" class="btn btn-primary btn-deliver">deliver <i class="fas fa-check"></i></button>		
	
	
				<?php

				}
				
			
				
			}
					
			?>

		</td>
</tr>



<?php





	  }







	}

	

	?>



	<?php




?>
		
	</table>

</div>
<!--- end req -->


  		</div><!-- end col --->





	<?php
}




?>
  			


  	



  	
  		



  	</div><!-- end row --->
  </div>
  <!-- end container --->


















<!--- start post control -->

<h3 class="post-title">Posts <i class="fas fa-ad"></i></h3>
<!--- start req -->
<div class="table-responsive post-table">

	 <!--- start search ----->

 <div class="search">
     
            <form method="POST">
               
                <label form="search" for="searchPost"><strong>Search for post</strong></label>
                <div class="row">
                    <div class="col-md-8">
                               <input id="search" id="searchPost" type="text" name="search" class="form-control">
                    </div>


                    <div class="col-md-4">


                <select id="search-by" name="search-by" class="form-control">
                     <option value="title">Title</option>
                     <option value="post text">Description</option>
                     <option value="price">Price</option>
                </select>
                        
                    </div>

                </div>
            </form>

</div>

 <!--- end search ----->


	<table class="table">
	<thead>
		<tr>
			<th>Title</th>
			<th>Description</th>
			<th>Price</th>
			<th>Place</th>
			<th>Control</th>
		</tr>
	</thead>
	<tbody id="output">
<?php

foreach($posts as $post){
	// get section name
	$secID = $post['section ID'];
	$stmt = $con->prepare("SELECT `name` FROM `section` WHERE `ID`='$secID'");
    $stmt->execute();
    $SecName = $stmt->fetch();

?> 

	<tr>
		<td><?php echo $post['title']; ?></td>
		<td><?php echo $post['post text'];  ?></td>
		<td><?php echo $post['price'].'$';  ?></td>
		<td><?php echo $SecName['name'];  ?></td>
		<td>
			<?php

			 if($post['approved']=='1'){
				 ?>
				 <a href="post-view.php?id=<?php echo $post['ID']; ?>"><button type="button" class="btn btn-primary">view <i class="far fa-eye"></i></button></a>
			<a href="postEdit.php?id=<?php echo $post['ID']; ?>"><button type="button" class="btn btn-success">Edit <i class="far fa-edit"></i></button></a>

			<form method="POST">
				<input type="text" name="ID" value="<?php echo $post['ID'];  ?>" hidden>
				<button type="submit" name="delete" class="btn btn-danger">Delete <i class="far fa-trash-alt"></i></button>
			</form>		

				 <?php
			 }else{
				 ?>

				 <span style="display:block; color:#28a745;">Not approved yet <i class="far fa-clock"></i></span>

				 

				 <?php
			 }
			
			?>
			

		</td>
	</tr>
	<?php
}



?>

</tbody>	
	</table>

</div>


<!--- end post control -->






<?php

if($type == 'client'){
	// start hired list

	?>


<!--- start post control -->

<h3 class="post-title">Hired list <i class="fas fa-handshake"></i> </h3>
<!--- start req -->
<div class="table-responsive post-table">



	<table class="table">
	<thead>
		<tr>
			<th>Skilled</th>
			<th>Post</th>
			<th>Price</th>
			<th>Status</th>
		</tr>
	</thead>
	<tbody id="output-h">
<?php


$stmt = $con->prepare("SELECT `request group`.`from`,`request group`.`to`,`request group`.`post ID`
                      , `request group`.`status` ,`request`.`price`
FROM `request group`,`request` 
WHERE `request group`.`status`='1' AND (`request group`.`from`='$ID' OR `request group`.`to`='$ID') 
AND `request`.`type`='delivering' AND `request`.`GroupID`=`request group`.`ID` ORDER BY `request`.`ID`  DESC");
$stmt->execute();
$req = $stmt->fetchAll();



if(count($req)>0){

foreach($req as $r){

	$postID = $r['post ID'];
	if($r['from']==$ID){
		$prevent = 0;

		
		$data = $r['to'];

		$stmt = $con->prepare("SELECT `title` FROM `post` WHERE `ID`='$postID'");
		$stmt->execute();
		$post = $stmt->fetch();

		$stmt = $con->prepare("SELECT  `ID`,`first name`,`last name`,`image`,`type` FROM `user` WHERE `ID`='$data'");
        $stmt->execute();
        $data = $stmt->fetch();

	}
	elseif($r['to']==$ID){
		$prevent = 0;


		$data = $r['from'];

		$stmt = $con->prepare("SELECT `title` FROM `post` WHERE `ID`='$postID'");
		$stmt->execute();
		$post= $stmt->fetch();

		$stmt = $con->prepare("SELECT `ID`,`first name`,`last name`,`image`,`type` FROM `user` WHERE `ID`='$data'");
        $stmt->execute();
        $data = $stmt->fetch();

	}else{
		$prevent = 1;
	}



	

if($prevent==0){

	?>

    <tr>
		<td>
			<a href="user-view.php?id=<?php echo $data['ID']; ?>" style="text-decoration:none;">
			<img src="Admin/uploads/<?php echo $data['type']; ?>/<?php echo $data['image']; ?>" style="width:45px;"
			 class="rounded-circle">
			<label style="display:block;"><?php echo $data['first name'].' '.$data['last name']; ?></label>
			</a>
		</td>
		<td><?php echo $post['title']; ?></td>
		<td><?php echo $r['price'].'$';  ?></td>
		<td><?php 
		  if($r['status']=='1'){
			 ?> <label style="display:block; color:#009432;"> Delivered <i class="far fa-check-circle"></i></label>   <?php
		  }
		  else{
			?> <label style="display:block; color:#c0392b;"> Not delivered yet <i class="far fa-clock"></i></label>   <?php
		  }
		
		   ?>
	    </td>
	</tr>




	<?php





}

}

}




?> 




</tbody>	
	</table>

</div>




	<?php
	// end hired list
}
elseif($type == 'skilled'){

	?>

<h3 class="post-title">Working list <i class="fas fa-handshake"></i> </h3>
<!--- start req -->
<div class="table-responsive post-table">


	<table class="table">
	<thead>
		<tr>
			<th>Skilled</th>
			<th>Post</th>
			<th>Price</th>
			<th>Status</th>
		</tr>
	</thead>
	<tbody id="output-h">
<?php


$stmt = $con->prepare("SELECT `request group`.`from`,`request group`.`to`,`request group`.`post ID`
                      , `request group`.`status` ,`request`.`price`
FROM `request group`,`request` 
WHERE `request group`.`status`='1' AND (`request group`.`from`='$ID' OR `request group`.`to`='$ID') 
AND `request`.`type`='delivering' AND `request`.`GroupID`=`request group`.`ID` ORDER BY `request`.`ID`  DESC");
$stmt->execute();
$req = $stmt->fetchAll();


if(count($req)>0){

foreach($req as $r){

	$postID = $r['post ID'];
		

	if($r['from']==$ID){
		$data = $r['to'];

		$prevent = 0;

		


		$stmt = $con->prepare("SELECT `title` FROM `post` WHERE `ID`='$postID'");
		$stmt->execute();
		$post = $stmt->fetch();

		$stmt = $con->prepare("SELECT `ID`,`first name`,`last name`,`image`,`type` FROM `user` WHERE `ID`='$data'");
        $stmt->execute();
        $data = $stmt->fetch();

		

	}
	elseif($r['to']==$ID){
		$prevent = 0;

		$data = $r['from'];

		$stmt = $con->prepare("SELECT `title` FROM `post` WHERE `ID`='$postID'");
		$stmt->execute();
		$post = $stmt->fetch();

		$stmt = $con->prepare("SELECT `ID`,`first name`,`last name`,`image`,`type` FROM `user` WHERE `ID`='$data'");
        $stmt->execute();
        $data = $stmt->fetch();

		

	}else{
		$prevent = 1;
	}



if($prevent==0){
	?>

    <tr>
		<td>
		  <a href="user-view.php?id=<?php echo $data['ID']; ?>" style="text-decoration:none;">
			<img src="Admin/uploads/<?php echo $data['type']; ?>/<?php echo $data['image']; ?>" style="width:45px;"
			 class="rounded-circle">
			<label style="display:block;"><?php echo $data['first name'].' '.$data['last name']; ?></label>
		  </a>
		</td>
		<td><?php echo $post['title'];?></td>
		<td><?php echo $r['price'].'$';  ?></td>
		<td><?php 
		  if($r['status']=='1'){
			 ?> <label style="display:block; color:#28a745;"> Delivered <i class="far fa-check-circle"></i></label>   <?php
		  }
		  else{
			?> <label style="display:block; color:#28a745;"> Not delivered yet <i class="far fa-clock"></i></label>   <?php
		  }
		
		   ?>
	    </td>
	</tr>




	<?php

		}

}

}




?> 




</tbody>	
	</table>

</div>


	<?php

}




?>

</div> <!--- end main --->



<script>

$('.main').css('margin-top',$('.nav').height()*1.3);



</script>


<script src="layout/js/dashboard.js"></script>






