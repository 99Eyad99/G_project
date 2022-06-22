<?php

include 'includes/templates/lowerNav.php';
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
	   <link rel="stylesheet" type="text/css" href="layout/css/postEdit_dark.css">
	<?php
    
}else{
       include 'includes/templates/bar.php';
	?>
    
	    <link rel="stylesheet" type="text/css" href="layout/css/postEdit.css">
	<?php

}


// check not empty
if(!empty($_GET['id'])){
    $postID = filter_var($_GET['id'],FILTER_SANITIZE_NUMBER_INT);
    // check post if exist 
    if(checkPost($postID)==1){
    include 'classes/post.php';
    // create an object 
    $post = new post();
    $post->setID($postID);




    // start POST proccessing form 1
    if(isset($_POST['submit'])){
        $title = filter_var($_POST['title'],FILTER_SANITIZE_STRING);
        $desc = filter_var($_POST['desc'],FILTER_SANITIZE_STRING);
        $price = filter_var($_POST['price'],FILTER_SANITIZE_NUMBER_INT);


        // img details

        
        $imgName = $_FILES['img']['name'];
        $imgSize = $_FILES['img']['size'];
        $img_tmpName= $_FILES['img']['tmp_name'];
        $imgType = $_FILES['img']['type'];

        // -----------------------------------------


        // array to collect erros
        $error = array();

        if(empty($title)){
            $error[] = alert('alert alert-danger', 'Please enter a title' , 
            'width:80%; margin:5px 0px 0px 10%; padding:5px;');
        }


        if(empty($desc)){
            $error[] = alert('alert alert-danger', 'Please enter a description' , 
            'width:80%; margin:5px 0px 0px 10%; padding:5px;');
        }


        if(empty($price)){
            $error[] = alert('alert alert-danger', 'Please enter a price' , 
            'width:80%; margin:5px 0px 0px 10%; padding:5px;');
        }


        if($imgSize==0){
            $error[] = alert('alert alert-danger', 'Please enter an image' , 
            'width:80%; margin:5px 0px 0px 10%; padding:5px;');
        }else{
             // upload the image 
            $tmpName = rand(0,100000000).'_'.$imgName;
            move_uploaded_file($img_tmpName,'Admin/uploads/posts/'.$tmpName);
        }

        // display errors alert if exist
        if(count($error)>0){
            foreach($error as $e){
                echo $e;
            }
        }
        //-----------------------------

        if(count($error)==0){
            $post->setTitle($title);
            $post->setPostText($desc);
            $post->setImage($tmpName);
            $post->setPrice($price);

            if($post->saveToDB()){
                echo alert('alert alert-success', 'Edited successfully' , 
            'width:80%; margin:10px 0px 0px 10%; padding:5px;');
            }




        }





    }


    // end POST proccessing form 1


// start proccessing 
    if(isset($_POST['submit2'])){

        $title = filter_var($_POST['title'],FILTER_SANITIZE_STRING);
        // img details

        
        $imgName = $_FILES['img']['name'];
        $imgSize = $_FILES['img']['size'];
        $img_tmpName= $_FILES['img']['tmp_name'];
        $imgType = $_FILES['img']['type'];

        // -----------------------------------------

        
        // array to collect erros
        $error = array();

        if(empty($title)){
            $error[] = alert('alert alert-danger', 'Please enter a title' , 
            'width:80%; margin:5px 0px 0px 10%; padding:5px;');
        }

        if($imgSize==0){
            $error[] = alert('alert alert-danger', 'Please enter an image' , 
            'width:80%; margin:5px 0px 0px 10%; padding:5px;');
        }else{
             // upload the image 
            $tmpName = rand(0,100000000).'_'.$imgName;
            move_uploaded_file($img_tmpName,'Admin/uploads/posts/'.$tmpName);
        }

          // display errors alert if exist
          if(count($error)>0){
            foreach($error as $e){
                echo $e;
            }
        }
        //-----------------------------


        if(count($error)==0){
            $post->setTitle($title);
            $post->setImage($tmpName);

            if($post->saveToDB()){
                echo alert('alert alert-success', 'Edited successfully' , 
            'width:80%; margin:10px 0px 0px 10%; padding:5px;');
            }




        }









    }



    if($post->getSectionID()=='4000002'){

    
    // start Edit form
    ?>

<div class="main">


<form enctype="multipart/form-data" method="POST" action="">
   

   

    <label for="title">Title</label>
    <input id="title" type="text" name="title" class="form-control" value="<?php echo $post->getTitle(); ?>" required>

    <label for="desc">Description</label>
    <textarea id="desc" type="text" name="desc" class="form-control"  ></textarea>

    <div class="row">

        <div class="col-sm-6  col-md-6">
    <label for="price">Price</label>
    <input id="price" type="text" name="price" class="form-control"  required>
    </div>

        <div class="col-sm-6  col-md-6">
    <label for="img">Image</label>
    <input id="img" type="file" name="img" class="form-control"  required>
    </div>

    </div>

    <button type="submit" name="submit" class="btn btn-primary">submit</button>
</form>



</div>

    





    <?php // end Edit form
}else{
     // second form
    ?>

    <div class="main">

<form method="POST" action="">
   

   
    <div class="row">

        <div class="col-sm-6  col-md-6">
     <label for="title">Title</label>
    <input id="title" type="text" name="title" class="form-control" value="<?php echo $post->getTitle(); ?>" required>
    </div>

        <div class="col-sm-6  col-md-6">
    <label for="img">Image</label>
    <input id="img" type="file" name="img" class="form-control" required>
    </div>

    </div>

    <button type="submit" name="submit2" class="btn btn-primary">submit</button>
</form>



</div>


    <?php
    // end second form




}



    }// end check if exist
}
// end check not empty
  




?>

<script>

	$(document).ready(function(){
		$('.main').css('margin-top',$('.nav').height()*2.1);

	})    

</script>
