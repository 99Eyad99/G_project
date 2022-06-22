<?php


class skilled{

    private $ID;
    private $password;
    private $token;
    public $first_name;
    public $last_name;
    public $description;
    protected $email;
    public $image;
    public $registerDate;
    public $type;
    public $feild;
    protected $activated;
    public $view;
    public $fav = array();
    protected $request = array();



    // getters
    function getID(){
        return $this->ID;
    }

    function getPassword(){
        return $this->password;
    }

    function getToken(){
        return $this->token;
    }

    function getFirstName(){
        return $this->first_name;
    }

    function getLastName(){
        return $this->last_name;
    }

    function getDescription(){
        return $this->description;
    }

    function getEmail(){
        return $this->email;
    }

    function getImage(){
        return $this->image;
    }

    function getRegisterDate(){
        return $this->registerDate;
    }

    function getType(){
        return $this->type;
    }

    function getFeild(){
        return $this->feild;
    }

    function getAcivated(){
        return $this->activated;
    }

    function getFav(){
        return $this->fav;
    }

    function getView(){
        return $this->view;
    }

       function getRequest(){
        return $this->request;
    }


  





    // setters

    function setID($ID){
       

        $this->ID=$ID;

        global $con;
        $stmt = $con->prepare("SELECT * FROM `user` WHERE `ID`=$ID AND `type`='skilled'");
        $stmt->execute();
        $data= $stmt->fetch();
    
        // insert fav list
        $stmt2 = $con->prepare(" SELECT `post ID` FROM `fav list` WHERE `user ID`=$ID");
        $stmt2->execute();
        $data2= $stmt2->fetchAll();
        foreach($data2 as $d){
            $this->fav[] =  $d['post ID'];
        }




      
         

        if(count($data)!=0){

        	$password = $data['password'];
           $token =   $data['token'];
           $description = $data['description'];
           $first_name = $data['first name'];
           $last_name =  $data['last name'];
           $email = $data['email'];
           $image =  $data['image'];
           $registerDate = $data['register Date'];
           $type = $data['type'];
           $feild = $data['Feild'];
           $activated = $data['activated'];
           $view = $data['view'];
           

           // add values
           $this->password=$password;
           $this->token = $token;
           $this->first_name = $first_name;
           $this->last_name = $last_name;
           $this->email = $email;
           $this->image = $image;
           $this->registerDate = $registerDate;
           $this->type = $type;
           $this->feild = $feild;
           $this->activated = $activated;
           $this->view = $view;


        }

           

    }


  // setters
    function setPassword($pass){
        $this->password=$pass;
    }

    function setToken($token){
        $this->token=$token;
    }

    function setFirstName($first_name){
        $this->first_name=$first_name;
    }

    function setLastName($last_name){
        $this->last_name=$last_name;
    }

    function setDescription($description){
        $this->description=$description;
    }

    function setEmail($email){
        $this->email=$email;
    }

    function setImage($image){
        $this->image=$image;
    }

    function setRegisterDate($registerDate){
        $this->registerDate=$registerDate;
    }

    function setType($Type){
        $this->type=$Type;
    }


    function setFeild($feild){
        $this->feild=$feild;
    }

    function setView($view){
        $this->view=$view;
    }

    // class functions

    function saveToDB(){

    	// variables
           $ID = $this->ID;
    	   $password = $this->password;
           $token =   $this->token;
           $description = $this->description;
           $first_name = $this->first_name;
           $last_name =  $this->last_name;
           $email = $this->email;
           $image =  $this->image;
           $registerDate = $this->registerDate;
           $type = $this->type;
           $feild = $this->feild;
           $activated = $this->activated;
           $view = $this->view;
     

     // update data
        global $con;
        $stmt = $con->prepare("UPDATE `user` SET 
        	`password`='$password',`token`='$token',`first name`='$first_name',`last name`='$last_name'
        	,`description`='$description',`email`='$email',`image`='$image',`register Date`='$registerDate',
        	`type`='$type',`Feild`='$feild',`activated`='$activated',`view`='$view' 
        	WHERE `ID`=$ID");
        $stmt->execute();

     
           return $stmt;
        
    
    }

    function AddToFav($post){

       global $con;
       $stmt = $con->prepare("INSERT INTO `fav list`( `user ID`, `post ID`) 
       VALUES ('$this->ID','$post')");
       $stmt->execute();

    }

    function removeFromFav($post){

        global $con;
        $stmt = $con->prepare("DELETE FROM `fav list` WHERE `post ID`=$post");
        $stmt->execute();
 
     }


    function AddPost($ID, $title , $desc , $price , $image){

        global $con;
        $stmt = $con->prepare("INSERT INTO `post`( `title`, `post text`, `price`, `image`, `section ID`, `creator ID`) 
        VALUES ('$title','$desc','$price','$image','4000002','$ID')" );
        $stmt->execute();
        return $stmt;
 
     }

     function AddPost_portfilo($ID, $title , $image){

        global $con;
        $stmt = $con->prepare("INSERT INTO `post`( `title`, `image`, `section ID`, `creator ID`) 
        VALUES ('$title','$image','4000010','$ID')" );
        $stmt->execute();
        return $stmt;
 
     }

     function sendRequest($request){

        $request->create();

     }

    function Request_accept($ID){

        global $con;
        $stmt = $con->prepare("UPDATE `request` SET `accept`='1'  WHERE `ID`='$ID'");
        $stmt->execute();
        return $stmt;

    }

    
    function Request_Reject($ID){

        global $con;
        $stmt = $con->prepare("DELETE FROM `request` WHERE `ID`='$ID'");
        $stmt->execute();
        return $stmt;


    }


    function addComment($comment,$postID){

        global $con;
        $stmt = $con->prepare("INSERT INTO `comment`(`comment-rating`, `creator ID`, `post ID`) 
                               VALUES ('$comment','$this->ID','$postID')");
        $stmt->execute();
        return $stmt;

    }


    



    


}


/*
include '../Admin/init.php';


$s = new skilled();
$s->setID('3000017');

$s->setFirstName("eyad");

$s->saveToDB();


*/













?>