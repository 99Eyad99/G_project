<?php 

include 'init.php';
include 'includes/templates/upperNav.php';
include 'includes/templates/lowerNav.php';



   $token = filter_var( $_GET['token'] , FILTER_SANITIZE_STRING );

   // check if token is exist in database
   if( isset($token) && checkItem( 'token' , 'user' ,$token ) == 1 ){

         $stmt = $con->prepare("SELECT `ID` FROM `user` WHERE `token` = '$token'");
         $stmt->execute();
         $userID = $stmt->fetch();
         $userID  = $userID['ID'];


         $stmt = $con->prepare("UPDATE `user` SET `activated` = '1' WHERE `user`.`token` = '$token'");
         $stmt->execute();


          // fetch tokens and check if generate token is unique
         $stmt = $con->prepare("SELECT `token` FROM `user`");
         $stmt->execute();
         $tokens_count = $stmt->rowCount();
         $tokens = $stmt->fetchAll();
         $token = $token = bin2hex(random_bytes(16));
         $found = 0;
         $check = 0;
         do{
            $token = $token = bin2hex(random_bytes(16));
            if($tokens_count > 0){
	            foreach($tokens as $t){

		            if($t['token'] != $token){
			            $check = 1;
		            }else{
			            $check = 0;
		            }	
	            }  
	         $found = $check;
         }
         }while($found == 0);

         if($check == 1){
            
            // after activating the account the token will be changed 
            $stmt = $con->prepare("UPDATE `user` SET `token` = '$token' WHERE `ID` = '$userID' ");
            $stmt->execute();
         }

      if($stmt){

        alert('alet alert-info text-center',
			   'Your account has been activated successfully' , 
			   'width:80%; margin:20px 0px 0px 10%; padding:10px;');

      }


   }







?>



