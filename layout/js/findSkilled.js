
// start live search

$("#search").keyup(function(){

    var search_input = $('#search').val();



        $.ajax({
        method:'POST',
        url:'ajax/skilled_live_search.php',
        data:{
            search:search_input   
        }
        ,
        success:function(data){
            $('#output').html(data);
            
        }
    })

    

    

    
})
