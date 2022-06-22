// start live search

$("#search").keyup(function(){


    var search_by = $('#search-by').val();
    var search_input = $('#search').val();

 
        $.ajax({
        method:'POST',
        url:'ajax/post_live_search.php',
        data:{
            search:search_input,
            by:search_by,
            
        }
        ,
        success:function(data){
            $('#output').html(data);
            
        }
    })

    
   
})


// end live search






$('.btn-deliver').click(function(){
    

	// collecting inputs
    var gID =  $(this).siblings('.gID').val();
	var  requester = $(this).siblings('.Requester').val();
	var  postID = $(this).siblings('.post_ID').val();
	var press = $(this);
	



	// set global prompt string defaults
$.xPrompt.defaults = {
  placeholder: "test",
  error: "error", 
  header:'By sending this you are signing an agreement between you and the other side on this price and service'
};



	$.xPrompt({
      placeholder: "Enter a price",
      error: "error"
    }, function(i){
        var input = i;


// send ajax request
$.ajax({
   method:'POST',
   url:'ajax/sendAgreement.php',
   data:{
	
	  requester:requester,
	  postID:postID,
	  price:input,
      gID:gID

}
,
success:function(data){  
	press.hide();
	press.parent().append('<span> Wait for the agree <i class="far fa-clock"></i></span>');
	
}

})

// end ajax



    })

})


	     


