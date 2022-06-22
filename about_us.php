<?php


ob_start();
include 'init.php';
include 'includes/templates/upperNav.php';
include 'includes/templates/lowerNav.php';
include 'sender.php';


?>


<div class="container box ">
    <div class="row">

      
        <div class="col-md-6">
            <p>
                Our platform enable skilled to offer their servies online
                and enable clients to find skilled workers online
            </p>       
        </div>

        <div class="col-md-6">
            <img src="online.png" class="img-fluid" alt="">        
        </div>



    


    </div>
</div>






<style>

    body{
        background-color:#130f40;
    }

    .box{
        background-color:white;
        max-width:70%;
        margin: 70px 0px 50px 15%;
        min-width: 230px;
        border-radius:5px;
    
    }

    .box img{
        margin: 20px auto;
     
    }

    .box p{
        margin: 100px 0px 20px 40px;
        font-size:25px;
        font-weight:600;
        color: #000;
    }

    @media(max-width:500){

        .box p{
            margin: 30px 0px 20px 40px;
            font-size:20px;
            font-weight:600;
            color: #000;
        }

        
        .box{
            background-color:white;
            max-width:70%;
            margin: 25px 0px 25px 15%;
            min-width: 230px;
            border-radius:5px;
    
        }



    }



</style>