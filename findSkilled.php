<?php
ob_start();

include_once 'init.php';




 
session_start();




if(isset($_SESSION['ID'])){
   

    $ID = $_SESSION['ID'];

// get use type

 $type = getUserType($ID);
 $type = $type['type'];

 // create skilled or client object 
 if($type == 'skilled'){
 	  include 'classes/skilled.php';
       
 	  $user = new skilled();
 	  $user->setID($ID);

 }else{

 	  include 'classes/client.php';
 	  $user = new client();
 	  $user->setID($ID);

 }


if($user->getView()=='1'){
	include 'includes/templates/bar-dark.php';
	?> 
	   <link rel="stylesheet" type="text/css" href="layout/css/findSkilled_dark.css">
	<?php
  
}else{
       include 'includes/templates/bar.php';
	?>
    
	    <link rel="stylesheet" type="text/css" href="layout/css/findSkilled.css">
	<?php
}


}else{
    include 'includes/templates/upperNav.php';
    include 'includes/templates/lowerNav.php';

    ?>
    
        <link rel="stylesheet" type="text/css" href="layout/css/findSkilled.css">
    <?php
}





?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>


<div class="main"><!-- start main -->


<h1 class="text-center">Find skilled worker <i class="fas fa-hard-hat"></i></h1>


	 <!--- start search ----->

 <div class="search">
     
            <form method="POST">
               
                <label form="search" for="search">Search for skilled <i class="fas fa-search"></i></label>
                <div class="row">
                    <div class="col-md-12">
                            <input id="search" id="search" type="text" name="search" class="form-control" placeholder="search by feild">
                    </div>

                </div>
            </form>

</div>

 <!--- end search ----->


 <?php

    $stmt = $con->prepare("SELECT * FROM `user` WHERE `type`='skilled' AND `activated`='1' ");
    $stmt->execute();
    $skilleds = $stmt->fetchAll();



 ?>


 <!--- start container ----->
 <div class="container-fluid">
 	<div class="row" id="output">


 	<?php

 	// start display -----------------------------------------------------

 	foreach($skilleds as $s){
	?>

<div class="col-lg-3 col-md-4 col-sm-6 col-12" style=" margin-top: 10px; margin-bottom: 10px;"> <!--- start col ----->

 <div class="card" >
  <img src="Admin/uploads/<?php echo $s['type']; ?>/<?php echo $s['image']; ?>" class="card-img-top" alt="...">
  <div class="card-body">
    <h5 class="card-title"><?php echo $s['first name'].' '.$s['last name']; ?></h5>
    <span>Feild : <?php echo $s['Feild']; ?></span>
    
    <a href="user-view.php?id=<?php echo $s['ID']; ?>" class="btn btn-primary">Visit profile <i class="far fa-id-badge"></i></a>
  </div>
</div>


	
 </div>
 <!--- end col ----->


	<?php
}





       // end display -------------------------------------------------------
 	?>

 



 		
 		





 	</div>
 </div>

 <!--- end container ----->


</div><!-- end main -->

  <script>


      $(document).ready(function(){
		      $('.main').css('margin-top',$('.nav').height());
              })

  </script>

 <script type="text/javascript" src="layout/js/findSkilled.js"></script>


 