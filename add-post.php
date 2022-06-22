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
	   <link rel="stylesheet" type="text/css" href="layout/css/add-post-dark.css">
	<?php
}else{
    include 'includes/templates/bar.php';
	?>
	    <link rel="stylesheet" type="text/css" href="layout/css/add-post.css">
	<?php

}

if(isset($_SESSION['ID'])){

    ?>

<div class="main">


    <?php

    // start form proccessing 
    if(isset($_POST['submit'])){
        // erorrs will be noted into the array
        $error = array();
        // --------------------------------------


        // input variables

        $title =filter_var($_POST['name'],FILTER_SANITIZE_STRING);
        $desc =filter_var($_POST['description'],FILTER_SANITIZE_STRING);
        $price =filter_var($_POST['price'],FILTER_SANITIZE_STRING);

        // image 
        
        $imgName = $_FILES['img']['name'];
        $imgSize = $_FILES['img']['size'];
        $img_tmpName= $_FILES['img']['tmp_name'];
        $imgType = $_FILES['img']['type'];

        $allowed = ['image/PNG','image/JPEG','image/JPG','image/png','image/jpeg','image/jpg','image/png'];
        //   -------------------------------


        // ------------------------------------------------------------------
    
            

        // end input variables


        // start check if empty 

        if(empty($title)){
            $error[] = alert('alert alert-danger', 'Please enter a title' , 
            'width:80%; margin:2px 0px 0px 10%; padding:5px;');
        }

        if(empty($desc)){
            $error[] = alert('alert alert-danger', 'Please enter a description' , 
            'width:80%; margin:2px 0px 0px 10%; padding:5px;');
        }

        if(empty($price)){
            $error[] = alert('alert alert-danger', 'Please enter a price' , 
             'width:80%; margin:2px 0px 0px 10%; padding:5px;');
        }
    
        if(!is_numeric($price)){
            $error[] = alert('alert alert-danger', 'Please enter a numberic data' , 
            'width:80%; margin:10px 0px 0px 10%; padding:5px;');

        }

        if(abs($price) != $price){
            $error[] = alert('alert alert-danger', 'Price must be greater than 0' , 
            'width:80%; margin:10px 0px 0px 10%; padding:5px;');
        }
     
        if(in_array($imgType,$allowed)){
          

            if($imgSize==0){
                $error[] = alert('alert alert-danger', 'Please upload an image' , 
                'width:80%; margin:2px 0px 0px 10%; padding:5px;');
            }else{
                // upload the image 
                $tmpName = rand(0,100000000).'_'.$imgName;
                move_uploaded_file($img_tmpName,'admin/uploads/posts/'.$tmpName);
            }

        }
        else{

            $error[] = alert('alert alert-danger', 'These are the allowed image types: PNG , JPG , JPEG' , 
            'width:80%; margin:2px 0px 0px 10%; padding:5px;');

        }

   
       


        // end check if empty


          // start displays errors
          if(count($error)>0){
            foreach($error as $e){
                echo $e;
            }
        }else{
 
            if($user->AddPost($ID,$title,$desc,$price , $tmpName)){
                alert('alert alert-success', 'The post insarted successfully' , 
                'width:80%; margin:20px 0px 0px 10%; padding:5px;');
    
                 sendNote('upload post' ,'You did upload a post please wait to be approved : '.$title ,'0', $user->getID());
                
                redirect("The page will refresh after 5 seconds please don't make any action" , 5, 
                    '?action=manage');
    
            }



        }


        

    
        
        

    }
    
    
    
    
    // end form proccessing
    ?>


<div class="info">

        <div class="panel panel-primary">
            <div class="panel-heading text-center" > <i class="fas fa-ad"></i> </div>
           
            <div class="panel-body">
                <div  class="row roow">


                     


    <div class=" col">
      <form enctype="multipart/form-data" method="POST" action="">

        <div class="input">
                <label for="name">Title</label>
                <input type="text" id="name" name="name" class="form-control live-name" required>
         </div>

         <div class="input">
           <label for="desc">Description</label>
                <input type="text" id="desc" name="description" class="form-control" required>
         </div>

          <div class="input">
           <label for="price">Price</label>
                <input type="text" id="price" name="price" class="form-control" required>
         </div>

         <div class="input">
           <label for="image">image</label>
                <input type="file" id="image" name="img" class="form-control"  onchange="document.getElementById('output').src = window.URL.createObjectURL(this.files[0])" required>
         </div>

         <div class="input">
            <button  class="btn btn-primary" type="submit" name="submit">submit</button>
         </div>

           

                          
    </div>
        

    <div class="card item_box col live">
        

        
        <img id="output" >
        <h3 class="text-center">Title</h3>
        <p>Description</p>
        <hr>
        <span>Starting at</span>

    </div>

    
       
    


    </form>

    </div>

            
        
            
               </div>
            </div>
        </div>

<?php


}



ob_end_flush();
?>

</div>

<script type="text/javascript">

$('.main').css('margin-top',$('.nav').height()/1.8);



$('#name').keyup(function(){
        $('.live h3').text($('#name').val());
})
    
$('#desc').keyup(function(){
        $('.live p').text($('#desc').val());
})
    

$('#price').keyup(function(){
       $('.live span').text('Starting at : '+$('#price').val()+'$');
        
})





</script>






<?php



?>

