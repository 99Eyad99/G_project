<?php 


  ?>



  <nav>
    <ul>
        <li><a href="main.php"><img src="logo.jpg" style="width:55px; margin-left: 10px;"></a></li>
        <div class="right">
            <li><a href="add-post.php">Add post</a></li>
            <li><a href="dashborad">dashboard</a></li>
            <li><a href="profile.php">profile</a></li>
        

            <li><a href="logout.php">logout</a></li>
        </div>
    </ul>
    
  </nav>


<style type="text/css">

nav{
    width: 100%;
    background-color: white;
    min-height: 60px;

}

@media(max-width:475px){
    nav{
        height: 120px;
    }

}

@media(max-width: 407px){
    nav{
        height: 160px;
    }
}
    
ul{
    list-style-type: none;
    margin: 0px;
    padding: 0px;
    width: 100%;
}

nav ul li{
    float: left;
    position: relative;
    line-height: 55px;


}

nav ul .right{
      float: right;
}

nav ul li a{
    text-decoration: none;
    display: block;
    color: black;
    padding: 0px 8px 0px 8px;
    font-size:17px;
}

nav ul li a:hover{
   text-decoration: none;
}


</style>
