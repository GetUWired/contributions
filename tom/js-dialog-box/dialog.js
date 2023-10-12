jQuery( document ).ready( function () {

    //Location Edit/Update submission handler
    var _doing_primary_dialog_ajax = false;

    jQuery( 'body' ).on( 'click', '[data-primary_dialog]', function(e) {
        e.preventDefault();

        if(_doing_primary_dialog_ajax){
            return false;
        }

        let endpoint = jQuery(this).data('primary_dialog');
        let id = jQuery(this).data('id');
        let org_id = jQuery(this).data('org_id');
        let paged = jQuery(this).data('paged');

        // console.log(endpoint);
        _doing_primary_dialog_ajax = true;
        jQuery.ajax({
            method: "POST",
            url: DIALOG_CONTENT.root + `myendpoint/v1/${endpoint}`,
            dataType: "JSON",
            data: {
                'id' : id,
                'org_id' : org_id,
                'paged' : paged,
            },
            beforeSend: function ( xhr ) {
                xhr.setRequestHeader( 'X-WP-Nonce', DIALOG_CONTENT.nonce );
            },
            success : function( response ) {
                jQuery('#primary_modal .inner-content').html(response.html);           
                jQuery('#primary_modal').fadeIn(100);
                _doing_primary_dialog_ajax = false;
            },
            fail : function( response ) {
                console.log( response );
                triggerNotification('Error', "Something Went Wrong", 'nfc-top-right', 'error' );
                _doing_primary_dialog_ajax = false;
            }
 
        });
 
    });

    var _doing_secondary_dialog_ajax = false;
    jQuery( 'body' ).on( 'click', '[data-secondary_dialog]', function(e) {
        e.preventDefault();

        if(_doing_secondary_dialog_ajax){
            return false;
        }

        let endpoint = jQuery(this).data('secondary_dialog');
        let id = jQuery(this).data('id');
        let search_option = jQuery(this).data('search_option');


        // console.log(endpoint);
        _doing_secondary_dialog_ajax = true;
        jQuery.ajax({
            method: "POST",
            url: DIALOG_CONTENT.root + `myendpoint/v1/${endpoint}`,
            dataType: "JSON",
            data: {
                'post_id' : id,
                'search_option' : search_option,
            },
            beforeSend: function ( xhr ) {
                xhr.setRequestHeader( 'X-WP-Nonce', DIALOG_CONTENT.nonce );
            },
            success : function( response ) {
                jQuery('#secondary_modal .inner-content').html(response.html);
                jQuery('#secondary_modal').fadeIn(100); 
                _doing_secondary_dialog_ajax = false;                     
            },
            fail : function( response ) {
                console.log( response );
                triggerNotification('Error', "Something Went Wrong", 'nfc-top-right', 'error' );
                _doing_secondary_dialog_ajax = false;
            }
 
        });
 
    });

    jQuery('body').on('click', '.close-button', function(e){
        e.preventDefault();
        jQuery(this).closest('.modal').fadeOut(100);
        // jQuery('.mce-toolbar-grp').remove();           
    });

    var _doing_confirmation_ajax = false;
    jQuery('body').on('click', '[data-confirm_dialog]', function(e){
        e.preventDefault();

        if(_doing_confirmation_ajax){
            return false;
        }

        let endpoint = jQuery(this).data('confirm_dialog');
        let id = jQuery(this).data('id');
        let org_id = jQuery(this).data('org_id');

        // console.log(endpoint);
        _doing_confirmation_ajax = true;
        jQuery.ajax({
            method: "POST",
            url: DIALOG_CONTENT.root + `myendpoint/v1/${endpoint}`,
            dataType: "JSON",
            data: {
                'id' : id,
                'org_id' : org_id,
            },
            beforeSend: function ( xhr ) {
                xhr.setRequestHeader( 'X-WP-Nonce', DIALOG_CONTENT.nonce );
            },
            success : function( response ) {
                jQuery('#confirm_dialog .inner-content').html(response.html); 
                          
                jQuery('#confirm_dialog').fadeIn(100);           
                jQuery('#confirm_dialog').css('display', 'flex');
                _doing_confirmation_ajax = false;             
            },
            fail : function( response ) {
                // console.log( response );
                triggerNotification('Error', "Something Went Wrong", 'nfc-top-right', 'error' );
                 _doing_confirmation_ajax = false;
            }
 
        });

    });

    jQuery('body').on('click', '.cancel-close', function(e){
        e.preventDefault();
        jQuery(this).closest('.confirm-modal').fadeOut(100);
    });

} );

/*Example

<h2><a data-primary_dialog="location" data-id="<?= $post->ID ?>" data-log="page_view" href="<?= get_permalink($post->ID) ?>"><?= get_the_title($post->ID) ?></a></h2>

	<div id="primary_modal" class="modal">
		<div class="button-wrapper">
			<span class="close-button material-symbols-outlined">cancel</span>
		</div>
		<div class="modal-content">
			<section class="modal-header">
			</section>
			<section class="inner-content">
			<!-- Content inserted -->
			</section>
		</div>
	</div>
	<div id="secondary_modal" class="modal">
		<div class="button-wrapper">
			<span class="close-button material-symbols-outlined">cancel</span>
		</div>
		<div class="modal-content">
			<section class="modal-header">
			</section>
			<section class="inner-content">
			<!-- Content inserted -->
			</section>
		</div>
	</div>
	<div id="confirm_dialog" class="confirm-modal">
		<section class="inner-content">
		<!-- Content inserted -->
		</section>
	</div>

*/