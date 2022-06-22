<?php

session_start();
include 'init.php';
if(isset($_SESSION['ID']) && checkItem('ID' , 'admin' , $_SESSION['ID'])==1){
    ob_start();
}
else{
    header('location: login.php');
}

$action = isset($_GET['action']) ? $_GET['action'] :'manage';


?>
<link rel="stylesheet" type="text/css" href="layout/css/sideBar.css">
<link rel="stylesheet" type="text/css" href="layout/css/post.css">

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

     $stmt = $con->prepare("SELECT * FROM post ORDER BY ID DESC  ");
     $stmt->execute();
     $posts = $stmt->fetchAll();


?>

 <h1 class="text-center page-title">Manage posts</h1>

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
                     <option value="title">Title</option>
                     <option value="post text"> post text</option>
                     <option value="price">Price</option>
                     <option value="section ID">section</option>
                     <option value="creator ID">creator</option>
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

     if(checkItem('ID','post',$ID)==1){


        $stmt = $con->prepare("UPDATE `post` SET `deleted_at`=now() WHERE `ID`='$ID'");
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


 // start approve 

 if(isset($_POST['approve'])){
    $ID = $_POST['ID'];
    $title = $_POST['title'];
    $creator = $_POST['creator'];

    if(checkItem('ID','post',$ID)==1){

       $stmt = $con->prepare("UPDATE `post` SET `approved`=1 WHERE `ID`='$ID'");
       $stmt->execute();

       if($stmt){

           alert('alet alert-success text-center','approved successfully' , 
           'width:80%; margin:20px 0px 0px 10%; padding:10px; ');

           sendNote('post has been approved',' your post has been approved : '.$title,'1', $creator);




           redirect("The page will refresh after 5 seconds please don't make any action" , 5, 
               '?action=manage');


       }

   }


}


 // end approve



 
 
 
 ?>


<!--- end form proccessing -->





<!--- start container -->
<div class="container post-view">
    <div class="row" id="output">


<?php


foreach($posts as $p){
  if(empty($p['deleted_at'])){

    ?>

    <div class="col-12 col-sm-6 col-md-6 col-lg-4" style="  margin: 10px 0px 10px 0px;">

        <div class="card" style="height: 100%;">
            <img src="uploads/posts/<?php echo $p['image'];?>" class="card-img-top" alt="..." >
            <div class="card-body">
                <h5 class="card-title"><strong><?php echo $p['title'];?></strong></h5>
                <p class="card-text"><?php echo $p['post text'];?></p>
                <strong class="row price"><?php  if(!empty($p['price'])){echo $p['price'].' $';}  ?></strong>
                <strong class="row">ID :<?php echo $p['ID'] ;?></strong>
                <strong class="row">
                    created by :
                <?php $user = getUserData($p['creator ID'], 'user'); echo $user['first name']." ".$user['last name'];  ?>
                </strong>
                <div class="row">
         <?php if($p['approved']==0){
                        ?>
                    <form method="POST" action="">
                    <input name="title" value="<?php echo $p['title']; ?>" hidden >
                    <input name="creator" value="<?php echo $p['creator ID']; ?>" hidden >
                    <input name="ID" value="<?php echo $p['ID']; ?>" hidden >
                    <button type="submit" name="approve" class="btn btn-primary">approve</button>
                </form>
                <?php
                }else{
                    echo '<span>approved</span>';
                }  
        ?>
            <form method="POST" action="" > 
            <input  name="ID" value="<?php echo $p['ID']; ?>" hidden >
            <button  name="delete" onclick="conf()" class="btn btn-danger">delete</button>
            </form>
        </div>
       
        </div>
    </div>
</div>
<?php
  }
}

?>

</div>
</div>


<!--- end container -->
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
<script type="text/javascript" src="layout/js/post.js"></script>
<script type="text/javascript" src="layout/js/loadNoti.js"></script>