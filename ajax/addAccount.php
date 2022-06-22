<?php

include '../connect.php';
include '../includes/functions/fun.php';



// start form proccessing --------------------------------------




// inputs
$email = filter_var($_POST['email'],FILTER_SANITIZE_EMAIL);
$F_name = filter_var($_POST['firstName'],FILTER_SANITIZE_STRING);
$L_name = filter_var($_POST['lastName'],FILTER_SANITIZE_STRING);
$password = filter_var(sha1($_POST['password']),FILTER_SANITIZE_STRING);
$Feild =filter_var($_POST['feild'],FILTER_SANITIZE_STRING);
$type = filter_var($_POST['type'],FILTER_SANITIZE_STRING);


// start discover and collect input errors -------------------------------------
$erorr = array();

if(empty($email)){
    $erorr[] = alert('alert alert-danger text-center','Email is empty' , 
        'width:80%; margin:10px 0px 0px 10%; padding:10px; ');

}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $erorr[] = alert('alert alert-danger text-center','Email is invalid' , 
        'width:80%; margin:10px 0px 0px 10%; padding:10px; ');
}

if(checkItem('email' , 'user' , $email)){
    $erorr[] = alert('alert alert-danger text-center','Email is already used' , 
        'width:80%; margin:10px 0px 0px 10%; padding:10px; ');

}

if(empty($F_name)){
    $erorr[] = alert('alert alert-danger text-center','First name is empty' , 
        'width:80%; margin:10px 0px 0px 10%; padding:10px; ');

}


if(empty($L_name)){
    $erorr[] = alert('alert alert-danger text-center','Last name is empty' , 
        'width:80%; margin:10px 0px 0px 10%; padding:10px; ');

}

if(empty($password)){
    $erorr[] = alert('alert alert-danger text-center','Password is empty' , 
        'width:80%; margin:10px 0px 0px 10%; padding:10px; ');
}

if(strlen($_POST['password'])<8){
    $erorr[] = alert('alert alert-danger text-center','Password length must greater or equal 8' , 
        'width:80%; margin:10px 0px 0px 10%; padding:10px; ');
        
}

if(empty($type)){
    $erorr[] = alert('alert alert-danger text-center','Type is not selected' , 
        'width:80%; margin:10px 0px 0px 10%; padding:10px; ');
}

if(!empty($Feild) &&  $type == 'client'){

    $erorr[] = alert('alert alert-danger text-center','Feild is not needed for client' , 
    'width:80%; margin:10px 0px 0px 10%; padding:10px; ');

}

if(empty($Feild) &&  $type == 'skilled'){

    $erorr[] = alert('alert alert-danger text-center','Feild is requried for skilled' , 
    'width:80%; margin:10px 0px 0px 10%; padding:10px; ');

}

$types = ['skilled' ,'client'];


if(!in_array($type,$types)){
    $erorr[] = alert('alert alert-danger text-center','type is invalid' , 
        'width:80%; margin:10px 0px 0px 10%; padding:10px; ');
}

// end discover and collect input errors ...........................................................


// display error alerts
if(count($erorr)>0){
   foreach($erorr as $e){
    echo $e;
   }
}

// .......................


// start ensure that token is unique ---------------------

// fetch tokens 
$stmt = $con->prepare("SELECT `token` FROM `user`");
$stmt->execute();
$tokens_count = $stmt->rowCount();
$tokens = $stmt->fetchAll();


$found=0;
do{

    $token = $token = bin2hex(random_bytes(16));
    if($tokens_count>0){
        foreach($tokens as $t){
            if($t['token']==$token){
                $found = 1;
            }		
        }

    }
    
}while($found == 0);




if(count($erorr)==0){

    if($type == 'skilled' ){

        $stmt = $con->prepare("INSERT INTO `user`
        (`password`, `token`, `first name`, `last name`, `email`,
         `register Date`, `type`, `Feild`, `activated`, `view`) 
        VALUES ('$password','$token','$F_name','$L_name','$Feild','0','0')");
        $stmt->execute();

        if($stmt){
            
            alert('alert alert-success text-center','registerd successfully' , 
        'width:80%; margin:10px 0px 0px 10%; padding:10px; ');

        alert('alert alert-info text-center','Check your email to find your ID and activation link' , 
        'width:80%; margin:10px 0px 0px 10%; padding:10px; ');

         return 1;

        }

    

    }

    

}






// end ensure that token is unique ..............................





// end form proccessing ........................................




?>