<?php

session_start();
include 'init.php';
if(isset($_SESSION['ID']) && checkItem('ID' , 'admin' , $_SESSION['ID'])==1){
    include 'admin.php';
    ob_start();
}
else{
    header('location: login.php');
}

$action = isset($_GET['action']) ? $_GET['action'] :'manage';


?>

<link rel="stylesheet" type="text/css" href="layout/css/sideBar.css">
<link rel="stylesheet" type="text/css" href="layout/css/profile.css">


<div class="container">
    <div class="nav">
        <ul>
            <li>
                   <span class=""><h2></h2></span>
            </li>
            <!----->
             <li>
                <a href="../main.php">
                    <span class="icon"><i class="fas fa-home"></i></span>
                    <span class="title">website</span>
                </a>
            </li>
            <li>
                <a href="dashboard.php">
                    <span class="icon"><i class="fas fa-desktop"></i></span>
                    <span class="title">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="stats.php">
                     <span class="icon"><i class="far fa-chart-bar"></i></span>
                     <span class="title">Statistics and reports</span>
                </a>
            </li>
             <li>
                <a href="section.php?action=manage">
                    <span class="icon"><i class="fas fa-sitemap"></i></span>
                    <span class="title">Sections</span>
                </a>
            </li>
            <li>
                <a href="client.php?action=manage">
                     <span class="icon"><i class="fas fa-user-tie"></i></span>
                     <span class="title">Clients</span>
                </a>
            </li>
             <li>
                <a href="skilled.php?action=manage">
                     <span class="icon"><i class="fas fa-hard-hat"></i></span>
                     <span class="title">Skilleds</span>
                </a>
            </li>
             <li>
                <a href="post.php?action=manage">
                     <span class="icon"><i class="fas fa-ad"></i></span>
                     <span class="title">Posts</span>
                </a>
            </li>
            <li>
                <a href="comment.php?action=manage">
                    <span class="icon"><i class="fas fa-comments"></i></span>
                    <span class="title">Comments</span>
                </a>
            </li>
             <li>
                <a href="message.php?action=manage">
                    <span class="icon"><i class="fas fa-inbox"></i></span>
                    <span class="title">Messages</span>
                </a>
            </li>
               <li>
                <a href="logout.php">
                    <span class="icon"><i class="fas fa-sign-out-alt"></i></span>
                    <span class="title">Logout</span>
                </a>
            </li>
          
        </ul>
        
    </div>


</div>


 <div class="main">


      <div class="topbar">
    
            <div class="toggle" onclick="toggleMenu()"><i class="fas fa-bars"></i></div>   

            <div class="profile row">

<div class="noti row">
      <label class="notes" style="font-size:14px;"></label>
     <a href="noti.php" style="color:black;"><i class="far fa-bell"></i></a>
</div>
 
 <div class="row admin_img">
     <img class="rounded-circle" src="uploads/users/user.png">
     <i class="fas fa-caret-down"></i>
 </div>

</div>   

            

        </div>


            <!--  below top bar --->

            <div class="profile-drop-down">
               <ul>
                    <li><a href="profile.php">My profile <i class="fas fa-id-badge"></i></a></li>
                    <li><a href="logout.php">logout <i class="fas fa-sign-out-alt"></i></a></li>      
                </ul>
        </div>




           

        <?php 

// start manage
if($action == 'manage'){
    $ID = $_SESSION['ID'];

    $admin = new admin();
    $admin->setID($ID);
   
   



    ?>
   

     <div class="box">
         <div class="heading "><h3>Admin info</h3></div>
         <div  class="body container">

            <div class="row">
        
                <button type="button" id="edit-btn" class="btn btn-success">Edit information</button>
                <button type="button" id="password-btn" class="btn btn-danger">change password</button>
                <button type="button" id="image-btn" class="btn btn-secondary">change image</button>
               
            </div>

            
            <?php 
         // start edit information proccessing
            if(isset($_POST['Edit-info'])){
                $F_name = $_POST['F_name'];
                $L_name = $_POST['L_name'];
                $email = $_POST['email'];

                $admin->setFirstName($F_name);
                $admin->setLastName($L_name);
                $admin->setEmail($email);

                $done = $admin->saveToDB();

                if($done){

                    redirect("The page will refresh after 5 seconds please don't make any action" , 5, 'profile.php');

                    alert('alet alert-success text-center','updated successfully' , 
                    'width:80%; margin:20px 0px 20px 10%; padding:10px; ');
                  

                }
                

            }
             // end edit information proccessing

             


             // start change passowrd proccessing

             if(isset($_POST['change-password'])){
                $password= $_POST['password'];
               
                $admin->setPassword(sha1($password));


                $done = $admin->saveToDB();

                if($done){

                    redirect("The page will refresh after 5 seconds please don't make any action" , 5, 'profile.php');

                    alert('alet alert-success text-center','updated successfully' , 
                    'width:80%; margin:20px 0px 20px 10%; padding:10px; ');
                  

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
                    move_uploaded_file($img_tmpName,'uploads/users/'.$tmpName);

                    $admin->setImage($tmpName);

                    $done = $admin->saveToDB();

                if($done){

                    redirect("The page will refresh after 5 seconds please don't make any action" , 5, 'profile.php');

                    alert('alet alert-success text-center','updated successfully' , 
                    'width:80%; margin:20px 0px 20px 10%; padding:10px; ');
                  

                }

                }
               
                

            }


             // end change image proccessing



             ?>

    <!--- start edit form -->

             <div class="form" id="edit-form">

        <form enctype="multipart/form-data" method="POST" action="">


            <div class="row">

                <div class="col-md-5">
                      <label for="F_name">First name</label>
                       <input type="text" id="F_name" name="F_name" class="form-control"  required>
                    
                </div>

                <div class="col-md-5">
                      <label for="L_name">Last name</label>
                      <input type="text" id="L_name" name="L_name" class="form-control" required>
            
                </div>
                
            </div>

              <div class="row">


                <div class="col-md-10">
                      <label for="email">Email</label>
                      <input type="email" id="email" name="email" class="form-control" required>
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
                      <input type="text" id="password" name="password" class="form-control" required>
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



            <div class="row">

                 <div class="col-md-6">
                <img src="uploads/users/<?php echo $admin->getImage(); ?>" style="max-width:80%;">       
            </div>

             <div class="col-md-6">
                <ul>
                    <li><strong>ID :</strong> <?php echo $admin->getID();?></li>
                    <li><strong>First Name :</strong> <?php echo $admin->getFirstName();?></li>
                    <li><strong>Last Name :</strong> <?php echo $admin->getLastName();?></li>
                    <li><strong>Last Name :</strong> <?php echo $admin->getEmail();?></li>
                <ul>
                   
            </div>
                


            </div>


        
         </div>
     </div>

    




    <?php


}


?>

<script type="text/javascript" src="layout/js/nav.js"></script>
<script type="text/javascript" src="layout/js/profile.js"></script>
<script type="text/javascript" src="layout/js/loadNoti.js"></script>



