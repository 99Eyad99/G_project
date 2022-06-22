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

<link rel="stylesheet" type="text/css" href="layout/css/comment.css">
<link rel="stylesheet" type="text/css" href="layout/css/sideBar.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>


<!--- start nav -->
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

     $stmt = $con->prepare("SELECT * FROM comment ");
    $stmt->execute();
    $comment= $stmt->fetchAll();


?>

 <h1 class="text-center page-title">Manage comments</h1>

 <!--- start search ----->

 <div class="search">
     
            <form method="POST">
               
                <label form="search">Search for post</label>
                <div class="row">
                    <div class="col-md-8">
                               <input id="search" type="text" name="search" class="form-control">
                    </div>


                    <div class="col-md-4">


                <select id="search-by" name="search-by" class="form-control">
                     <option value="ID">ID</option>
                     <option value="comment">comment</option>
                     <option value="creator">Add by</option>
                     <option value="post ID">post ID</option>
                   </select>
                        
                    </div>

                </div>
            </form>

</div>

 <!--- end search ----->



 <!--- start form proccessing ----->

 <?php

// start delete
 if(isset($_POST['delete'])){
     $ID = $_POST['ID'];

     if(checkItem('ID','comment',$ID)==1){

        $stmt = $con->prepare("UPDATE `comment` SET `deleted_at`=now() WHERE `ID`='$ID'");
        $stmt->execute();

        if($stmt){

            alert('alet alert-success text-center','deleted successfully' , 
            'width:80%; margin:20px 0px 0px 10%; padding:10px; ');

            redirect("The page will refresh after 5 seconds please don't make any action" , 5, 
                '?action=manage');


         
        }

    }



 }

 // end delete



 
 ?>


<!--- end form proccessing -->



<!--- start table -->

<div class="manage-table" >

<table class="table table-striped table-responsive">
<thead>
    <tr>
      <th scope="col">comment ID</th>
      <th scope="col">Added by</th>
      <th scope="col">comment</th>
      <th scope="col">post</th>
      <th scope="col">control</th>
    </tr>
  </thead>
  <tbody id="output">
  
  <?php
  
  // fetch data

$stmt = $con->prepare(" SELECT * FROM comment ");
$stmt->execute();
$rows = $stmt->fetchAll();

foreach($rows as $r){
    if(empty($r['deleted_at'])){

    ?>


    <tr>
      <th scope="row"><?php echo $r['ID']; ?></th>
      <td><?php 
      $id = $r['creator ID'];
      $added_by = getUserData($id , 'user');
      
      
      if($added_by['type'] == 'client'){
          
        ?>  
            <img style="width:60px;" class="rounded-circle" src="uploads/client/<?php echo $added_by['image']; ?>">
            <h5><?php echo $added_by['first name']." ".$added_by['last name']; ?></h5>
     <?php 
          
      }else{
         
        ?>  
            <img style="width:60px;"  class="rounded-circle"  src="uploads/skilled/<?php echo $added_by['image']; ?>">
            <h5><?php echo $added_by['first name']." ".$added_by['last name']; ?></h5>
     <?php 

      }
    
      ?>
    </td>
      <td><?php echo $r['comment']; ?></td>
      <td><?php 
      $id = $r['post ID'];
      $post = getUserData($id , 'post')
      ?>
        <?php echo $post['title'];?>
      <?php 
      ?></td>
      <td>

        <div class="control">
      


    <form method="POST" action="">
       <input name="ID" value="<?php echo $r['ID']?>" hidden>
      
    <button id="delete" onclick="conf()" name="delete" type="submit" class="btn btn-danger" >Delete <i class="fas fa-user-times"></i></button>
    </form>

      </div>
      </td>
      
      
    </tr>



    <?php


    }
}
  
  
  
  ?>
 </tbody>
</table>


</div>
<!--- end table -->




<?php

// end manage
} 


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
<script type="text/javascript" src="layout/js/comment.js"></script>
<script type="text/javascript" src="layout/js/loadNoti.js"></script>




