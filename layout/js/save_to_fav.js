
$("form").submit(function(e){
    e.preventDefault();
  });


// add or delete from fav list

$(".btn-warning").click(function(){

	if($(this).children().attr('id')=='save'){
		$(this).children('.fa-bookmark').remove();
		$(this).html('<i id="not-save" class="far fa-bookmark"></i>');

	}else{
		
		$(this).children('.fa-bookmark').remove();
		$(this).html('<i id="save" class="fas fa-bookmark"></i>');

	}

	

    var post_ID = $(this).parent().children('#pID').val();

	$.ajax({
	method:'POST',
	url:'ajax/add_to_fav.php',
	data:{
		pID:post_ID
	}
	,
	success:function(data){
		
	
	
	}

	})
	
})
