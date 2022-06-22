<?php


include '../connect.php';
session_start();

if(isset($_SESSION)){
   $ID = $_SESSION['ID'];
}

$search = trim($_POST['search'],'\'"');
$search_by = $_POST['by'];
$allowed = ['title','post text','price'];




if(in_array($search_by,$allowed) && ((strlen($search)==0) || empty($search))  ){
	
	
		// get title of the post
		$stmt = $con->prepare("SELECT * FROM `post` WHERE `creator ID`='$ID' AND `deleted_at` IS NULL");
		$stmt->execute();
		$posts = $stmt->fetchAll();


        foreach($posts as $post){

			$secID = $post['section ID'];
	        $stmt = $con->prepare("SELECT `name` FROM `section` WHERE `ID`='$secID'");
            $stmt->execute();
            $SecName = $stmt->fetch();

		?>
		    <tr>
		        <td><?php echo $post['title']; ?></td>
		        <td><?php echo $post['post text'];  ?></td>
		        <td><?php echo $post['price'].'$';  ?></td>
		        <td><?php echo $SecName['name'];  ?></td>
		        <td>
			       <a href="post-view.php?id=<?php echo $post['ID']; ?>"><button type="button" class="btn btn-primary">view <i class="far fa-eye"></i></button></a>
			       <a href="postEdit.php?id=<?php echo $post['ID']; ?>"><button type="button" class="btn btn-success">Edit <i class="far fa-edit"></i></button></a>

			      <form method="POST">
				     <input type="text" name="ID" value="<?php echo $post['ID'];  ?>" hidden>
				     <button type="submit" name="delete" class="btn btn-danger">Delete <i class="far fa-trash-alt"></i></button>
			      </form>
				</td>
	        </tr>
		<?php
	}



}

if(in_array($search_by,$allowed) && ((strlen($search)>0) || empty($search))){


	
		// get title of the post
		$stmt = $con->prepare("SELECT * FROM `post` WHERE `creator ID`='$ID' AND `$search_by`LIKE '%$search%' AND `deleted_at` IS NULL");
		$stmt->execute();
		$posts = $stmt->fetchAll();

        foreach($posts as $post){

			$secID = $post['section ID'];
	        $stmt = $con->prepare("SELECT `name` FROM `section` WHERE `ID`='$secID'");
            $stmt->execute();
            $SecName = $stmt->fetch();

		?>
		    <tr>
		        <td><?php echo $post['title']; ?></td>
		        <td><?php echo $post['post text'];  ?></td>
		        <td><?php echo $post['price'].'$';  ?></td>
		        <td><?php echo $SecName['name'];  ?></td>
		        <td>
			       <a href="post-view.php?id=<?php echo $post['ID']; ?>"><button type="button" class="btn btn-primary">view <i class="far fa-eye"></i></button></a>
			       <a href="postEdit.php?id=<?php echo $post['ID']; ?>"><button type="button" class="btn btn-success">Edit <i class="far fa-edit"></i></button></a>

			      <form method="POST">
				     <input type="text" name="ID" value="<?php echo $post['ID'];  ?>" hidden>
				     <button type="submit" name="delete" class="btn btn-danger">Delete <i class="far fa-trash-alt"></i></button>
			      </form>
				</td>
	        </tr>
		<?php
	}

	



}






?>