// JavaScript Document
jQuery(document).ready(function() {
	if ( ! jQuery('.custom_upload_video_thumb').val() )
	{
		jQuery('.remove_image_button').hide();
	}

	// Uploading files
	var file_frame;

	jQuery(document).on( 'click', '.custom_upload_video_thumb_button_new', function( event ){

		event.preventDefault();
		
		formfield = jQuery(this).siblings('.custom_upload_video_thumb');
		preview = jQuery(this).siblings('.custom_preview_image_video_thumb');
		
		// If the media frame already exists, reopen it.
		if ( file_frame ) {
			file_frame.open();
			return;
		}

		// Create the media frame.
		file_frame = wp.media.frames.downloadable_file = wp.media({
			title: 'Choose image',
			button: {
				text: 'Use image',
			},
			multiple: false
		});

		// When an image is selected, run a callback.
		file_frame.on( 'select', function() {
			attachment = file_frame.state().get('selection').first().toJSON();

			formfield.val( attachment.id );
			preview.attr('src', attachment.url );

			jQuery('.remove_image_button').show();
			
			jQuery(".video_rep").trigger("change");
		});

		// Finally, open the modal.
		file_frame.open();
		
		
		
	});

	jQuery(document).on( 'click', '.remove_image_button', function( event ){
		
		formfield = jQuery(this).siblings('.custom_upload_video_thumb');
		preview = jQuery(this).siblings('.custom_preview_image_video_thumb');
	
		formfield.val('');
		preview.attr('src', '' );
		jQuery(this).siblings('.remove_image_button').hide();
		
		jQuery(".video_rep").trigger("change");
		
		return false;
	});
	
	
	//VIDEO GALLERY TAB
	jQuery(document).on( 'click', '.custom-repeatable-add-video', function( event ){
				
		field = jQuery(this).closest("div").find("ul.custom_repeatable li:last").clone(true);
		fieldLocation = jQuery(this).closest("div").find("ul.custom_repeatable li:last");
		jQuery("img", field).attr("src","");
		
		jQuery(field).find("input:checkbox").prop("checked",false);
		
		jQuery("input:not(:button)", field).val("").attr("name", function(index, name) {
			return name.replace(/[0-9]+(?!.*[0-9])/, function(fullMatch, n) {
				return parseInt(fullMatch, 10) + 1;
			});
		})
		
		jQuery(field).find("input:checkbox").val("on");
		
		jQuery("input#custom_video", field).val("").attr("name", function(index, name) {
			return name.replace(/[0-9]+(?!.*[0-9])/, function(fullMatch, n) {
				return parseInt(fullMatch, 10) + 1;
			});
		})
		
		jQuery("input#custom_video_thumb", field).val("").attr("name", function(index, name) {	
			return name.replace(/[0-9]+(?!.*[0-9])/, function(fullMatch, n) {
				return parseInt(fullMatch, 10) + 1;
			});
		})
		
		
		field.insertAfter(fieldLocation, jQuery(this).closest("div"));
		return false;
	});
	
	jQuery(document).on( 'click', '.custom_clear_video_thumbnail_button', function( event ){	
		jQuery(this).parent().find(".custom_upload_video_thumb").val("");
		jQuery(this).parent().parent().parent().find(".custom_preview_video_thumb").attr("src", "");
		return false;
	});
		
	jQuery(document).on( 'click', '.repeatable-remove-video', function( event ){
		if(jQuery(this).parent().parent().parent().children().length>1)
			jQuery(this).parent().parent().remove();
		else
			confirm('At least one element is required');
		
		jQuery(".video_rep").trigger("change");
		return false;
	});
	
	
	
});