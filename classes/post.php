<?php

class post{


private $ID;
public $title;
public $post_text;
public $price;
public $image;
public $rating;
public $approved;
public $section_ID;
public $creator_ID;


// start getters 


function getID(){
	return $this->ID;
}

function getTitle(){
	return $this->title;
}

function getPostText(){
	return $this->post_text;
}

function getPrice(){
	return $this->price;
}

function getImage(){
	return $this->image;
}

function getRating(){
	return $this->rating;
}

function getApproved(){
	return $this->approved;
}

function getSectionID(){
	return $this->section_ID;
}

function getCreatorID(){
	return $this->creator_ID;
}

// end getters ---------------------


// start setter 


function setID($ID){
	  $this->ID=$ID;

	  global $con;
      $stmt = $con->prepare("SELECT * FROM `post` WHERE `ID`='$ID'");
      $stmt->execute();
      $data= $stmt->fetch();

      $this->title = $data['title'];
      $this->post_text = $data['post text'];
      $this->image = $data['image'];
      $this->rating = $data['rating'];
      $this->approved = $data['approved'];
      $this->section_ID = $data['section ID'];
      $this->creator_ID = $data['creator ID'];



}


function setTitle($title){
	$this->title=$title;
}

function setPostText($text){
	$this->post_text=$text;
}

function setPrice($price){
	$this->price=$price;
}

function setImage($img){
	$this->image=$img;
}

function setRating($rating){
	$this->rating=$rating;
}

function setApproved($approved){
	$this->approved=$approved;
}

function setSectionID($sec_ID){
	$this->section_ID=$sec_ID;
}

function setCreatorID($creator_ID){
	$this->creator_ID=$creator_ID;
}

function saveToDB(){

	// variables
	$ID =$this->ID;
	$title = $this->title;
	$post_text = $this->post_text;
	$price = $this->price;
	$image = $this->image;
	$rating = $this->rating;
	$approved = $this->approved;
	$section_ID = $this->section_ID;
	$creator_ID = $this->creator_ID;
	// ------------------------------



	  global $con;
      $stmt = $con->prepare("UPDATE `post` SET  `title`='$title',`post text`='$post_text',`price`='$price',
      	`image`='$image',`rating`='$rating',`approved`='$approved',`section ID`='$section_ID',
      	`creator ID`='$creator_ID'  WHERE `ID`='$ID'");
      $stmt->execute();

      return $stmt;

}



}


/*
include '../init.php';
$post = new post();
$post->setID('5000002');

print_r($post);
*/



































?>