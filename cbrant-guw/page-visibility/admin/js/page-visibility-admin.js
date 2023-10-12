(function($) {
	 // we create a copy of the WP inline edit post function
	 var $wp_inline_edit = inlineEditPost.edit;
	 // and then we overwrite the function with our own code
	 inlineEditPost.edit = function(id) {
		 // "call" the original WP edit function
		 // we don't want to leave WordPress hanging
		 $wp_inline_edit.apply(this, arguments);
 
		 // now we take care of our business
 
		 // get the post ID
		 var $post_id = 0;
		 if (typeof(id) == 'object')
			 $post_id = parseInt(this.getId(id));
 
		 if ($post_id > 0) {
			 // define the edit row
			 var $edit_row = $('#edit-' + $post_id);
			 var $post_row = $('#post-' + $post_id);
 
			 // get the data
			 var $page_visibility    = $('.column-page_visibility', $post_row).html();
			 console.log($page_visibility);
			 var $page_redirect      = $('.column-page_redirect', $post_row).html();
			 var $page_password      = $('.column-page_password', $post_row).html();
 
			 // populate the data
			 $('select[name="page_visibility"]', $edit_row).val($page_visibility);
			 $('input[name="page_redirect"]', $edit_row).val($page_redirect);
			 $('input[name="page_password"]', $edit_row).val($page_password);
		 }
	 };
 
	 $(document).on('click', '#bulk_edit', function() {
		 // define the bulk edit row
		 var $bulk_row = $('#bulk-edit');
 
		 // get the selected post ids that are being edited
		 var $post_ids = new Array();
		 $bulk_row.find('#bulk-titles-list').children().each( function() {
			 $post_ids.push($(this).children('button').eq(0).attr('id').replace('_', ''));
		 });

		 console.log($post_ids);
 
		 // get the data
		 var $page_visibility = $bulk_row.find('select[name="page_visibility"]').val();
		 var $page_redirect = $bulk_row.find('input[name="page_redirect"]').val();
		 var $page_password = $bulk_row.find('input[name="page_password"]').val();
 
		 // save the data
		 $.ajax({
			 url: ajaxurl, // this is a variable that WordPress has already defined for us
			 type: 'POST',
			 async: false,
			 cache: false,
			 data: {
				 action: 'save_bulk_edit_page', // this is the name of our WP AJAX function that we'll set up next
				 post_ids: $post_ids, // and these are the 2 parameters we're passing to our function
				 page_visibility: $page_visibility,
				 page_redirect: $page_redirect,
				 page_password: $page_password
			 }
		 });
	 });
})(jQuery);
