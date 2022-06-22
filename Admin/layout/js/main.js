/*
$("#search").keyup(function(){


    var search_by = $('#search-by').val();
    var search_input = $('#search').val();


        $.ajax({
        method:'POST',
        url:'ajax/skilled_live_search.php',
        data:{
            search:search_input,
            by:search_by,
            
        }
        ,
        success:function(data){
            $('#output').html(data);
      
           

        }
    })

*/ 


function  write() {
	console('working');
}