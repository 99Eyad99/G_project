      $(".toggle").click(function(){
          
          var navWidth = $(".nav").width();

// side bar is not fitting with small screens
        if($('body').width()>700){


          if(navWidth==220){
              $(".nav").css('width','60px');
              $(".nav .title").hide();
              $('.main').css('left','60px');
              $('.main').css('width','calc(100% - 60px)')
          };
           
             
          
          
          if(navWidth==60){
               $(".nav").css('width','220px');
               $(".nav .title").show();
              $('.main').css('left','220px');
              $('.main').css('width','calc(100% - 220px)')
          };
             
            
        
          }

            


          



  });


// width of side bar will be 60px for small screens 

if($('body').width()<700){


      $(".nav").css('width','60px');
              $(".nav .title").hide();
              $('.main').css('left','60px');
              $('.main').css('width','calc(100% - 60px)');



};

// start drop-down 

$(document).ready(function(){

// hide the below element
$('.profile-drop-down').css('display','none');


$('.admin_img').click(function(){
if($('.profile-drop-down').is(':visible') == true){
    $('.profile-drop-down').slideUp(1000);

}
else{
    $('.profile-drop-down').slideDown(1000);
}

});




});

// end drop down
    