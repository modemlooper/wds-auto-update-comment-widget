(function($) {

	// run get comment function and poll api for comments
	get_comments();

	// set interval to check every 15 seconds
	var refresh = setInterval( function() {
		get_comments();
	}, 15000);


	// after 2 mins kill api check
	setTimeout( function() {
		clearInterval(refresh);
	}, 120000);


	/**
	 * get_comments function.
	 * 
	 */
	function get_comments() {
	
		// borrow the admin loading spinner
		$('#wds-comment-widget').html( '<img src="/wp-admin/images/wpspin_light.gif">' );
	
		$.ajax({
		    url: apiurl + 'comments',
		    dataType: 'json',
		    type: 'GET',
		    success: function(data) {
		    	// send data to processing function
		        process_comment_data( data );
		    },
		    error: function() {
		       console.log('Error with API');
		       $('#wds-comment-widget').html( 'No comments' );
		    }
		});
		
	}



	/**
	 * process_comment_data function.
	 * 
	 */
	function process_comment_data( data ) {
		
		// start an array for comments
		var comments = [];
		
		$.each( data, function( index, value ){
		
			comments += '<li postid="' + data[index]['ID'] + '">' + data[index]['comment_author'] + ' on <a href="' + data[index]['guid'] + '">' + data[index]['post_title'] + '</a></li>';
		
		});
		
		setTimeout( function() {
			$('#wds-comment-widget').html( '<ul>' + comments + '<ul>' );
		}, 500);
		
	}
	
	
 })(jQuery);