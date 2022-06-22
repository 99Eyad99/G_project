
    
 
// start live search

$("#search").keyup(function(){


    var search_by = $('#search-by').val();
    var search_input = $('#search').val();

    console.log(search_input);
    console.log(search_by);

        $.ajax({
        method:'POST',
        url:'ajax/comment_live_search.php',
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



// start delete confirm

$('table #delete').click(function(){
    return confirm('are you sure');

  })


// end delete confirm