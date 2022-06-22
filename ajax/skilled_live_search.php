<?php

include '../connect.php';




$search = trim($_POST['search'],'\'"');


if(strlen($search)==0 || empty($search)){

  $stmt = $con->prepare("SELECT * FROM  `user` WHERE `Feild`LIKE '%$search%' AND `type`='skilled'");
$stmt->execute();

$rows =  $stmt->fetchAll();

foreach($rows as $s){

?>

<div class="col-lg-3 col-md-4 col-sm-6 col-12"> <!--- start col ----->

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

}
elseif(strlen($search)>=1){


$stmt = $con->prepare("SELECT * FROM  `user` WHERE `Feild`LIKE '%$search%' AND `type`='skilled' ");
$stmt->execute();

$rows =  $stmt->fetchAll();

foreach($rows as $s){

?>

<div class="col-lg-3 col-md-4 col-sm-6 col-12"> <!--- start col ----->

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
  
}







?>


