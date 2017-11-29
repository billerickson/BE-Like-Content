// @codekit-prepend "js.cookie.js"

jQuery(function($){

	// Get liked content
	var cookieName = 'be_like_content';
	var likedContent = Cookies.get( cookieName );
	if( likedContent ) {
		likedContent = JSON.parse( likedContent );
	} else {
		likedContent = [];
	}

	// Single post, set active if already liked
	$('.be-like-content').each(function(e){
		var post_id = $(this).data('post-id');
		if( likedContent.indexOf( post_id ) != -1 )
			$(this).addClass('liked');
	});

	// Like on click
	var liking = false;
	$(document).on('click', '.be-like-content', function(e){
		e.preventDefault();
		var button = $(this);
		var post_id = $(button).data('post-id');
		if( ! liking && -1 == likedContent.indexOf( post_id ) ) {

			liking = true;
			$(button).addClass('liking');
			var data = {
				action: 'be_like_content',
				post_id: post_id,
			};
			$.post( be_like_content.url, data, function(res){
				if( res.success ) {
					$(button).removeClass('liking').addClass('liked').html(res.data);
					var liking = false;
					likedContent.push( post_id );
					Cookies.set( cookieName, JSON.stringify( likedContent ), { expires: 365 } );
					//console.log( res );
				} else {
					//console.log( res );
				}
			}).fail(function( xhr, textStatus, e ){
				//console.log( xhr.responseText );
			});
		}
	});

});
