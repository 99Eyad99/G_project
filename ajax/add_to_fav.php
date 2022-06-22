<?php





include '../admin/init.php';



$dsn='mysql:host=localhost;dbname=g_project';
$user='root';
$pass='';
$option = array(
    PDO::MYSQL_ATTR_INIT_COMMAND =>'SET NAMES utf8'
);

try{
    $con = new PDO($dsn,$user,$pass,$option);
    $con->setAttribute(PDO::ATTR_ERRMODE , PDO::ERRMODE_EXCEPTION);
    //echo 'you are connected';
}
catch(PDOException $e){
   // echo 'Falied to connect'.' ',$e->getMessage();
}


if(!isset($_SESSION['ID'])){
	session_start();
    $ID =  $_SESSION['ID'];
}

$pID= $_POST['pID'];




// check the type of the user by checking where the ID exist


$stmt = $con->prepare("SELECT `type` FROM `user` WHERE `ID`=$ID");
$stmt->execute();
$type= $stmt->fetch();

$stmt = $con->prepare("SELECT * FROM `post` WHERE `approved`=1");
$stmt->execute();
$H_post= $stmt->fetchAll();

// start skilled

if($type['type'] == 'skilled'){

    include '../classes/skilled.php';
    $user = new skilled();
    $user->setID($ID);

}else{

    include '../classes/client.php';
    $user = new client();
    $user->setID($ID);

}
    // end checking

    
    if(in_array($pID ,$user->fav)){
        $user->removeFromFav($pID);
    }else{
        $user->AddToFav($pID);
    }

    


?>



  




<script type="text/javascript">


$("form").submit(function(e){
    e.preventDefault();
  });


  $(".btn-warning").click(function(){



var post_ID = $(this).parent().children('#pID').val();

 $.ajax({
 method:'POST',
 url:'ajax/add_to_fav.php',
 data:{
     pID:post_ID
 }
 ,
 success:function(data){
     $('#output').html(data);
    
    
 }

 })

  })


 


</script>




