<?php

include '../connect.php';




$search = trim($_POST['search'],'\'"');
$search_by = $_POST['by'];

if(strlen($search)==0 || empty($search)){

  $stmt = $con->prepare("SELECT * FROM  `user` WHERE `$search_by` LIKE '%$search%'");
$stmt->execute();

$rows =  $stmt->fetchAll();

foreach($rows as $user){


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

}
elseif(strlen($search)>=1){


$stmt = $con->prepare("SELECT * FROM  `user` WHERE `$search_by` LIKE '%$search%' ");
$stmt->execute();

$rows =  $stmt->fetchAll();

foreach($rows as $user){

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
  
}







?>

