


// start buttons control

// edit info button
 $('#edit-form').css('display','none');


$('#edit-btn').click(function(){
    if($('#edit-form').is(':visible') == true){
        $('#edit-form').slideUp(1000);


    }
    else{
        $('#edit-form').slideDown(1000);
        $('#password-form').hide();
        $('#image-form').hide();
       
    }

});

// edit info button


// password button


$('#password-form').css('display','none');


$('#password-btn').click(function(){
    if($('#password-form').is(':visible') == true){
        $('#password-form').slideUp(1000);


    }
    else{
        $('#password-form').slideDown(1000);
        $('#edit-form').hide();
        $('#image-form').hide();
    }

});

// password button


// image button


$('#image-form').css('display','none');


$('#image-btn').click(function(){
    if($('#image-form').is(':visible') == true){
        $('#image-form').slideUp(1000);


    }
    else{
        $('#image-form').slideDown(1000);
        $('#edit-form').hide();
        $('#password-form').hide();
    }

});


