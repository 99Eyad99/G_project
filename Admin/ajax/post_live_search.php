<?php



include '../connect.php';



$search = trim($_POST['search'],'\'"');
$search_by = $_POST['by'];



if(strlen($search)==0 || empty($search)){


// start creator
if($search_by == 'creator ID'){
// find ID by name
$stmt = $con->prepare("SELECT `ID` FROM `user` WHERE `first name` LIKE '%$search%' OR `last name` LIKE '%$search%'");
$stmt->execute();
$ID =  $stmt->fetchAll();

foreach($ID as $i){
  $IDN = $i['ID'];

$stmt = $con->prepare("SELECT * FROM  `post` WHERE `$search_by`LIKE '%$$IDN%' ");
$stmt->execute();
$post =  $stmt->fetchAll();

    foreach($post as $p){

      ?>

<div class="col-md-4">

<div class="card">
  <img src="uploads/posts/<?php echo $p['image'];?>" class="card-img-top" alt="...">
  <div class="card-body">
    <h5 class="card-title"><strong><?php echo $p['title'];?></strong></h5>
    <p class="card-text"><?php echo $p['post text'];?></p>
    <strong class="row price"><?php echo $p['price']."$"; ;?></strong>
    <strong class="row">ID :<?php echo $p['ID'] ;?></strong>
    <strong class="row">
    created by :
    <?php 

$ID  = $p['creator ID'];

$stmt = $con->prepare("SELECT `first name`, `last name` FROM `user` WHERE `ID`=$ID");
$stmt->execute();
$user =  $stmt->fetch();



    
    echo $user['first name']." ".$user['last name'];  ?>
    </strong>
    <div class="row">

    <?php if($p['approved']==0){

        ?>
        <form method="POST" action="">
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
    <button  name="delete" class="btn btn-danger">delete</button>
    </form>


    </div>
   
      
  </div>
</div>

</div>




      <?php

  }
     }

}
// end creator 


// start section



if($search_by == 'section ID'){
  // find ID by name
  $stmt = $con->prepare("SELECT `ID` FROM `section` WHERE `name` LIKE '%$search%' ");
  $stmt->execute();
  $ID =  $stmt->fetchAll();
  
  foreach($ID as $i){
    $IDN = $i['ID'];
  
  $stmt = $con->prepare("SELECT * FROM  `post` WHERE `$search_by`LIKE '%$$IDN%' ");
  $stmt->execute();
  $post =  $stmt->fetchAll();
  
      foreach($post as $p){
  
        ?>
  
  <div class="col-md-4">
  
  <div class="card">
    <img src="uploads/posts/<?php echo $p['image'];?>" class="card-img-top" alt="...">
    <div class="card-body">
      <h5 class="card-title"><strong><?php echo $p['title'];?></strong></h5>
      <p class="card-text"><?php echo $p['post text'];?></p>
      <strong class="row price"><?php echo $p['price']."$"; ;?></strong>
      <strong class="row">ID :<?php echo $p['ID'] ;?></strong>
      <strong class="row">
      created by :
      <?php 
  
  $ID  = $p['creator ID'];
  
  $stmt = $con->prepare("SELECT `first name`, `last name` FROM `user` WHERE `ID`=$ID");
  $stmt->execute();
  $user =  $stmt->fetch();
  
  
  
      
      echo $user['first name']." ".$user['last name'];  ?>
      </strong>
      <div class="row">
  
      <?php if($p['approved']==0){
  
          ?>
          <form method="POST" action="">
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
      <button  name="delete" class="btn btn-danger">delete</button>
      </form>
  
  
      </div>
     
        
    </div>
  </div>
  
  </div>
  
  
  
  
        <?php
  
    }
       }
  
  }


  // end section

$stmt = $con->prepare("SELECT * FROM  `post` WHERE `$search_by`LIKE '%$search%' ");
$stmt->execute();

$post =  $stmt->fetchAll();



foreach($post as $p){

?>

<div class="col-md-4">

<div class="card">
  <img src="uploads/posts/<?php echo $p['image'];?>" class="card-img-top" alt="...">
  <div class="card-body">
    <h5 class="card-title"><strong><?php echo $p['title'];?></strong></h5>
    <p class="card-text"><?php echo $p['post text'];?></p>
    <strong class="row price"><?php echo $p['price']."$"; ;?></strong>
    <strong class="row">ID :<?php echo $p['ID'] ;?></strong>
    <strong class="row">
    created by :
    <?php 

$ID  = $p['creator ID'];

$stmt = $con->prepare("SELECT `first name`, `last name` FROM `user` WHERE `ID`=$ID");
$stmt->execute();
$user =  $stmt->fetch();



    
    echo $user['first name']." ".$user['last name'];  ?>
    </strong>
    <div class="row">

    <?php if($p['approved']==0){

        ?>
        <form method="POST" action="">
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
    <button  name="delete" class="btn btn-danger">delete</button>
    </form>


    </div>
   
      
  </div>
</div>

</div>


<?php

}

}
elseif(strlen($search)>=1){

 // start creator


  if($search_by == 'creator ID'){

    // find ID by name
    $stmt = $con->prepare("SELECT `ID` FROM `user` WHERE `first name` LIKE '%$search%' OR `last name` LIKE '%$search%'");
    $stmt->execute();
    $ID =  $stmt->fetchAll();
    
    foreach($ID as $i){
      $IDN = $i['ID'];
    
    $stmt = $con->prepare("SELECT * FROM  `post` WHERE `$search_by`LIKE '%$IDN%' ");
    $stmt->execute();
    $post =  $stmt->fetchAll();
    
        foreach($post as $p){
    
          ?>
    
    <div class="col-md-4">
    
    <div class="card">
      <img src="uploads/posts/<?php echo $p['image'];?>" class="card-img-top" alt="...">
      <div class="card-body">
        <h5 class="card-title"><strong><?php echo $p['title'];?></strong></h5>
        <p class="card-text"><?php echo $p['post text'];?></p>
        <strong class="row price"><?php echo $p['price']."$"; ;?></strong>
        <strong class="row">ID :<?php echo $p['ID'] ;?></strong>
        <strong class="row">
        created by :
        <?php 
    
    $ID  = $p['creator ID'];
    
    $stmt = $con->prepare("SELECT `first name`, `last name` FROM `user` WHERE `ID`=$ID");
    $stmt->execute();
    $user =  $stmt->fetch();
    
          
        echo $user['first name']." ".$user['last name'];  ?>
        </strong>
        <div class="row">
    
        <?php if($p['approved']==0){
    
            ?>
            <form method="POST" action="">
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
        <button  name="delete" class="btn btn-danger">delete</button>
        </form>
    
    
        </div>
       
          
      </div>
    </div>
    
    </div>
    
    
          <?php
    
      }
         }
    
    }

    // end creator


    // start section



if($search_by == 'section ID'){
  // find ID by name
  $stmt = $con->prepare("SELECT `ID` FROM `section` WHERE `name` LIKE '%$search%' ");
  $stmt->execute();
  $ID =  $stmt->fetchAll();
  
  foreach($ID as $i){
    $IDN = $i['ID'];
  
  $stmt = $con->prepare("SELECT * FROM  `post` WHERE `$search_by`LIKE '%$IDN%' ");
  $stmt->execute();
  $post =  $stmt->fetchAll();
  
      foreach($post as $p){
  
        ?>
  
  <div class="col-md-4">
  
  <div class="card">
    <img src="uploads/posts/<?php echo $p['image'];?>" class="card-img-top" alt="...">
    <div class="card-body">
      <h5 class="card-title"><strong><?php echo $p['title'];?></strong></h5>
      <p class="card-text"><?php echo $p['post text'];?></p>
      <strong class="row price"><?php echo $p['price']."$"; ;?></strong>
      <strong class="row">ID :<?php echo $p['ID'] ;?></strong>
      <strong class="row">
      created by :
      <?php 
  
  $ID  = $p['creator ID'];
  
  $stmt = $con->prepare("SELECT `first name`, `last name` FROM `user` WHERE `ID`=$ID");
  $stmt->execute();
  $user =  $stmt->fetch();
  
  
  
      
      echo $user['first name']." ".$user['last name'];  ?>
      </strong>
      <div class="row">
  
      <?php if($p['approved']==0){
  
          ?>
          <form method="POST" action="">
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
      <button  name="delete" class="btn btn-danger">delete</button>
      </form>
  
  
      </div>
     
        
    </div>
  </div>
  
  </div>
  
  
  
  
        <?php
  
    }
       }
  
  }


  // end section

    


  


$stmt = $con->prepare("SELECT * FROM  `post` WHERE `$search_by`LIKE '%$search%'");
$stmt->execute();

$post =  $stmt->fetchAll();

foreach($post as $p){

?>

<div class="col-md-4">

<div class="card">
  <img src="uploads/posts/<?php echo $p['image'];?>" class="card-img-top" alt="...">
  <div class="card-body">
    <h5 class="card-title"><strong><?php echo $p['title'];?></strong></h5>
    <p class="card-text"><?php echo $p['post text'];?></p>
    <strong class="row price"><?php echo $p['price']."$"; ;?></strong>
    <strong class="row">ID :<?php echo $p['ID'] ;?></strong>
    <strong class="row">
    created by :
    <?php 

$ID  = $p['creator ID'];

$stmt = $con->prepare("SELECT `first name`, `last name` FROM `user` WHERE `ID`=$ID");
$stmt->execute();
$user =  $stmt->fetch();


echo $user['first name']." ".$user['last name'];
    
    ?>
    </strong>
    <div class="row">

    <?php if($p['approved']==0){

        ?>
        <form method="POST" action="">
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
    <button  name="delete" class="btn btn-danger">delete</button>
    </form>


    </div>
   
      
  </div>
</div>

</div>

<?php

}
  
}




?>

<script>


function conf(){
  var con = confirm("Are you sure");
  if(con != true){
    event.preventDefault();
    
  }
}



</script>