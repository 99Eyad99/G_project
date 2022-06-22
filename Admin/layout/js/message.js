// select all

$('#select-all').click(function(){
    $('input[type="checkbox"]').prop('checked',this.checked);
})








// end select all -----------------





// start live search

$("#search").keyup(function(){


    var search_by = $('#search-by').val();
    var search_input = $('#search').val();


        $.ajax({
        method:'POST',
        url:'ajax/user_live_search.php',
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

    