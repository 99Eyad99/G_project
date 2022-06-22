<?php

include '../connect.php';



$search = trim($_POST['search'],'\'"');
$search_by = $_POST['by'];

if(strlen($search)==0 || empty($search)){

  $stmt = $con->prepare("SELECT * FROM  `user` WHERE `$search_by`LIKE '%$search%' AND `type`='client' ");
$stmt->execute();

$rows =  $stmt->fetchAll();

foreach($rows as $r){

?>

<tr>
      <th scope="row"><?php echo $r['ID']; ?></th>
      <td><?php echo $r['first name']; ?></td>
      <td><?php echo $r['last name']; ?></td>
      <td><?php echo $r['email']; ?></td>
      <td><img style="width:60px;" src="uploads/client/<?php echo $r['image']; ?>"></td>
      <td><?php echo $r['register Date']; ?></td>
      
      <td>
        <div class="control">
      
      
      <a href="?action=Edit&id=<?php  echo $r['ID']; ?>">
        <button type="button" class="btn btn-success">Edit <i class="fas fa-user-edit"></i>
         </button>
       </a>

       <a href="?action=details&id=<?php  echo $r['ID']; ?>">
             <button type="button" class="btn btn-primary">Details</button>
       </a>
       
      <a href="?action=delete&id=<?php  echo $r['ID']; ?>"><button type="button" class="btn btn-danger">Delete <i class="fas fa-user-times"></i></button>
      </a>

      

      </div>
      </td>
      
      
    </tr>


<?php

}

}
elseif(strlen($search)>=1){


$stmt = $con->prepare("SELECT * FROM  `user` WHERE `$search_by`LIKE '%$search%' AND `type`='client' ");
$stmt->execute();

$rows =  $stmt->fetchAll();

foreach($rows as $r){

?>

<tr>
      <th scope="row"><?php echo $r['ID']; ?></th>
      <td><?php echo $r['first name']; ?></td>
      <td><?php echo $r['last name']; ?></td>
      <td><?php echo $r['email']; ?></td>
      <td><img style="width:60px;" src="uploads/skilled/<?php echo $r['image']; ?>"></td>
      <td><?php echo $r['register Date']; ?></td>
      
      <td>
        <div class="control">
      
      
      <a href="?action=Edit&id=<?php  echo $r['ID']; ?>">
        <button type="button" class="btn btn-success">Edit <i class="fas fa-user-edit"></i>
         </button>
       </a>

       <a href="?action=details&id=<?php  echo $r['ID']; ?>">
             <button  type="button" class="btn btn-primary">Details</button>
       </a>
       
    <form method="POST" action="">
       <input name="ID" value="<?php echo $r['ID']?>" hidden>
      
    <button  onclick="conf()" id="delete" name="delete" type="submit" class="btn btn-danger" >Delete <i class="fas fa-user-times"></i></button>
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

