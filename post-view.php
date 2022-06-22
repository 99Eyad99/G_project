<?php

include 'init.php';
session_start();




if(isset($_SESSION) && isset($_SESSION['ID']) && (getUserType($_SESSION['ID'])=='skilled'  || getUserType($_SESSION['ID']))=='client' ){

	$allow = 1;
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
	  <script type="text/javascript" src="layout/js/rating_dark.js"></script>
	   <link rel="stylesheet" type="text/css" href="layout/css/post-view-dark.css">
	<?php
}else{
	include 'includes/templates/bar.php';
	?>
	 <script type="text/javascript" src="layout/js/rating.js"></script>
	 <link rel="stylesheet" type="text/css" href="layout/css/post-view.css">
	<?php

}

	
}else{

	$allow = 0;
	

	?>
	  <link rel="stylesheet" type="text/css" href="layout/css/post-view.css">

	<?php
	include 'includes/templates/upperNav.php';
	include 'includes/templates/lowerNav.php';
 }










// check GET request
if(isset($_GET['id']) && !empty($_GET['id'])){

    $ID =$_GET['id'];
	$postID =$_GET['id'];
    // check if post is exist and approved
	if(checkPost($ID)==1){

		 $stmt = $con->prepare("SELECT * FROM `post` WHERE `ID`='$ID'");
         $stmt->execute();
         $post =  $stmt->fetch();
		 $title = $post['title'];

         // get creator info
         $creator_ID = $post['creator ID'];

         $stmt = $con->prepare("SELECT `image`,`first name`,`last name`,`type` FROM `user` WHERE `ID`=$creator_ID");
         $stmt->execute();

         $creator = $stmt->fetch();




// add commment from proccessing

 if(isset($_POST['submit-com'])){
        
	   // inputs
	   $userID = $user->getID();
	   $comment = filter_var($_POST['comment'] , FILTER_SANITIZE_STRING);
	   $postID =$_GET['id'];

	   echo $comment.' . '.$userID.' . '.$postID;

	    if(!empty($comment)){

			if(commentStatus($userID , $postID)== 0){

				$stmt = $con->prepare("INSERT INTO 
				`comment`(`comment`, `creator ID`, `post ID`) 
				VALUES ('$comment','$userID','$postID')");
                $stmt->execute();
				header('location:post-view.php?id='.$postID.'');
				sendNote("Comment alert" ,"a comment added for your post <br> ".$comment." : <br> <a href=post-view.php?id=".$postID.">".$title."</a>",'0',$creator_ID);


			}else{

				$stmt = $con->prepare("UPDATE `comment` 
				                       SET `comment`='$comment',`creator ID`='$userID',`post ID`='$postID' 
				                       WHERE  `creator ID`='$userID' AND `post ID`='$postID'");
                $stmt->execute();

				sendNote("Comment alert" ,"a comment added for your post <br> ".$comment." : <br> <a href=post-view.php?id=".$postID.">".$title."</a>",'0',$creator_ID);

				header('location:post-view.php?id='.$postID.'');
		
			}

		}


	


}


// -------------------------------------




		// post view start here 
		?>

<div class="main"><!---- start main ---->


         <!---- start container ---->
		<div class="container-fluid">
			 <!---- start row ---->
			<div class="row">

				 <div class="col-md-6 col-lg-8">
				 	<!-- start post view --->


				 			<div class="card" >
			<h1 class="card-title text-center"><?php  echo $post['title'];   ?></h1>

	<div class="creator row">

		<div class="col-9">
			<a href="user-view.php?id=<?php  echo  $creator_ID;   ?>">

			<img class="rounded-circle" style="width:55px;" src="Admin/uploads/<?php  echo $creator['type'];  ?>/<?php  echo $creator['image'];  ?>">
			<strong><?php  echo $creator['first name']; echo ' '; echo $creator['last name'];  ?></strong>

			</a>

		</div>
	</div>

  <img src="Admin/uploads/posts/<?php  echo $post['image'];   ?>" class="card-img-top" alt="..."> 

   <div class="control">


<form method="POST" action=""  id="add-fav">
	
	<input value="<?php echo $post['ID'];?>" id="pID" name="pID"  hidden>
	<button name="add_fav"  type="submit" id="add-fav" class="btn btn-warning " style="float: right; background-color: transparent;
	  border: none;">
  
	  

	  <?php

	  if($allow == 1){

	  
	  if(in_array($post['ID'],$user->fav)){
		 
			?>
			   <i id="save" class="fas fa-bookmark" ></i>
	       <?php
		  
          	 
		  
	  }else{


		  ?>
			  	<i id="not-save" class="far fa-bookmark"></i>

		  <?php

	  }

	}

		
	  
		  ?>


	  </button>

	 
	
  
	  </form>

	 

	  <?php

	  // if user is login 
	  if($allow ==1){

		/* if post not belong the user who view the page the request button will shown
		 if request already sent request button will be hidden and show span " requested "
		  so the user can know that request already sent

		
		
		

		*/



		$proType = getUserType($post['creator ID']);
		$reqType = getUserType($user->getID());

		/* users from same type can not send request to each other

		   skilled to client == true
		   client to skilled == true
		   ------------------------------
		   client to client == false
		   skilled to skilled == false

		*/
		$same =0;
		if($proType == $reqType){

			$same = 1;

		}

		$postID = $post['ID'];
		$ID = $user->getID();

		$stmt = $con->prepare("SELECT `request group`.`status`,`request`.`type` FROM `request`,`request group` 
		WHERE `request group`.`post ID`='$postID' AND `request group`.`from`='$ID' 
		AND `request`.`GroupID`=`request group`.`ID` AND `request group`.`status`='0' AND `request`.`type`='acceptance'");
        $stmt->execute();
		$check = $stmt->fetch();
        $count =  $stmt->rowCount();

	





		if($same == 0 && $count==0){

			?>
			<form method="POST" action="">
  
				<button type="submit" class="btn btn-success" id="request" name="request">Send request</button>

				<?php


if(isset($_POST['request'])){




	
	$requester =$user->getID();
	$requested = $creator_ID;
	$postID = $post['ID'];
	$price = $post['price'];

	$stmt = $con->prepare("SELECT * FROM `request group` WHERE `from`='$requester' AND `to`='$requested' 
	                       AND `post ID`='$postID' AND  `post ID`='0'");
	$stmt->execute();
	$count =  $stmt->rowCount();
	$requestGroup =  $stmt->fetch();



	//$request = new request();
	//$request->create();


	



}








?>

  
		   </form>

		   
  
			<?php



		}
		
	

			
		   }




	  

	 

	  
	 
	 
	 
	 ?>




	    </div>



  <div class="card-body">
      <strong >Description</strong>
    <p class="card-text"><?php  echo $post['post text'];   ?></p>


     <hr>

    <div class="price">
		<label>Starting from : <strong><?php  echo $post['price'];   ?> $</strong></label>		
	</div>

	<?php



   // rating show only the post creeated by skilled which means service post
  if(getUserType($creator_ID)['type'] == 'skilled'){
	
	
	if(clacRating($postID)>4){
		?>

    <hr>
		<label style="font-size: 17px;">Rating</label>
		<span class="fa fa-star" id="1" style="color:gold;"></span>
        <span class="fa fa-star" id="2" style="color:gold;"></span>
        <span class="fa fa-star" id="3" style="color:gold;"></span>
        <span class="fa fa-star" id="4" style="color:gold;"></span>
        <span class="fa fa-star" id="5" style="color:gold;"></span>
		
		
		
		<?php
	}elseif(clacRating($postID)>3){
		?>

		<hr>
			<label style="font-size: 17px;">Rating</label>
			<span class="fa fa-star" id="1" style="color:gold;"></span>
			<span class="fa fa-star" id="2" style="color:gold;"></span>
			<span class="fa fa-star" id="3" style="color:gold;"></span>
			<span class="fa fa-star" id="4" style="color:gold;"></span>
			<span class="fa fa-star" id="5" ></span>
			
			
			
			<?php
	}elseif(clacRating($postID)>2){
		?>

		<hr>
			<label style="font-size: 17px;">Rating</label>
			<span class="fa fa-star" id="1" style="color:gold;"></span>
			<span class="fa fa-star" id="2" style="color:gold;"></span>
			<span class="fa fa-star" id="3" style="color:gold;"></span>
			<span class="fa fa-star" id="4" ></span>
			<span class="fa fa-star" id="5" ></span>
			
			
			
			<?php
	}elseif(clacRating($postID)>1){
		?>

		<hr>
			<label style="font-size: 17px;">Rating</label>
			<span class="fa fa-star" id="1" style="color:gold;"></span>
			<span class="fa fa-star" id="2" style="color:gold;"></span>
			<span class="fa fa-star" id="3" ></span>
			<span class="fa fa-star" id="4" ></span>
			<span class="fa fa-star" id="5" ></span>
			
			
			
			<?php
	}elseif(clacRating($postID)>0){
		?>

		<hr>
			<label style="font-size: 17px;">Rating</label>
			<span class="fa fa-star" id="1" style="color:gold;"></span>
			<span class="fa fa-star" id="2" ></span>
			<span class="fa fa-star" id="3" ></span>
			<span class="fa fa-star" id="4" ></span>
			<span class="fa fa-star" id="5" ></span>
			
			
			
			<?php
	}else{

		?>

		<hr>
			<label style="font-size: 17px;">Rating</label>
			<span class="fa fa-star" id="1" ></span>
			<span class="fa fa-star" id="2" ></span>
			<span class="fa fa-star" id="3" ></span>
			<span class="fa fa-star" id="4" ></span>
			<span class="fa fa-star" id="5" ></span>
			
			
			
			<?php

		

	}


}



		
		
		
		?>

   

   
  </div>
</div>

				 	

                    <!--- end post view --->
				 </div>


				 <div class="col-md-6 col-lg-4">

<?php

$stmt = $con->prepare("SELECT `request group`.`from`,`request group`.`to` FROM `request group`,`request`
WHERE `request group`.`status`='1' AND  `request group`.`post ID`='$postID' AND `request`.`type`='delivering' AND
`request`.`accept`='1' AND `request group`.`ID`=`request`.`GroupID` AND (`request group`.`from`='$ID' OR `request group`.`to`='$ID')");
$stmt->execute();
$check = $stmt->fetch();
$count = $stmt->rowCount();


if($count > 0){


	if($check['from'] != $user->getID()){
			$type = getUserType($check['from']);
			
	}
	elseif($check['to'] != $user->getID()){
			$type = getUserType($check['to']);

	}
	
	if($user->getType() == 'client' && $type['type'] != $user->getType()  
	   &&  $creator_ID !=$user->getID()  && $allow == 1 ){
	

		?>

<div class="comment-rate-view">
			

	<form class="comment-form" method="POST" action="">

		<h3 class="text-center">Add comment <i class="fas fa-comment"></i></h3>

		<div class="rating">
	  		<label>Rating</label> 
			<span class="fa fa-star" id="s1"></span>
			<span class="fa fa-star" id="s2"></span>
			<span class="fa fa-star" id="s3"></span>
			<span class="fa fa-star" id="s4"></span>
			<span class="fa fa-star" id="s5"></span>
		</div>

		<label for="com">Comment</label>
		<textarea  type="text" name="comment" id="com" class="form-control"></textarea>

		<button type="submit" name="submit-com" class="btn btn-primary">Submit</button>
		
	</form>


</div>

            <?php


		}


		

		}


		$stmt = $con->prepare("SELECT  `comment`.*,`user`.`first name`,`user`.`last name`,`user`.`ID` 
								as 'userID' ,`user`.`image`,`user`.`type` as 'userType' 
								FROM `comment`,`user` WHERE  
								`comment`.`post ID`='$postID' AND  `comment`.`creator ID` =`user`.`ID` ");
   $stmt->execute();

  $comments = $stmt->fetchAll();
  $count = $stmt->rowCount();

  $creatorType = getUserType($post['creator ID']);
 
		
if($creatorType['type'] == 'skilled'){

?>

	<div class="comments">
		<h3 class="text-center">Comments <i class="far fa-comment-dots"></i></h3>

<?php


if($count>0){
  foreach($comments as $com){
	  if(empty($com['deleted_at'])){
	  ?>
	  		<div class="comment">

			  <input type="text" class="cID" value="<?php  echo $com['ID'];?>" hidden>
				  <div class="control" style="float:right; margin:10px 10px 10px 0px;">
				</div>
			 			
				<table>
				 	<tr>
				 		<th>
				 			<a href="user-view.php?id=<?php echo $com['userID'];?>"><img src="Admin/uploads/<?php echo $com['userType'];?>/<?php echo  $com['image'];?>"
										  style="width:45px;" class="rounded-circle"> </a>
				 		</th>
				 		<th>
				 		
				 		</th>

				 		</tr>
				 		<tr>
				 			<th><a href="user-view.php?id=<?php echo $com['userID'];?>">
							  <?php echo $com['first name'].' '. $com['last name'];?>
							  </a>
							</th>
							
				 			<th  style="padding-left: 20px;">
							 <?php echo $com['comment'];?>
				 			</th>

				 		</tr>
				</table>
				 			
			</div>
			 		
	  <?php
	  }
  }

}
else{
	
	echo 'No comments yet';

}




?>

</div>
  


	
				 </div>

				
			</div> <!---- end row ---->
			
		</div>  <!---- end container ---->



         
        <?php
     // end post view ----------------------------------------

	     // end check post  
	   }else{

	   }






	}// end check post




	
}



?>


</div><!---- end main ---->




<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>


<script>


$('.main').css('margin-top',$('.nav').height()/2);

// Add rating 

$(document).ready(function(){

// 5 star
$('#s5').click(function(){
	var rate = 5;
	var postID = <?=$postID?>;
	var creatorID = <?=$creator_ID?>;
	

	
	$.ajax({
	method:'POST',
	url:'ajax/rating-add.php',
	data:{

		rate:rate,
		postID:postID,
		creatorID:creatorID,
	}
	,
	success:function(data){
		//location.reload();
		alert(data);
		
	
	}

	})


	 
})
// end 5 star


//  4 star
$('#s4').click(function(){
	var rate = 4;
	var postID = <?=$postID?>;
	var creatorID = <?=$creator_ID?>;
	
	

	$.ajax({
	method:'POST',
	url:'ajax/rating-add.php',
	data:{
		rate:rate,
		postID:postID,
		creatorID:creatorID,
	}
	,
	success:function(data){
		alert(data);
		location.reload();
		
	
	}

	})


	 
})
// end 4 star


//  3 star
$('#s3').click(function(){
	var rate = 3;
	var postID = <?=$postID?>;
	var creatorID = <?=$creator_ID?>;

	
	
	

	$.ajax({
	method:'POST',
	url:'ajax/rating-add.php',
	data:{

		rate:rate,
		postID:postID,
		creatorID:creatorID,
	}
	,
	success:function(data){
		alert(data);
		location.reload();
		
	
	}

	})


	 
})
// end 3 star


//  2 star
$('#s2').click(function(){
	var rate = 2;
	var postID = <?=$postID?>;
	var creatorID = <?=$creator_ID?>;



	$.ajax({
	method:'POST',
	url:'ajax/rating-add.php',
	data:{

		rate:rate,
		postID:postID,
		creatorID:creatorID,

	}
	,
	success:function(data){
		alert(data);
		location.reload();

		
		
	
	}

	})


	 
})
// end 2 star


//  1 star
$('#s1').click(function(){
	var rate = 1;
	var postID = <?=$postID?>;
	var creatorID = <?=$creator_ID?>;
	

	$.ajax({
	method:'POST',
	url:'ajax/rating-add.php',
	data:{

		rate:rate,
		postID:postID,
		creatorID:creatorID,
		
	}
	,
	success:function(data){
		alert(data);
		location.reload();
		
	
	}

	})


	 
})
// end 1 star



})






</script>








<script>


	



$(".control form").submit(function(e){
          e.preventDefault();
});



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

});
// end 



// start 


function sendRequest(){
	var requester = <?=$user->getID()?>;
	var requested = <?=$creator_ID?>;
	var postID = <?=$post['ID']?>;
	var price = <?=$post['price']?>;

	$.ajax({
   		method:'POST',
   		url:'ajax/sendRequest.php',
   		data:{

	  	requester:requester,
	  	requested:requested ,
	  	postID:postID ,
	  	price:price 
	}
	,
		success:function(data){

		$('#request').replaceWith("<span style=' padding:3px; border-radius:2px; background-color: #c0392b;color:white;'>Requested</span>");
	
		}

	})


}


function Check_requestGroup(){

	var requester = <?=$user->getID()?>;
	var requested = <?=$creator_ID?>;
	var postID = <?=$post['ID']?>;
	var price = <?=$post['price']?>;




	$.ajax({
   		method:'POST',
   		url:'ajax/check_requestGroup.php',
   		data:{
			requester:requester,
			requested:requested ,
			postID:postID
		}
		,
		success:function(data){
			sendRequest();

		}

	})


}





$("#request").click(function(){

	Check_requestGroup();
	
});


// end 


</script>


<script>

	var commnetID;

	
	function delete_comment(id){
		var thisID = id;
		var cID = commnetID;

		$.ajax({
   			method:'POST',
   			url:'ajax/deleteComment.php',
   			data:{
				cID:cID,
				}
				,
			success:function(data){	
				if(data == 1){
					$('#'+thisID).parent().parent().hide();		
				}
				
			}

		})
		
		
		
	};



	$('.comment').click(function(){


		var cID = $(this).children('.cID').val();
		var uID = <?=$user->getID()?>;

		const delete_btn = "<button class='btn btn-danger delete-com' style='padding:5px;' onclick='delete_comment()'><i class='fas fa-trash-alt'></i></button>";
		const insert = '<input type="text" class="cID" value="'+cID+'" hidden>'+
						'<button class="btn btn-danger" id="delete_btn" onclick="delete_comment(this.id)"><i class="fas fa-trash-alt"></i></button>';

		const selected = $(this);


		$.ajax({
   			method:'POST',
   			url:'ajax/checkAbility.php',
   			data:{
				cID:cID,
				uID:uID
				}
				,
			success:function(data){	
				if(data == 1){
					$(selected).css('background-color','#f1f2f6');
					$(selected).children('.control').html(insert);
					commnetID = cID;
				}
				
			}

		})



	});


	






</script>









