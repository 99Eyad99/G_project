<?php

session_start();
include 'init.php';
if(isset($_SESSION['ID'])  && checkItem('ID' , 'admin' , $_SESSION['ID'])==1){
   
    ob_start();
}
else{
    header('location: login.php');
}



// ---------------------------- users report ----------------------------------

$stmt = $con->prepare("SELECT DISTINCT `user`.`ID` , `user`.`first name` , `user`.`last name` , `user`.`type` FROM `user`,`request group` WHERE `user`.`activated`='1' AND (`request group`.`from`=`user`.`ID` OR `request group`.`to` =`user`.`ID`)");
$stmt->execute();
$active_users = $stmt->fetchAll();


$stmt = $con->prepare("SELECT DISTINCT `user`.`ID` , `user`.`first name` , `user`.`last name` ,`user`.`type`  FROM `user`,`post` WHERE `user`.`ID`=`post`.`creator ID` AND `post`.`section ID` = '4000002'  AND `approved`='1' ORDER BY `user`.`ID` ASC");
$stmt->execute();
$post_count_per_user = $stmt->fetchAll();

// ------------------------ sales report -------------------------------------------------------


$stmt = $con->prepare("SELECT DISTINCT `post`.`ID`,`post`.`title`,`post`.`creator ID`,`user`.`Feild` FROM `user`,`post` ,`request group` WHERE `post`.`section ID`='4000002' AND `post`.`approved`='1' AND `user`.`type`='skilled' AND `post`.`creator ID`=`user`.`ID` AND `request group`.`post ID`=`post`.`ID`");
$stmt->execute();
$most_requested_service = $stmt->fetchAll();

$date =  date("Y-m-d H:i:s");




?>

<link rel="stylesheet" type="text/css" href="layout/css/stats.css">
<link rel="stylesheet" type="text/css" href="layout/css/sideBar.css">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.4/jspdf.min.js"></script>
<script src="plugin/html-table-to-excel-jquery/js/tableHTMLExport.js"></script>



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



        <div class="reports container">
            <div class="row">

                <div class="col-md-12">
                    <div class="reports-container">
                        <div class="report">
                            <span><i class="fas fa-file"></i></span>
                            Sales report
                        </div>
                        <button class="download-btn pdf btn print1">PDF <i class="fas fa-file-pdf"></i></button>
                        <button class="download-btn excel btn excel_export_1">Csv <i class="fas fa-file-csv"></i></button>

                      <div id="print1">
                      <h4 class="table-title" hidden>Date: <?php echo $date; ?></h4>

                        <div class="table-responsive ">
                              <h4 class="table-title">Number of requests for each service</h4>
                              
                              <table class="table" id="excel_export_table1">
                                <thead>
                                    <tr>
                                        <th scope="col" class='acciones'>ID</th>
                                        <th scope="col" class="acciones">Title</th>
                                        <th scope="col" class="acciones">Created by</th>
                                        <th scope="col" class="acciones">Field</th>
                                        <th scope="col" class="acciones">Reqeusted times</th>
                                    </tr>
                                </thead>
                        <?php

                        foreach($most_requested_service as $service){

                            $ID = $service['creator ID']; 
                            $createdBy = getUserData($ID ,'user');
                            $serviceID = $service['ID'];

                            
                            $stmt = $con->prepare("SELECT `ID` FROM `request group` WHERE `post ID`='$serviceID' ");
                            $stmt->execute();
                            $count = $stmt->rowCount();

                     
                            
                            ?>
                                <tbody>
                                    <tr id="ultimo">
                                        <th scope="row" class="acciones"><?php echo $service['ID']; ?></th>
                                        <td class="acciones"><?php echo $service['title']; ?></td>
                                        <td class="acciones"><?php echo $createdBy['ID']; ?></td>
                                        <td class="acciones"><?php echo $createdBy['Feild']; ?></td>
                                        <td class="acciones"><?php echo $count; ?></td>
                                    </tr>
                                <tbody>
                            
                            <?php
                        }  
                        
                        ?>

                              </table>
                          </div>
                        </div>



                    </div>  
                </div>

                <div class="col-md-12">
                    <div class="reports-container">
                        <div class="report">
                            <span><i class="fas fa-file"></i></span>
                            Users report
                        </div> 
                       <button class="download-btn pdf btn print2">PDF <i class="fas fa-file-pdf"></i></button>
                       <button class="download-btn excel btn excel_export_2">Csv <i class="fas fa-file-csv"></i></button>



                       <div class="users" id="print2" >

                       <h4 class="table-title" hidden>Date: <?php echo $date; ?></h4>

                          
                          <div class="table-responsive">
                              <h4 class="table-title">Active users</h4>
                              <table class="table" id="excel_export_table2">

                                <thead>
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">First name</th>
                                        <th scope="col">Last name</th>
                                        <th scope="col">Type</th>
                                        <th scope="col">Status</th>
                                    </tr>
                                </thead>
                        <?php

                        foreach($active_users as $active){
                            ?>
                                <tbody>
                                    <tr>
                                        <th scope="row"><?php echo $active['ID']; ?></th>
                                        <td><?php echo $active['first name']; ?></td>
                                        <td><?php echo $active['last name']; ?></td>
                                        <td><?php echo $active['type']; ?></td>
                                        <td>Active</td>
                                    </tr>
                                <tbody>
                            
                            <?php
                        }  
                        
                        ?>

                              </table>
                          </div>


                          <div class="table-responsive">
                              <h4 class="table-title">Number of posts of each user</h4>
                              <table class="table" id="excel_export_table3">
                                <thead>
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">First name</th>
                                        <th scope="col">Last name</th>
                                        <th scope="col">Type</th>
                                        <th scope="col">Posts</th>
                                    </tr>
                                </thead>
                        <?php

                        foreach($post_count_per_user as $user_post){

                            $ID = $user_post['ID'];

                            $stmt = $con->prepare("SELECT `ID` FROM `post` WHERE `creator ID`='$ID' AND `section ID`='4000002' AND `approved`='1'  ");
                            $stmt->execute();
                            $count = $stmt->rowCount();


                      
                            ?>
                                <tbody>
                                    <tr>
                                        <th scope="row"><?php echo $user_post['ID']; ?></th>
                                        <td><?php echo $user_post['first name']; ?></td>
                                        <td><?php echo $user_post['last name']; ?></td>
                                        <td><?php echo $user_post['type']; ?></td>
                                        <td><?php echo $count; ?></td>
                                    </tr>
                                <tbody>
                            
                            <?php
                        }
                        
                        
                        
                        
                        ?>

                              </table>
                          </div>


                       


                       </div>


                       
                </div>



            </div>
        </div>



 
        



</div>


<script>

$(document).ready(function(){

   
    var specialElementHandlers = {
        '#editor': function (element, renderer) {
        return true;
        }
    };

    $('.print1').click(function () {

        var doc = new jsPDF('portrait', 'pt', 'a4');
     
        doc.fromHTML($("#print1").html(), 15, 15, {
            'width': 400 ,
            'tableWidth': 'auto',
            'elementHandlers': specialElementHandlers
        });


        doc.save('sales_report.pdf');

    });


    
    $('.print2').click(function () {

        console.log('clicked');

        var doc = new jsPDF('portrait', 'pt', 'a4');

        doc.fromHTML($("#print2").html(), 15, 15, {
            'width': 400 ,
            'tableWidth': 'auto',
            'elementHandlers': specialElementHandlers
        });


        doc.save('users_report.pdf');

});

  // csv -------------------------------------------------------------
  var d = new Date();
  var strDate = d.getFullYear() + "/" + (d.getMonth()+1) + "/" + d.getDate();
 

    $(".excel_export_1").click(function(){  
        console.log(strDate);
        $("#excel_export_table1").tableHTMLExport({
            type:'csv',
            filename:'salses_'+strDate+'.csv',
        });
    });

    $(".excel_export_2").click(function(){  
        $("#excel_export_table2").tableHTMLExport({
            type:'csv',
            filename:'usersStatus1_'+strDate+'.csv',
        });

        $("#excel_export_table3").tableHTMLExport({
            type:'csv',
            filename:'usersStatus2_'+strDate+'.csv',
        });
        
    });

    


})




</script>


<script type="text/javascript" src="layout/js/nav.js"></script>
<script type="text/javascript" src="layout/js/loadNoti.js"></script>

