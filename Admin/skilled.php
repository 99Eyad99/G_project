<?php

session_start();
include 'init.php';
if(isset($_SESSION['ID'])  && checkItem('ID' , 'admin' , $_SESSION['ID'])==1){
    ob_start();
}
else{
    header('location: login.php');
    
}

$action = isset($_GET['action']) ? $_GET['action'] :'manage';

?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<link rel="stylesheet" type="text/css" href="layout/css/sideBar.css">
<link rel="stylesheet" type="text/css" href="layout/css/skilled.css">


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


?>

 <h1 class="text-center page-title">manage skilleds</h1>



<div class="search">
     
            <form method="POST">
               
                <label form="search">Search for skilleds</label>

                <div class="row">
                    <div class="col-md-8">
                               <input id="search" type="text" name="search" class="form-control">
                    </div>


                    <div class="col-md-4">

                <select id="search-by" name="search-by" class="form-control">
                     <option value="ID">ID</option>
                     <option value="first name">Frist name</option>
                     <option value="last name">Last name</option>
                     <option  value="Feild">Feild</option>
                     <option value="description">Description</option>
                     <option value="email">Email</option>
                     <option value="register Date">Register date</option>
                   </select>
                        
                    </div>

                </div>
            </form>

</div>



<?php




  if(isset($_POST['delete'])){
    $ID = $_POST['ID'];

    if(checkItem('ID','user',$ID)==1){

        $stmt = $con->prepare("DELETE FROM `user` WHERE `ID`=$ID");
        $stmt->execute();

        if($stmt){

            alert('alet alert-danger text-center','deleted successfully' , 
            'width:80%; margin:20px 0px 0px 10%; padding:10px; ');

            redirect("The page will refresh after 5 seconds please don't make any action" , 5, '?action=manage');

   
        }

    }



  }

   









?>

<div class="manage-table" >

<table class="table table-striped table-responsive">
<thead>
    <tr>
      <th scope="col">ID</th>
      <th scope="col">First Name</th>
      <th scope="col">Last Name</th>
      <th scope="col">Feild</th>
      <th scope="col">Description</th>
      <th scope="col">Email</th>
      <th scope="col">image</th>
      <th scope="col">Registeration date</th>
      <th scope="col">control</th>
    </tr>
  </thead>
  <tbody id="output">
  
  <?php
  
  // fetch data

$stmt = $con->prepare(" SELECT * FROM user WHERE type='skilled'");
$stmt->execute();
$rows = $stmt->fetchAll();

foreach($rows as $r){

    ?>


    <tr>
      <th scope="row"><?php echo $r['ID']; ?></th>
      <td><?php echo $r['first name']; ?></td>
      <td><?php echo $r['last name']; ?></td>
      <td><?php echo $r['Feild']; ?></td>
      <td><?php echo $r['description']; ?></td>
      <td><?php echo $r['email']; ?></td>
      <td><img style="width:60px;" src="uploads/skilled/<?php echo $r['image']; ?>"></td>
      <td><?php echo $r['register Date']; ?></td>
      
      <td>
        <div class="control">
      
      
      <a href="?action=Edit&id=<?php  echo $r['ID']; ?>">
        <button type="button" class="btn btn-success">Edit <i class="fas fa-user-edit"></i>
         </button>
       </a>

       <a href="?action=details&id=<?php  echo $r['ID']; ?>">
             <button type="button" class="btn btn-primary">Details</button>
       </a>


      

       
    <form method="POST" action="">
       <input name="ID" value="<?php echo $r['ID']?>" hidden>
      
    <button id="delete" onclick="conf()" name="delete" type="submit" class="btn btn-danger" >Delete <i class="fas fa-user-times"></i></button>
    </form>

      </div>
      </td>
      
      
    </tr>





    



    <?php



}
  
  
  
  ?>
 </tbody>
</table>


</div>



        


        


<?php
 // end manage    
           
}
elseif($action =='details'){

    // start details
    $ID = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;
    // fetch data
    $stmt = $con->prepare("SELECT * FROM `user` WHERE `type`='skilled' AND `ID`='$ID'");
    $stmt->execute();
    $row = $stmt->fetch();

    

     ?>

     
<div class="container-fluid">
    <div class="row">


        <div class="col-md-6">


        <div class="view-box">
            <div class="profile">

                <div class="profile-img">

                <img class="img-fluid" src="uploads/<?php echo $row['type']?>/<?php echo $row['image']; ?>">
                </div>

                <ul>
                    <li><strong>ID :</strong><?php echo ' '.$row['ID'];?></li>
                    <li><strong>First Name :</strong><?php echo ' '.$row['first name'];?></li>
                    <li><strong>Last Name :</strong><?php echo ' '.$row['last name'];?></li>
                    <li><strong>Email :</strong><?php echo ' '.$row['email'];?></li>
                      <li><strong>Register Date :</strong><?php echo ' '.$row['register Date'];?></li>
                <ul>

                
            </div>

        </div>

            


        </div>


        <div class="col-md-6">


            <div class="info">  
                <div class="heading">
                    <h3 class="text-center">Activities</h3>
                </div>
                
               <ul>
                    <li><strong>Posts :</strong>
                        <?php 

                        $stmt = $con->prepare("SELECT `creator ID` FROM `post` 
                                               WHERE `creator ID`='$ID' AND `approved`='1' ");
                                  $stmt->execute();
                                  echo ' ';
                                  echo($stmt->rowCount());
                         ?>
                    </li>
                    <li><strong>Request sent:</strong><?php


                        $stmt = $con->prepare("SELECT `from` FROM `request group` WHERE `from`='$ID'");
                                  $stmt->execute();

                            echo " ".$stmt->rowCount();
                    


                     ?></li>
                    <li><strong>Request received :</strong><?php

                        $stmt = $con->prepare("SELECT `to` FROM `request group` WHERE `to`='$ID'");
                                  $stmt->execute();

                             echo " ".$stmt->rowCount();
                        


                     ?></li>
                    <li><strong> comments :</strong>
                        <?php

                         $stmt = $con->prepare("SELECT `creator ID` FROM `comment` 
                                               WHERE `creator ID`='$ID'");
                                  $stmt->execute();
                                  echo ' ';
                                  echo($stmt->rowCount());

                        ?>
                    </li>
                <ul>

          
            </div>
            

            
        </div>
        




    </div>
</div>

 
    <?php
    





// end details
//$_SERVER['REQUEST_METHOD']
}
elseif($action =='Edit'){

    $ID = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;
    

    // check if ID exist in database skilled table 
   if(checkItem('ID','user',$ID)==1){
    


    // get user data by ID
    $row = getUserData($ID , 'user');

    ?>

    <?php

    // deal with submit

    if($_SERVER['REQUEST_METHOD']=='POST'){

    if(isset($_POST['submit'])){

        $ID = $_POST['ID'];
        $Fname = $_POST['F_name'];
        $Lname = $_POST['L_name'];
        $pass = $_POST['password'];
        $email = $_POST['email'];
        $Fleid = $_POST['Fleid'];
        $desc = $_POST['Description'];
      

        $old_pass= $_POST['old-password'];
        $old_desc= $_POST['old-desc'];




    // validation input



    $erorr = array();

    if(empty($_POST['F_name'])){
        $erorr[] = '<div class="text-center alert alert-danger" style="width:80%; margin:20px 0px 0px 10%" >
        <strong> First name is empty </strong></div>';
    }

    if(empty($_POST['L_name'])){
        $erorr[] = '<div class="text-center alert alert-danger" style="width:80%; margin:20px 0px 0px 10%">
        <strong> Last name is empty </strong></div>';
    }


    if(empty($_POST['email'])){
        $erorr[] = '<div class="text-center alert alert-danger" style="width:80%; margin:20px 0px 0px 10%">
        <strong> Email is empty </strong></div>';
    }

    if(empty($_POST['Fleid'])){
        $erorr[] = '<div class="text-center alert alert-danger" style="width:80%; margin:20px 0px 0px 10%">
        <strong> Fleid is empty </strong></div>';
    }
    if(empty($pass)){
        $pass = $old_pass;
    }
    if(empty($desc)){
        $desc = $old_desc;
    }

// display alert if required input are empty 
    foreach($erorr as $e){
        echo $e;
    }

/*




*/

// insert data 
if(count($erorr)==0){

    
// all scenarios 

// 1


// this means there is image
if($_FILES['image']['size'] !=0){

    $imgName = $_FILES['image']['name'];
    $imgSize = $_FILES['image']['size'];
    $img_tmpName= $_FILES['image']['tmp_name'];
    $imgType = $_FILES['image']['type'];

   

   

    

    $tmpName = rand(0,100000000).'_'.$imgName;
    move_uploaded_file($img_tmpName,'uploads/skilled/'.$tmpName);


    $stmt = $con->prepare("UPDATE `user` SET `password`='$pass',`first name`='$Fname',
        `last name`='$Lname',`description`='$desc',`email`=' $email',`image`='$tmpName',
        `Feild`='$Fleid' WHERE `ID`=$ID ");
    $stmt->execute();
    
    
    if($stmt){
    
        alert('alet alert-success text-center','updated successfully' , 
        'width:80%; margin:20px 0px 0px 10%; padding:10px; ');
      
        
        redirect("The page will refresh after 5 seconds please don't make any action" , 5, '?action=manage');
      
    } 

        
    

}
else{

   

   $stmt = $con->prepare("UPDATE `user` SET `password`='$pass', `first name`='$Fname',
        `last name`='$Lname',`description`='$desc',`email`='$email',
        `Feild`='$Fleid' WHERE `ID`=$ID ");
    $stmt->execute();



if($stmt){

    alert('alet alert-success text-center','updated successfully' , 
    'width:80%; margin:20px 0px 0px 10%; padding:10px; ');
    
    redirect("The page will refresh after 5 seconds please don't make any action" , 5, '?action=manage');
}
        


    }
   



}


    


    

    
}
// end if count($erorr)=0

















}

// end form proccessing

  


    

    
    ?>

    <div class="form">

        <form enctype="multipart/form-data" method="POST" action="">

     <input type="text" hidden="" name="ID" value="<?php echo $row['ID'];?>">
     <input type="text" hidden="" name="old-password" value="<?php echo $row['password'];?>">
     <input type="text" hidden="" name="old-desc" value="<?php echo $row['description'];?>">


            <div class="row">

                <div class="col-md-5">
                      <label for="F_name">First name</label>
                       <input type="text" id="F_name" name="F_name" class="form-control" 
                       value="<?php echo $row['first name'];?>" required>
                    
                </div>

                <div class="col-md-5">
                      <label for="L_name">Last name</label>
                      <input type="text" id="L_name" name="L_name" class="form-control"
                      value="<?php echo $row['last name'];?>" required>
            
                </div>
                
            </div>

              <div class="row">

                <div class="col-md-5">
                      <label for="password">Password</label>
                       <input type="password" id="password" name="password"
                        placeholder="Enter just if you want to change" class="form-control">
                    
                </div>

                <div class="col-md-5">
                      <label for="email">Email</label>
                      <input type="email" id="email" name="email" class="form-control"
                      value="<?php echo $row['email'];?>" required>
                </div>
                
            </div>

         
                 <label for="Fleid">Fleid</label>
                      <input type="text" id="Fleid" name="Fleid" class="form-control" 
                      value="<?php echo $row['Feild'];?>" required>
           


           
                <label for="image">image</label>
                      <input type="file" id="image" name="image" class="form-control">

                         <label for="Description">Description</label>
                      <textarea id="Description" class="form-control" name="Description" placeholder="enter just if your want to change"></textarea>

                       <div class="row">
                        
                        <div class="col-md-10">
                             <button type="submit" name="submit" class="btn btn-primary">submit</button>
                        </div>
                     
                      </div>
                 
          

        </form>
        


    </div>



    



    <?php


   
    








   }






// end edit
}
elseif($action == 'delete'){


    $ID = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;
    echo $ID;







}

ob_end_flush();



    
        
        ?>


        


</div>


<script>


function conf(){
  var con = confirm("Are you sure");
  if(con != true){
    event.preventDefault();
    
  }
}

</script>

<script type="text/javascript" src="layout/js/nav.js"></script>
<script type="text/javascript" src="layout/js/skilled.js"></script>
<script type="text/javascript" src="layout/js/loadNoti.js"></script>






