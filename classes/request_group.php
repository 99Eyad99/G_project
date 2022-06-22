<?php

class request_group{
    private $ID;
    private $from;
    private $to;
    private $postID;
    public $status;
    protected $requests = array();



    function create(){

        global $con;
        $stmt = $con->prepare("INSERT INTO `request group`(`from`, `to`, `post ID`, `status`) 
                               VALUES ('$this->from','$this->to','$this->postID','$this->status')");
        $stmt->execute();
    }

    function consract($ID , $from ,  $to , $postID , $status){
        $this->ID = $ID;
        $this->from= $from;
        $this->to= $to;
        $this->postID = $postID;
        $this->status = $status;

    }


  // start getters

    function getID(){
        return $this->ID;
    }

    function getFrom(){
        return $this->from;
    }

    function getTo(){
        return $this->to;
    }

    function getPostID(){
        return $this->postID;
    }

    function getStatus(){
        return $this->status;
    }

    function getRequests(){
        return $this->requests;
    }

    // end getters


    // start setters

    function setID($ID){
        $this->ID=$ID;
    }

    function setFrom($from){
        $this->from=$from;
    }

    function setTo($to){
        $this->to=$to;
    }

    function setPostID($postID){
        $this->postID=$postID;
    }

    function setStatus($status){
        $this->status=$status;
    }

    function setRequest($request){
        if(count($this->requests)<3){
            $request->setGroupID($this->getID());
            $this->requests[] = $request;
        }
        
    }



    // used to fetch from DB and create object
    function fetch($ID){

        global $con;
        $stmt = $con->prepare("SELECT * FROM `request group` WHERE `ID`='$ID'");
        $stmt->execute();
        $data =  $stmt->fetchAll();

        foreach($data as $d){
            $this->ID=$d['ID'];
            $this->from=$d['from'];
            $this->to=$d['to'];
            $this->postID=$d['post ID'];
            $this->status=$d['status'];

        }


    }

    function setRequest_DB($request){

        global $con;

        $price = $request->getPrice();
        $type = $request->getType();
        $accept = $request->getAccept();
        $status = $request->getStatus();
        $groupID = $this->ID;

        $stmt = $con->prepare("INSERT INTO `request`(`price`, `type`, `accept`, `status`, `GroupID`) 
                                VALUES ('$price','$type','$accept','$status','$groupID')");
        $stmt->execute();
        
    }

    function update_DB(){


        global $con;
        $stmt = $con->prepare("UPDATE `request group` 
        SET`from`='$this->from',`to`='$this->to',`post ID`='$this->postID',`status`='$this->status' 
        WHERE `ID`='$this->ID'");
        $stmt->execute();


    }


    function insert_request_DB(){


        global $con;

        foreach($this->requests as $request){

            
            $price = $request->getPrice();
            $type = $request->getType();
            $accept = $request->getAccept();
            $status = $request->getStatus();
            $groupID = $request->getGroupID();

           

  
         
        $stmt = $con->prepare("INSERT INTO `request`(`price`, `type`, `accept`, `status`, `GroupID`) 
        VALUES ('$price','$type','$accept','$status','$groupID')");
        $stmt->execute();
    
        

        }


    }




}

/*
include '../connect.php';
include 'client.php';
include 'skilled.php';
include 'request.php';

$client = new client();
$client->setID('3000002');


$skilled = new skilled();
$skilled->setID('3000032');



$req = new request();
$req->setID('111');
$req->setPrice('15');
$req->setType('acceptance');
$req->setStatus('acceptance');
// create request group

$Group = new request_group();
$Group->setID(120);
$Group->setFrom($client->getID());
$Group->setTo($skilled->getID());
$Group->setPostID('5000002');
$Group->setStatus('0');

$client->Add_requestGroup($Group);


//$Group->create();
// ------------------------


print_r($client->sendRequest($client->getID() , $skilled->getID() ,'5000002', $req));







*/










?>