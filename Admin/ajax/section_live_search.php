<?php
include '../connect.php';




$search = trim($_POST['search'],'\'"');
$search_by = $_POST['by'];

if(strlen($search)==0 || empty($search)){

  $stmt = $con->prepare("SELECT * FROM  `section` WHERE `$search_by`LIKE '%$search%' ");
$stmt->execute();

$rows =  $stmt->fetchAll();

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
       
      <a href="?action=delete&id=<?php  echo $r['ID']; ?>"><button type="button" class="btn btn-danger">Delete <i class="fas fa-user-times"></i></button>
      </a>

      

      </div>
      </td>
      
      
    </tr>


<?php

}

}
elseif(strlen($search)>=1){


$stmt = $con->prepare("SELECT * FROM  `section` WHERE `$search_by`LIKE '%$search%' ");
$stmt->execute();

$rows =  $stmt->fetchAll();

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