<?php

session_start();
include 'init.php';
if(isset($_SESSION['ID']) && checkItem('ID' , 'admin' , $_SESSION['ID'])==1){
    $ID = $_SESSION['ID'];
    ob_start();
}
else{
    header('location: login.php');
}

$action = isset($_GET['action']) ? $_GET['action'] :'manage';


?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<link rel="stylesheet" type="text/css" href="layout/css/message.css">
<link rel="stylesheet" type="text/css" href="layout/css/sideBar.css">

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
     <img class="rounded-circle" src="uploads/users/user.png" >
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

if($_GET['action']=='manage'){    

    if(isset($_POST['send'])){

          $users = $_POST['check'];
          $type = $_POST['type'];
          $subject = $_POST['subject'];
          $message = $_POST['message'];

          if(!empty($users) && !empty($message) && !empty($subject) && !empty($type)){

                if($type == 'email'){
                    include '../sender.php';
                      foreach($users as $user){
                        $stmt = $con->prepare("SELECT `email` FROM `user` WHERE `ID`='$user'");
                        $stmt->execute();
                        $email= $stmt->fetch();
                        $email = $email['email'];
                        sendForActivate($email, $subject , $message ,'' );
                      }
                    
                    
                   }

                if($type == 'note'){

                     foreach($users as $user){
                        sendNote($subject , $message ,'1',$user);
                     }
 
                  }

          }

         

          



     }


     $stmt = $con->prepare("SELECT * FROM `user` WHERE `activated`='1'");
     $stmt->execute();
     $users = $stmt->fetchAll();

    ?>

    <div class="container-fluid form">
        <div class="row">

            <div class="col-md-6">

<div class="users">

<div class="search">
     

            <form method="POST">
               
                <label for="search">Search for user <i class="fas fa-search"></i></label>
                <div class="row">
                    <div class="col-md-8">
                               <input id="search" type="text" name="search" class="form-control">
                    </div>


                    <div class="col-md-4">


                <select id="search-by" name="search-by" class="form-control">
                     <option value="ID">ID</option>
                     <option value="type">type</option>
                   </select>
                        
                    </div>

                </div>
            </form>

    

</div>


<form method="POST" action="">

            <div  class="table-responvise scroll">
                <table class="table">
                 <thead>
                    <tr class="heading">
                        <th><input type="checkbox" name="select all" id="select-all"></th>
                        <th>ID</th>
                        <th>user</th>
                        <th>type</th>
                    </tr>
                </thead>

<tbody id="output">
    


<?php

  foreach($users as $user){
    ?>
    
      <tr>
         <td><input type="checkbox" name="check[]" value="<?php echo $user['ID']; ?>"></td>
         <td><?php echo $user['ID']; ?></td>
         <td>
            <img src="uploads/<?php echo $user['type']; ?>/<?php echo $user['image']; ?>" 
                 style="width: 55px;" class="rounded-circle">
            <?php   echo '<br>'.$user['first name'].' '.$user['last name']; ?>      
         </td>
         <td><?php echo $user['type']; ?></td>
       </tr>

    <?php

  }

?>
  </tbody>



                  
            </table>
                </div>

</div>

                
            </div>




             <div class="col-md-6">

                <div class="send-form">

                    <label for="subject">Subject</label>
                    <input type="text" name="subject" class="form-control">



                    <label for="mess">message <i class="far fa-envelope"></i></label>
                    <textarea id="mess" name='message'></textarea>

                    <label for="type">type</label>
                    <select id="type" class="form-control" name="type">
                        <option value="email">Email</option>
                        <option value="note">Notification</option>
                    </select>
                    <button type="submit" name="send" class="btn btn-primary">send <i class="far fa-paper-plane"></i></button>

                    </form>
                </div>
                
            </div>
            

        </div>
    </div>



    
    



    <?php
}


?>







</div>


<script type="text/javascript" src="layout/js/nav.js"></script>
<script type="text/javascript" src="layout/js/loadNoti.js"></script>
<script type="text/javascript" src="layout/js/message.js"></script>


