<?php


class request{

    private $ID;
    private $price;
    public $type;
    public $accept;
    public $status;
    public $groupID;
  

    function consract($ID , $price , $type ,$accept, $status , $groupID){
        $this->ID = $ID;
        $this->price= $price;
        $this->type = $type;
        $this->accept = $accept;
        $this->status = $status;
        $this->groupID = $groupID;
        

    }

    function create(){

        global $con;
        $stmt = $con->prepare("INSERT INTO `request`(`price`, `type`, `accept`, `status`, `GroupID`) 
                        VALUES ('$this->price','$this->type','$this->accept','$this->status','$this->groupID')");
        $stmt->execute();
    }


    // getters

    function getID(){
        return $this->ID;
    }

    function getPrice(){
        return $this->price;
    }


    function getType(){
        return $this->type;
    }

    function getAccept(){
        return $this->accept;
    }


    function getStatus(){
        return $this->status;
    }


    function getGroupID(){
        
        return $this->groupID;
        

    }

    // end getters 



    //  start setters

    function setID($ID){
        $this->ID = $ID;

    }

    function setPrice($price){
        $this->price = $price;

    }

    function setType($type){
        $this->type = $type;

    }
    function setStatus($status){
        $this->status = $status;
    }

    function setGroupID($gID){
        
       $this->groupID=$gID;  

    }

















}
















?>