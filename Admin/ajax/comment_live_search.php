<?php
include '../connect.php';



$search = trim($_POST['search'],'\'"');
$search_by = $_POST['by'];

if(strlen($search)==0 || empty($search)){

  $stmt = $con->prepare("SELECT * FROM  `comment`");
$stmt->execute();

$rows =  $stmt->fetchAll();

foreach($rows as $r){

?>

<tr>
      <th scope="row"><?php echo $r['ID']; ?></th>

      <td><?php 
      $id = $r['creator ID'];
      $stmt = $con->prepare("SELECT `user`.`first name`,`user`.`last name` ,`user`.`image` ,`user`.`type`
       FROM `comment`,`user` WHERE `user`.`ID` = $id");
      $stmt->execute();
      $added_by =  $stmt->fetch();
   
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
      
      $stmt = $con->prepare("SELECT `post`.`title` FROM `comment`,`post`
       WHERE  `post`.`ID`=$id");
      $stmt->execute();
      $post =  $stmt->fetch();
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
elseif(strlen($search)>=1){


$stmt = $con->prepare("SELECT * FROM  `comment` WHERE `$search_by`LIKE '%$search%'");
$stmt->execute();

$rows =  $stmt->fetchAll();


foreach($rows as $r){

?>

<tr>
      <th scope="row"><?php echo $r['ID']; ?></th>

      <td><?php 
      $id = $r['creator ID'];
      $stmt = $con->prepare("SELECT `user`.`first name`,`user`.`last name` ,`user`.`image` ,`user`.`type`
       FROM `comment`,`user` WHERE `user`.`ID` = $id");
      $stmt->execute();
      $added_by =  $stmt->fetch();
   
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
      
      $stmt = $con->prepare("SELECT `post`.`title` FROM `comment`,`post`
       WHERE  `post`.`ID`=$id");
      $stmt->execute();
      $post =  $stmt->fetch();
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

<script>


function conf(){
  var con = confirm("Are you sure");
  if(con != true){
    event.preventDefault();
    
  }
}





</script>

