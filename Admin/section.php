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

<link rel="stylesheet" type="text/css" href="layout/css/section.css">
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


?>

 <h1 class="text-center page-title">Manage sections</h1>


 <a href="?action=add"><button type="button" class="btn btn-primary add">Add section</button></a>


 

<div class="search">
     

            <form method="POST">
               
                <label form="search">Search for section</label>
                <div class="row">
                    <div class="col-md-8">
                               <input id="search" type="text" name="search" class="form-control">
                    </div>


                    <div class="col-md-4">


                <select id="search-by" name="search-by" class="form-control">
                     <option value="ID">ID</option>
                     <option value="name">Name</option>
                     <option value="ordering">Ordering</option>
                   </select>
                        
                    </div>

                </div>
            </form>

    

</div>



<?php


// handle delete

/*

INSERT INTO `skilled` (`ID`, `password`, `token`, `first name`, `last name`, `flied`, `description`, `email`, `image`, `register Date`) VALUES ('2000002', '123', 'Jhon', 'Smith', 'JhonSmith', 'Landspaping', '', 'JhonSmith28@gmail.com', '', '2021-07-01');
*/

  if(isset($_POST['delete'])){
    $ID = $_POST['ID'];

    if(checkItem('ID','section',$ID)==1){

        $stmt = $con->prepare("DELETE FROM `section` WHERE `ID`=$ID");
        $stmt->execute();

        if($stmt){

            alert('alet alert-danger text-center','deleted successfully' , 
            'width:80%; margin:20px 0px 0px 10%; padding:10px; ');

            redirect("The page will refresh after 5 seconds please don't make any action" , 5, '?action=manage');


         
        }

    }



  }

        $stmt = $con->prepare("SELECT * FROM `section`");
        $stmt->execute();
        $rows = $stmt->fetchAll();

        ?>

         <!---- start control table --->

        <div class="manage-table" >

<table class="table table-striped table-responsive">
<thead>
    <tr>
      <th scope="col">ID</th>
      <th scope="col">Name</th>
      <th scope="col">Ordering</th>
      <th scope="col">control</th>
    </tr>
  </thead>
  <tbody id="output">
  
  <?php
  
  // fetch data

$stmt = $con->prepare(" SELECT * FROM section");
$stmt->execute();
$rows = $stmt->fetchAll();

foreach($rows as $r){

    ?>


    <tr>
      <th scope="row"><?php echo $r['ID']; ?></th>
      <td><?php echo $r['name']; ?></td>
      <td><?php echo $r['ordering']; ?></td>
      <td>

        <div class="control">
      
      
      <a href="?action=Edit&id=<?php  echo $r['ID']; ?>">
        <button type="button" class="btn btn-success">Edit <i class="fas fa-user-edit"></i>
         </button>
       </a>




 
    <form method="POST" action="">
       <input name="ID" value="<?php echo $r['ID']?>" hidden>
      
    <!---<button id="delete" name="delete" type="submit" class="btn btn-danger" >Delete <i class="fas fa-user-times"></i></button>--->
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


        <!---- end control table --->

        <?php

 

 

 // end manage    
           
}
elseif($action == 'add'){
   // start add page


    if(isset($_POST['submit'])){
        $name = $_POST['name'];
        $order = $_POST['Ordering'];

        $erorr = array();


        if(empty($name)){
            $erorr[] = '<div class="text-center alert alert-danger" style="width:80%; margin:20px 0px 0px 10%" >
        <strong> Name is empty </strong></div>';

        }


        if(empty($order)){
            $erorr[] = '<div class="text-center alert alert-danger" style="width:80%; margin:20px 0px 0px 10%" >
        <strong> ordering is empty </strong></div>';

        }


    


        // display errors alert if exist

        foreach($erorr as $e){
        echo $e;
    }


  
   

    if(count($erorr)==0){


// ordering must be uneiqe the code below ensure this



    $stmt = $con->prepare("SELECT `ordering` FROM `section`");
    $stmt->execute();
    $orders = $stmt->fetchAll();



    $exist = 0;


    foreach($orders as $i){
        if($i['ordering'] == $order){
            $exist = 1;
        }
      
    }

    if($exist == 1){

        echo '<div class="text-center alert alert-danger" style="width:80%; margin:20px 0px 0px 10%" >
        <strong> ordering is alreay exist </strong></div>';
        
    }else{

           $stmt = $con->prepare("INSERT INTO `section`(`name`, `ordering`) 
                                   VALUES ('$name','$order')");
           $stmt->execute();

           if($stmt){


            alert('alet alert-success text-center','inserted successfully' , 
            'width:80%; margin:20px 0px 0px 10%; padding:10px; ');

            redirect("The page will refresh after 5 seconds please don't make any action" , 5, '?action=manage');

           }


    }





    }



  






    }

    ?>

    <div class="form">
    

              <form method="POST" action="">

     



            

             
                      <label for="name">Name</label>
                       <input type="text" id="name" name="name" class="form-control"  required>
    
                      <label for="Ordering">ordering</label>
                      <input type="text" id="Ordering" name="Ordering" class="form-control" required>
            
         
                
         


                       <div class="row">
                        
                        <div class="col-md-10">
                             <button type="submit" name="submit" class="btn btn-primary">submit</button>
                        </div>
                     
                      </div>
     
        </form>    
    </div>








<?php
// end add page
}
elseif($action == 'Edit'){


    $ID = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;


          // check if ID exist in the table 
   if(checkItem('ID','section',$ID)==1){

    // this function can be used here also to fetch data by ID
    $row = getUserData($ID , 'section');

   }

   if(isset($_POST['submit'])){

    $old_order = $_POST['old-ordering'];
    $name = $_POST['name'];
    $order = $_POST['Ordering'];

    

    $erorr = array();

    $erorr = array();


    if(empty($name)){
        $erorr[] = '<div class="text-center alert alert-danger" style="width:80%; margin:20px 0px 0px 10%" >
    <strong> Name is empty </strong></div>';

    }
    if(empty($order)){
        $erorr[] = '<div class="text-center alert alert-danger" style="width:80%; margin:20px 0px 0px 10%" >
    <strong> ordering is empty </strong></div>';

    }
    


    // display errors alert if exist

    foreach($erorr as $e){
    echo $e;
      }

      if(count($erorr)==0){


        // ordering must be uneiqe the code below ensure this
        
        
        
            $stmt = $con->prepare("SELECT `ordering` FROM `section`");
            $stmt->execute();
            $orders = $stmt->fetchAll();
        
        
            $exist = 0;
   
            foreach($orders as $i){
                if($i['ordering'] == $order){
                        $exist = 1;
                }  
            }

          
 
            if($exist == 1 && $order != $old_order){
        
                echo '<div class="text-center alert alert-danger" style="width:80%; margin:20px 0px 0px 10%" >
                <strong> ordering is alreay exist </strong></div>';
                
            }elseif($exist == 1 && $order == $old_order){

                $stmt = $con->prepare("UPDATE `section` SET `name`='$name',`ordering`='$order'
                WHERE ID='$ID'");
                $stmt->execute();
     
                if($stmt){
     
                 alert('alet alert-success text-center','updated successfully' , 
                 'width:80%; margin:20px 0px 0px 10%; padding:10px; ');
     
                 redirect("The page will refresh after 5 seconds please don't make any action" , 5, '?action=manage');
     
                }
     
            }
            elseif($exist == 0 && $order != $old_order){
        
                   $stmt = $con->prepare("UPDATE `section` SET `name`='$name',`ordering`='$order'
                   WHERE ID='$ID'");
                   $stmt->execute();
        
                   if($stmt){
        
                    alert('alet alert-success text-center','updated successfully' , 
                    'width:80%; margin:20px 0px 0px 10%; padding:10px; ');
        
                    redirect("The page will refresh after 5 seconds please don't make any action" , 5, '?action=manage');
        
                   }
        
        
            }
            
            
        
            }
        
        
        

       
   }


   // start form
   ?>

<div class="form">

    <form method="POST" action="">

     
    <input type="text"  hidden value="<?php echo $row['ordering'];?>" name="old-ordering" class="form-control" required>

            <label for="name">Name</label>
             <input type="text" id="name" name="name" value="<?php echo $row['name'];?>" class="form-control"  required>

            <label for="Ordering">ordering</label>
            <input type="text" id="Ordering" value="<?php echo $row['ordering'];?>" name="Ordering" class="form-control" required>
  

             <div class="row">
              
              <div class="col-md-10">
                   <button type="submit" name="submit" class="btn btn-primary">submit</button>
              </div>
           
            </div>

</form>    
</div>





   


   <?php
   // end form

}






?>



 
<script type="text/javascript" src="layout/js/nav.js"></script>
<script type="text/javascript" src="layout/js/section.js"></script>
<script type="text/javascript" src="layout/js/loadNoti.js"></script>




