<?php
ob_start();


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
	
	   <link rel="stylesheet" type="text/css" href="layout/css/profile-dark.css">
	<?php
}else{
	include 'includes/templates/bar.php';
	?>
	 <link rel="stylesheet" type="text/css" href="layout/css/profile.css">
	<?php

}


}else{
	header('location:login.php');
}




// handle add post proccessing

  if(isset($_POST['submit-post'])){
      $title = filter_var($_POST['title'],FILTER_SANITIZE_STRING);

        // image 
        
        $imgName = $_FILES['img']['name'];
        $imgSize = $_FILES['img']['size'];
        $img_tmpName= $_FILES['img']['tmp_name'];
        $imgType = $_FILES['img']['type'];
        //   -------------------------------

        // array to collect input errors
        $errors = array();

        if(empty($title)){
            $errors[] = alert('alert alert-danger', 'Please enter a title' , 
            'width:80%; margin:2px 0px 0px 10%; padding:5px;');
        }

        // 0 means there is no image
        if($imgSize==0){
            $error[] = alert('alert alert-danger', 'Please upload an image' , 
            'width:80%; margin:2px 0px 0px 10%; padding:5px;');
        }else{
            // upload the image 
            $tmpName = rand(0,100000000).'_'.$imgName;
            move_uploaded_file($img_tmpName,'admin/uploads/posts/'.$tmpName);
        }

        // display errors is exist
        if(count($errors)>0){
            foreach($errors as $e){
                echo $e;
            }
        }
        
        // which means there is no errors i can insert the post
        if(count($errors)==0){

            if($user->AddPost_portfilo($user->getID(), $title , $tmpName)){

            alert('alert alert-success', 'The post insarted successfully' , 
            'width:80%; margin:20px 0px 0px 10%; padding:5px;');

            sendNote('upload post' ,'you did upload a post please wait to be approved : '.$title ,'0',$user->getID());
            
            //header('location:profile.php');
            
            }



           
            

        }

      


      //
  }







?>

<div class="main"><!--- start main --->

        <div class="box pro">

         <div class="heading row">

            <ul>
                <li><h3 id="btn-pro">Profile <i class="far fa-user"></i></h3></li>
                <?php 
                  // protfilo is only for skilled
                   if($type=='skilled'){
                       ?> 
                          <li><h3  id="btn-porto">Portfolio <i class="fas fa-portrait"></i></h3></li>
                       <?php
                   }
                
                ?>
            </ul>
            
        </div>

         <div  class="body container">

            <div class="row">  

                 <div class="control">

    <ul>
        <li id="edit-btn">Edit information <i class="fas fa-info-circle"></i> </li>
        <li id="password-btn">Change password <i class="fas fa-key"></i></li>
        <li id="image-btn">hange image <i class="fas fa-images"></i></li>
        <li id="theme-btn">Select theme <i class="fas fa-sun"></i> <i class="fas fa-moon"></i></li>
    </ul>

   </div>


 

              
            </div>

            
            <?php 

                  // start edit information proccessing
                  if(isset($_POST['Edit-info'])){
                    $F_name =  filter_var($_POST['F_name'],FILTER_SANITIZE_STRING);
                    $L_name =  filter_var($_POST['L_name'],FILTER_SANITIZE_STRING);
    
    
    
                    $user->setFirstName($F_name);
                    $user->setLastName($L_name);
    
    
                    $done = $user->saveToDB();
    
                    if($done){
    
                       
    
                        alert('alet alert-success text-center','updated successfully' , 
                        'width:80%; margin:20px 0px 20px 10%; padding:10px; ');
                      
    
                    }
                    
    
                }
                 // end edit information proccessing


                          
             // start change passowrd proccessing

             if(isset($_POST['change-password'])){
                 $erorr = [];

                if(!preg_match('/^(?=.*[A-Za-z])/', $_POST['password'])) {
                    $erorr[]= alert('alert alert-danger text-center','Password must conatin atleast one letter' , 
                                    'width:80%; margin:10px 0px 0px 10%; padding:10px; ');
                }
                 
                if(strlen($_POST['password'])<8){
                    $erorr[]= alert('alet alert-danger text-center','lenght of password must equal or greater than 8 ' , 
                    'width:80%; margin:20px 0px 20px 10%; padding:10px; ');

                }

                if(count($erorr)>0){
                    foreach($erorr as $e){
                        echo $e;
                    }
                }else{

                     $password= filter_var(sha1($_POST['password']),FILTER_SANITIZE_STRING);
                     $user->setPassword($password);
                     $done = $user->saveToDB();

                     if($done){

                        redirect("The page will refresh after 5 seconds please don't make any action" , 5, 'profile.php');
                        alert('alet alert-success text-center','updated successfully' , 
                        'width:80%; margin:20px 0px 20px 10%; padding:10px; ');
    
                    }
            

                }
      
            }


             // end change password proccessing



             // start change image proccessing

             if(isset($_POST['change-img'])){

    
                if($_FILES['image']['size'] !=0){

                    $imgName = $_FILES['image']['name'];
                    $imgSize = $_FILES['image']['size'];
                    $img_tmpName= $_FILES['image']['tmp_name'];
                    $imgType = $_FILES['image']['type'];      
                   
                
                    $tmpName = rand(0,100000000).'_'.$imgName;
                    move_uploaded_file($img_tmpName,'Admin/uploads/'.$type.'/'.$tmpName);

                    $user->setImage($tmpName);

                    $done = $user->saveToDB();

                if($done){

                    redirect("The page will refresh after 5 seconds please don't make any action" , 5, 'profile.php');

                    alert('alet alert-success text-center','updated successfully' , 
                    'width:80%; margin:20px 0px 20px 10%; padding:10px; ');
                

                }
                

                }
               
                

            }


             // end change image proccessing


                 
                 



             if(isset($_POST['change-theme'])){

                 $user->setView($_POST['view']);
                 $user->saveToDB();
                 header('location:profile.php');


             }


                      
             ?>

    <!--- start edit form -->

             <div class="form" id="edit-form">

           

        <form enctype="multipart/form-data" method="POST" action="">


            <div class="row">

                <div class="col-md-5">
                      <label for="F_name">First name</label>
                       <input type="text" id="F_name" name="F_name" class="form-control" 
                       value="<?php echo $user->getFirstName(); ?>"  required>
                    
                </div>

                <div class="col-md-5">
                      <label for="L_name">Last name</label>
                      <input type="text" id="L_name" name="L_name" class="form-control" 
                      value="<?php echo $user->getLastName(); ?>" required>
            
                </div>
                
            </div>


                       <div class="row">
                        
                        <div class="col-md-10">
                             <button type="submit" name="Edit-info" class="btn btn-primary">submit</button>
                        </div>
                     
                      </div>
                 
        </form>
        


    </div>

     <!--- end edit form -->


         <!--- start change password form -->

             <div class="form" id="password-form">
            

        <form enctype="multipart/form-data" method="POST" action="">

      



              <div class="row">


                <div class="col-md-10">
                      <label for="passowrd">password</label>
                      <input type="password" id="password" name="password" class="form-control" required>
                </div>
                
            </div>


                       <div class="row">
                        
                        <div class="col-md-10">
                             <button type="submit" name="change-password" class="btn btn-primary">submit</button>
                        </div>
                     
                      </div>
                 
        </form>
        


    </div>

     <!--- end change password form -->



         <!--- start change image form -->

             <div class="form" id="image-form">

             

        <form enctype="multipart/form-data" method="POST" action="">



              <div class="row">


                <div class="col-md-10">
                      <label for="image">image</label>
                      <input type="file" id="image" name="image" class="form-control" required>
                </div>
                
            </div>


                       <div class="row">
                        
                        <div class="col-md-10">
                             <button type="submit" name="change-img" class="btn btn-primary">submit</button>
                        </div>
                     
                      </div>
                 
        </form>
        


    </div>

     <!--- end change image form -->



     
         <!--- start change theme form -->

<div class="form" id="theme-form">

       <form  method="POST" action="">



      <div class="row">


        <div class="col-md-10">
                 <label for="disabledSelect" class="form-label">Select theme</label>
                 <select id="disabledSelect" class="form-control" name='view'>
                     <option value="0">Light</option>
                     <option value="1">Dark</option>
                 </select>
        </div>
        
    </div>


               <div class="row">
                
                <div class="col-md-10">
                     <button type="submit" name="change-theme" class="btn btn-primary">submit</button>
                </div>
             
              </div>
         
</form>



</div>

<!--- end change theme form -->
    <h3 style="margin-left: 10px;">User image</h3>


     <!--- start profile view -->
     <div class="row profile">


                 <div class="col-md-6">
                 
                <img src="Admin/uploads/<?php echo  $type; ?>/<?php echo $user->getImage(); ?>" class="img-thumbnail" style="max-width:80%;">       
            </div>

             <div class="col-md-6">
                <ul>
                    <li><strong>ID :</strong> <?php echo $user->getID();?></li>
                    <li><strong>First Name :</strong> <?php echo $user->getFirstName();?></li>
                    <li><strong>Last Name :</strong> <?php echo $user->getLastName();?></li>
                    <li><strong>Last Name :</strong> <?php echo $user->getEmail();?></li>
                <ul>
                   
            </div>
                


            </div>

        <!--- end profile view -->


    



        
         </div>

  <?php 
   
    // protfilo page can be accessed only by skilleds
    if($type == 'skilled'){
        ?>

        <!--- start porto --->
<div class="porto">



<div class="proto-control">

           <button type="button" class="btn btn-primary" id="addPort">Add post to your portfolio <i class="fas fa-plus"></i></button>
          

        <!--- start form ----->

<div class="form" id="add-post">

           

<form enctype="multipart/form-data" method="POST" action="">


    <div class="row">

        <div class="col-md-5">
              <label for="F_name">Title</label>
               <input type="text" id="title" name="title" class="form-control"  required>
            
        </div>

        <div class="col-md-5">
              <label for="L_name">Image</label>
              <input type="file" id="img" name="img" class="form-control" required>
    
        </div>
        
    </div>


               <div class="row">
                
                <div class="col-md-10">
                     <button type="submit" name="submit-post" class="btn btn-primary">submit</button>
                </div>
             
              </div>
         
</form>



</div>






<!--- end form ----->


</div><!--- end control ----->



 <!--- start container --->
<div class="container-fluid">
    <div class="row"><!--- start row --->

    <?php

    // fetch portfilo posts
    $userID = $user->getID();

    $stmt = $con->prepare("SELECT * FROM `post` WHERE `approved`='1' 
    AND `section ID`='4000010' AND `creator ID`='$userID' AND `deleted_at` IS  NULL ");
    $stmt->execute();
    $posts = $stmt->fetchAll();

    foreach($posts as $p){
        ?>
             <!--- start col --->
    <div class="col-12 col-sm-6 col-md-4" style="margin-top:10px;">

               <!--- start card ---> 
               <div class="card" style="height: 100%;" >
                    <img src="Admin/uploads/posts/<?php echo $p['image']; ?>" class="card-img-top" alt="...">
                    <div class="card-body">
                    <h5 class="card-title"><?php echo $p['title']; ?></h5>
                </div> <!--- end card ---> 

        </div>               

            
                   <!--- end card --->
    </div>
        <!--- end col --->

        <?php
    }
    
    
    
    ?>
        
     


        


    </div> <!--- end row --->
</div>
<!--- end container --->



</div>
<!--- end porto --->




        <?php
    }
  
  
  
  ?>








     </div>










    




    <?php






ob_end_flush();
?>


</div><!--- end main --->



<script type="text/javascript">

$('.main').css('margin-top',$('.nav').height()/1.9);






// edit info button
 $('#edit-form').css('display','none');

 


$('#edit-btn').click(function(){
    if($('#edit-form').is(':visible') == true){
        $('#edit-form').slideUp(1000);


    }
    else{
        $('#edit-form').slideDown(1000);
        $('#password-form').hide();
        $('#image-form').hide();
        $('#theme-form').hide();
       
    }

});

// edit info button


// password button


$('#password-form').css('display','none');


$('#password-btn').click(function(){
    if($('#password-form').is(':visible') == true){
        $('#password-form').slideUp(1000);


    }
    else{
        $('#password-form').slideDown(1000);
        $('#edit-form').hide();
        $('#image-form').hide();
        $('#theme-form').hide();
    }

});

// password button


// image button


$('#image-form').css('display','none');


$('#image-btn').click(function(){
    if($('#image-form').is(':visible') == true){
        $('#image-form').slideUp(1000);
        


    }
    else{
        $('#image-form').slideDown(1000);
        $('#edit-form').hide();
        $('#password-form').hide();
        $('#theme-form').hide();
       
    }

});


// theme form

$('#theme-form').css('display','none');


$('#theme-btn').click(function(){
    if($('#theme-form').is(':visible') == true){
        $('#theme-form').slideUp(1000);
       
    
    }
    else{
        $('#theme-form').slideDown(1000);
        $('#edit-form').hide();
        $('#image-form').hide();
        $('#password-form').hide();
        
    }

});


// tabs 

var view = '<?=$user->getView()?>';

$('.porto').css('display','none');
$(document).ready(function(){
       if(view ==1){
          $('#btn-pro').parent('li').css('background-color','black');
       }else{
           $('#btn-pro').parent('li').css('background-color','white');
            $('#btn-pro').css('color','black');
       }
        
})

$('#btn-porto').click(function(){

    if(view==1){
        $('#btn-pro').parent('li').css('background-color','#002130');
        $(this).parent('li').css('background-color','black');
    }else{
         $('#btn-pro').parent('li').css('background-color','#002130');
          $('#btn-pro').css('color','white');
         $(this).parent('li').css('background-color','white');
          $(this).css('color','black');
    }
    
    $('.box .body').hide();
    $('.porto').css('display','block');

})

$('#btn-pro').click(function(){


    if(view==1){
        $('#btn-porto').parent('li').css('background-color','#002130');
        $(this).parent('li').css('background-color','black');
    }else{
         $('#btn-porto').parent('li').css('background-color','#002130');
          $('#btn-porto').css('color','white');
         $(this).parent('li').css('background-color','white');
          $(this).css('color','black');
    }

    
    $('.box .porto').hide();
    $('.body').show();

})

// end tabs ----------------------


// add post form

$(document).ready(function(){
    $('#add-post').css('display','none');
})

$('#addPort').click(function(){
    $('#add-post').toggle();



})






// end add post form


</script>




