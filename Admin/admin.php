<?php


class admin{

    private $ID;
    private $password;
    public $first_name;
    public $last_name;
    public $email;
    public $image;

    // getters
    function getID(){
        return $this->ID;
    }

    function getPassword(){
        return $this->password;
    }

    function getFirstName(){
        return $this->first_name;
    }

    function getLastName(){
        return $this->last_name;
    }

    function getEmail(){
        return $this->email;
    }

    function getImage(){
        return $this->image;
    }

    // setters

    function setID($ID){

        $this->ID=$ID;

        global $con;
        $stmt = $con->prepare("SELECT * FROM admin WHERE ID=$ID");
        $stmt->execute();
        $data= $stmt->fetch();

           $password = $data['password'];
           $first_name = $data['first name'];
           $last_name =  $data['last name'];
           $email = $data['email'];
           $image =  $data['image'];

           // add values
           $this->password=$password;
           $this->first_name = $first_name;
           $this->last_name = $last_name;
           $this->email = $email;
           $this->image = $image;

           

    }

    function setPassword($pass){
        $this->password=$pass;
    }

    function setFirstName($first_name){
        $this->first_name=$first_name;
    }

    function setLastName($last_name){
        $this->last_name=$last_name;
    }

    function setEmail($email){
        $this->email=$email;
    }

    function setImage($image){
        $this->image=$image;
    }

    // class functions

    function saveToDB(){
        $ID = $this->ID;
        $pass = $this->password;
        $first_name = $this->first_name;
        $last_name = $this->last_name;
        $email = $this->email;
        $image =  $this->image;


        

        global $con;
        $stmt = $con->prepare("UPDATE `admin` SET `password`='$pass',`first name`='$first_name',
        `last name`='$last_name',`email`='$email',`image`='$image' WHERE ID=$ID");
        $stmt->execute();

     
           return $stmt;
        
        
        

    }





}

/*

session_start();
include 'init.php';
$ID = $_SESSION['ID'];
$me = new admin();


$me->setID($ID);
$me->saveToDB();

*/

















?>