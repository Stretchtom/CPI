
var is_loading = false; // initialize is_loading by false to accept new loading
var limit = 10; // limit items per page
$(function() {
    $(window).scroll(function() {
        if($(window).scrollTop() + $(window).height() == $(document).height()) {
            if (is_loading == false) { // stop loading many times for the same page
                // set is_loading to true to refuse new loading
                is_loading = true;
                // display the waiting loader
                $('#loader').show();
                // execute an ajax query to load more statments
                $.ajax({
                    url: 'load_more.php',
                    type: 'POST',
                    data: {last_id:last_id, limit:limit},
                    success:function(data){
                        // now we have the response, so hide the loader
                        $('#loader').hide();
                        // append: add the new statments to the existing data
                        $('#items').append(data);
                        // set is_loading to false to accept new loading
                        is_loading = false;
                    }
                });
            }
       }
    });
});
