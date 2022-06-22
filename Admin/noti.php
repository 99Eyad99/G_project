<?php 

session_start();
if(isset($_SESSION['ID'])){
    include 'init.php';
}
else{
    header('location: login.php');
}


// approve proccessing

if(isset($_POST['approve'])){

    $postID = $_POST['postID'];
    $userID = $_POST['userID'];
    $title = $_POST['title'];
    $stmt = $con->prepare("UPDATE `post` SET `approved`='1' WHERE `ID`='$postID'");
    $stmt->execute();

    if($stmt){
        sendNote('post has been approved' ,'Your post has been approved : '.$title ,'1', $userID);

    }


}


// end -------------------


// reject proccessing

if(isset($_POST['reject'])){

    $postID = $_POST['postID'];
    $stmt = $con->prepare("UPDATE `post` SET `deleted_at`=now() WHERE `ID`='$postID'");
    $stmt->execute();


}


// end reject  -------------------


// start hide ----------------
 
 if(isset($_POST['seen'])){
    $reqID = $_POST['reqID'];

    $stmt = $con->prepare("UPDATE `request` SET `status`='0' WHERE `ID`='$reqID'");
    $stmt->execute();
 }



// end -----------------------



?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<link rel="stylesheet" type="text/css" href="layout/css/sideBar.css">
<link rel="stylesheet" type="text/css" href="layout/css/noti.css">






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




        <!---- start container --->

         <h1 class="text-center">Notficiations <i class="fas fa-bell"></i></h1>



        <div class="container-fluid all">         
            <div class="row">

                <div class="col-md-6">

            <div class="posts-box">
                <h3>Posts <i class="fas fa-ad"></i></h3>
                
                <div class="scroll">

<?php
// fetch posts notications

$stmt = $con->prepare("SELECT `post`.`ID` as 'postID',`post`.`title`,`post`.`price`,`post`.`post text`,`post`.`creator ID` ,`post`.`deleted_at` ,`post`.`image` as 'post image', 
                               `user`.`ID`,`user`.`first name`,`user`.`last name`,`user`.`image` 
                                ,`user`.`type`
                      FROM `post`,`user` 
                      WHERE `post`.`approved`='0'AND `user`.`ID` =`post`.`creator ID` ORDER BY  `post`.`ID` DESC");
$stmt->execute();

$data= $stmt->fetchAll();


// end  ----------------------

// display notification start loop
foreach($data as $d){
    if(empty($d['deleted_at'])){

 
    ?>

<div class="note">

<table>
    <tr>
        <th>
            <img class="rounded-circle" src="uploads/<?php echo $d['type'];?>/<?php echo $d['image'];?>" 
                 style="width:45px;">
       </th>
        <th><?php echo $d['title'];?></th>
        <th>
        </th>
    </tr>

    <tr>
        <td><?php echo $d['first name'].' '.$d['last name'];?></td>
        <td style="padding:5px;">Post waiting for approvement</td>
        <td>
            <form method="POST" action=""> 
               <input type="text" id='title' name="title" value="<?php echo $d['title'];?>" hidden> 
               <input type="text" id='userID' name="userID" value="<?php echo $d['ID'];?>" hidden> 
               <input type="text" id='postID' name="postID" value="<?php echo $d['postID'];?>" hidden>
               <button style="padding:2px;" name="approve" type="submit" id="approve" class="btn btn-success">Approve <i class="fas fa-check"></i></button> 
               <button style="padding:2px;" name="reject" onclick="conf()" type="submit" id="approve" class="btn btn-danger">Reject <i class="fas fa-ban"></i></button>       
            </form>
        </td>
    </tr>
</table>


<div class="card" style="width: 18rem;">
      <img src="uploads/posts/<?php echo $d['post image'];?>" class="card-img-top" alt="...">
   <div class="card-body">
      <h5 class="card-title"><?php echo $d['title'];?></h5>
      <p class="card-text"><?php echo $d['post text'];?></p>
      <strong class="price"><?php  if(!empty($d['price'])){echo $d['price'].' $';}  ?></strong>       
  </div>
</div>


</div>



    <?php


    }
}
// end loop
?> 
</div>
                


                </div>
        </div>


        <div class="col-md-6">



            <div class="req-box">
                <h3>Requests <i class="fas fa-clipboard-list"></i></h3>
                
                <div class="scroll">

<?php

$stmt = $con->prepare("SELECT `request`.`ID`,`request`.`type` ,`request`.`status` , 
                       `request group`.`from`,`request group`.`to`  
                      FROM `request`,`request group` WHERE `request group`.`ID` = `request`.`GroupID`");
$stmt->execute();
$data = $stmt->fetchAll();

foreach($data as $d){
    if($d['status']==1){

        $requester = $d['from'];
        $provider = $d['to'];

       $stmt = $con->prepare("SELECT `first name`,`last name`,`image`,`type` FROM `user` WHERE `ID`=' $requester'");
       $stmt->execute();
       $requester = $stmt->fetch();

       $stmt = $con->prepare("SELECT `first name`,`last name`,`image`,`type` FROM `user` WHERE `ID`='$provider'");
       $stmt->execute();
      $provider = $stmt->fetch();


   
    
    
?>

                
        <div class="note">
                <table>
                    <tr>
                        <th>
                            <img src="uploads/<?php echo $requester['type']; ?>/<?php echo $requester['image']; ?>"
                                 style="width: 45px;" class="rounded-circle">
                        </th>
                        <td></td>
                        <th>
                            <img src="uploads/<?php echo $provider['type']; ?>/<?php echo $provider['image']; ?>"
                                 style="width: 45px;" class="rounded-circle">
                        </th>
                         <td></td>
                    </tr>

                     <tr>
                        <th><?php echo $requester['first name'].' '.$requester['last name']; ?></th>
                        <td style="width:150px;"><?php echo $d['type']; ?></td>
                        <th><?php echo $provider['first name'].' '.$provider['last name']; ?></th>
                         <td>
                            <form method="POST" action="">
                                <input type="text" name="reqID" value="<?php echo $d['ID'];?>" hidden>
                                  <button type="submit" name="seen" class="btn btn-primary"> hide <i class="far fa-eye-slash"></i></button>
                             </form>
                         </td>
                    </tr>             
               </table>                             
            </div>
<?php
 }
}


?>

</div>
                    


        </div>



           

            </div>
            </div>

        <!---- end container --->






            

        </div>
        <!---- end main --->



</div>

<script type="text/javascript" src="layout/js/nav.js"></script>
<script type="text/javascript" src="layout/js/noti.js"></script>
<script type="text/javascript" src="layout/js/loadNoti.js"></script>


<script>


function conf(){
  var con = confirm("Are you sure");
  if(con != true){
    event.preventDefault();
    
  }
}

</script>


<script type="text/javascript">
/*
$("form").submit(function(e){
    e.preventDefault();
  });


$("#approve").click(function(){


var post_ID = $(this).parent().children('#postID').val();



$.ajax({
method:'POST',
url:'ajax/approvePost.php',
data:{
  postID:post_ID,
}
,
success:function(data){
    $(this).html('approved <i class="far fa-check-circle"></i>');
    alert('done')
}

})



});
// end 



*/

</script>
