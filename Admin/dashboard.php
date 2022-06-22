<?php

session_start();
include 'init.php';
if(isset($_SESSION['ID']) && checkItem('ID' , 'admin' , $_SESSION['ID'])==1){
    
}
else{
    header('location: login.php');
}

?>

    <!--Load the AJAX API for google charts-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<link rel="stylesheet" type="text/css" href="layout/css/dashboard.css">

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


        <h1 class="text-center page-title">Dashboard</h1>

        
 <!  ---- start stat --- >
<div class="container-fluid stat">
            <div class="row">
                
                <div class="col-sm-6 col-md-3">
                    <div class="box" style="background-color:#d63031">
                         <h5>Clients   </h5>
                         <span><?php $clients = checkItem('type' , 'user' , 'client');
                                      echo $clients;?>
                                   
                              
                          </span>
                    </div>    
                </div>

                 <div class="col-sm-6 col-md-3">
                     <div class="box" style="background-color:#2c3e50";>
                         <h5>Skilleds</h5>
                         <span><?php $skilleds= checkItem('type' , 'user' , 'skilled');
                                      echo $skilleds;?> 
                        </span>
                    </div>
                </div>

                 <div class=" col-sm-6 col-md-3">
                    <div class="box" style="background-color:#f39c12";>
                         <h5>Posts</h5>
                         <span>
                        <?php 
                                  $stmt = $con->prepare("SELECT `section ID`, `deleted_at`FROM `post` WHERE `section ID`='4000002' ");
                                  $stmt->execute();
                                  $row = $stmt->fetchAll();

                                  $count = 0;
                                  foreach($row as $r){
                                      if(empty($r['deleted_at'])){
                                        $count +=1;
                                      }
                                     
                                  }

                                  echo $count;
                         ?>
                         </span>
                    </div>
                </div>

                 <div class="col-sm-6 col-md-3">
                     <div class="box" style="background-color:#009432"; >
                         <h5>Requests</h5>
                         <span>
                         <?php 
                             $stmt = $con->prepare("SELECT `request group`.`ID` 
                                                    FROM `request`,`request group` 
                                                    WHERE `request`.`accept`='1' 
                                                    AND `request group`.`ID` = `request`.`GroupID`; ");
                             $stmt->execute();
                             echo($stmt->rowCount());
                         ?>
                         </span>
                    </div>
                </div>


            </div>
        </div>


    <!  ---- end stat --- >




    <!  ---- start client and skilled tables --- >

<div class="container-fluid C_S_table" style="margin-top:20px;">
    <div class="row">

        <div class="col-md-12 col-lg-6">
<?php





?>





    <!--Div that will hold the pie chart-->
    <div id="chart_div" style="width:100%; "></div>


  <div id="chart_bar" style="width: 100%;"></div>
      
 




        </div>

        <div class="col-md-12 col-lg-6">

                        <div class="cs_table">
                <h4 class="text-center">Lastest clients</h4>
            
    <table class="table table-responsive">

<thead>
<tr>
<th scope="col">ID</th>
<th scope="col">First Name</th>
<th scope="col">Image</th>
<th scope="col">Control</th>
</tr>
</thead>

<?php


 $stmt = $con->prepare("SELECT * FROM `user` WHERE `type`='client' AND  `activated`='1' ORDER BY  `ID` DESC LIMIT 3 ");
$stmt->execute();
$row =  $stmt->fetchAll();


// insert data into table


if(count($row)>0){

foreach($row as $i){

?>

<tbody>
    <tr>
        <th scope="row"><?php echo $i['ID'];?></th>
        <td><?php echo $i['first name'];?></td>
        <td><img src="uploads/client/<?php echo $i['image'];?>" style="width: 40px;"></td>
    <div class="control"> 
        <td>
            <a href="client.php?action=Edit&id=<?php echo $i['ID'];?>">
                 <button type="button" class="btn btn-success">Edit <i class="fas fa-user-edit"></i></button>
            </a>

               <a href="<?php echo $i['type'];?>.php?action=details&id=<?php echo $i['ID'];?>">
                    <button type="button" class="btn btn-primary">view <i class="far fa-eye"></i></button>
               </a> 
        </td>
    </div>
</tr>
</tbody>

<?php



}


}




?>


</table>


</div>

<div class="cs_table"  style="margin-top:20px;">
                <h4 class="text-center">Lastest skilled</h4>

    <table class="table table-responsive">
<thead>
<tr>
<th scope="col">ID</th>
<th scope="col">First Name</th>
<th scope="col">Image</th>
<th scope="col">Control</th>
</tr>
</thead>

<?php


// fetch skilleds table data

 $stmt = $con->prepare("SELECT * FROM `user` WHERE `type`='skilled' AND  `activated`='1' ORDER BY  `ID` DESC LIMIT 3 ");
$stmt->execute();
$row =  $stmt->fetchAll();


// insert data into table

if(count($row)>0){
    foreach($row as $i){
?>

<tbody>
    <tr>
        <th scope="row"><?php echo $i['ID'];?></th>
        <td><?php echo $i['first name'];?></td>
        <td><img src="uploads/skilled/<?php echo $i['image'];?>" style="width: 40px;"></td>
    <div class="control"> 
        <td>
           <a href="skilled.php?action=Edit&id=<?php echo $i['ID'];?>">
                 <button type="button" class="btn btn-success">Edit <i class="fas fa-user-edit"></i></button>
           </a>

           
           <a href="<?php echo $i['type'];?>.php?action=details&id=<?php echo $i['ID'];?>">
                    <button type="button" class="btn btn-primary">view <i class="far fa-eye"></i></button>
               </a> 
               
            </button>
        </td>
    </div>
</tr>
</tbody>

<?php


}

}





?>


</table>


</div>



            

        </div>
        
    </div>
</div>





<!  ---- end client and skilled table --- >





  
        
       
</div>



<script type="text/javascript" src="layout/js/nav.js"></script>
<script type="text/javascript" src="layout/js/loadNoti.js"></script>

    <script type="text/javascript">

//start pie chart ------------------------------------------

        var clients =parseInt('<?=$clients?>');
        var skilleds = parseInt('<?=$skilleds?>');





      // Load the Visualization API and the corechart package.
      google.charts.load('current', {'packages':['corechart']});

      // Set a callback to run when the Google Visualization API is loaded.
      google.charts.setOnLoadCallback(drawChart);

      // Callback that creates and populates a data table,
      // instantiates the pie chart, passes in the data and
      // draws it.
      function drawChart() {

        // Create the data table.
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Topping');
        data.addColumn('number', 'Slices');
        data.addRows([
          ['client', clients],
          ['skilled', skilleds],

        ]);

        // Set chart options
        var options = {'title':'Website users types',
                       width_units: '%',
                       'width':'100%',
                       'height':'100%',
                       'backgroundColor':'#F4F4F4', 
                        is3D: true,
                        chartArea:{left:25,top:25,width:'100%',height:'100%'},
                        isHtml: true,
                        fontSize:'15'
                    };



        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }





//end pie chart ------------------------------------------
</script>




<?php
// find client posts count
  $stmt = $con->prepare("SELECT `post`.`ID`,`post`.`deleted_at` FROM `post`,`user` 
                         WHERE (`post`.`deleted_at` IS NULL ) AND `post`.`creator ID`=`user`.`ID` 
                         AND `user`.`type`='client'  AND `section ID`='4000002'");
  $stmt->execute();
  $clientPosts = $stmt->rowCount();


// find skilled posts count

  $stmt = $con->prepare("SELECT `post`.`ID`,`post`.`deleted_at` FROM `post`,`user` 
                         WHERE (`post`.`deleted_at` IS NULL ) 
                         AND `post`.`creator ID`=`user`.`ID` AND `user`.`type`='skilled' AND `section ID`='4000002' ");
  $stmt->execute();
  $skilledPosts = $stmt->rowCount();

?>

<script type="text/javascript">

    var clientPosts = parseInt('<?=$clientPosts?>');
    var skilledPosts = parseInt('<?=$skilledPosts?>')

google.charts.load('current', {packages: ['corechart', 'bar']});
google.charts.setOnLoadCallback(drawBasic);

function drawBasic() {

      var data = google.visualization.arrayToDataTable([
        ['type', 'Posts',],
        ['client', clientPosts],
        ['skilled', skilledPosts]
      ]);

      var options = {
        title: 'Post count for each type',
        backgroundColor:'#F4F4F4',
        fontSize:'15',
        chartArea: {width: '60%'},
        hAxis: {
          title: 'Total Population',
          minValue: 0
        },
        vAxis: {
          title: 'type'
        }
      };

      var chart = new google.visualization.BarChart(document.getElementById('chart_bar'));

      chart.draw(data, options);
    }


    


</script>

<script type="text/javascript">
$(window).resize(function(){
  drawChart();
});
    
</script>


