jQuery(function($) {
	var CF = CF || {};
	
	CF.postFormats = function($) {
		return {
			switchTab: function(clicked) {
				var $this = $(clicked),
					$tab = $this.closest('li');

				if (!$this.hasClass('current')) {
					$this.addClass('current');
					$tab.siblings().find('a').removeClass('current');
					this.switchWPFormat($this.attr('href'));
				}
			},
			
			switchWPFormat: function(format_hash) {
				var func;
				$(format_hash).trigger('click');
				if ( format_hash === '#post-format-0' ) {
					CF.postFormats.standard();
				}
				else {
					func = CF.postFormats[ format_hash.replace('#post-format-', '') ] ;
					if ( typeof func !== 'undefined' ) {
						func();
					}
					else {
						CF.postFormats.standard();
					}
				}
			},

			restoreFeaturedImage: function() {
				$('#postimagediv-placeholder').replaceWith($('#postimagediv'));
			},

			standard: function() {
				$('#cfpf-format-link-url, #cfpf-format-quote-fields, #cfpf-format-video-fields, #cfpf-format-gallery-preview').hide();
				$('#titlewrap').show();
				CF.postFormats.restoreFeaturedImage();
			},
			
			status: function() {
				$('#titlewrap, #cfpf-format-link-url, #cfpf-format-quote-fields, #cfpf-format-video-fields, #cfpf-format-gallery-preview').hide();
				CF.postFormats.restoreFeaturedImage();
				$('#content:visible').focus();
			},

			link: function() {
				$('#cfpf-format-quote-field, #cfpf-format-video-fields, #cfpf-format-gallery-preview').hide();
				$('#titlewrap, #cfpf-format-link-url').show();
				CF.postFormats.restoreFeaturedImage();
			},
			
			image: function() {
				$('#cfpf-format-link-url, #cfpf-format-quote-fields, #cfpf-format-video-fields, #cfpf-format-gallery-preview').hide();
				$('#titlewrap').show();
				$('#postimagediv').after('<div id="postimagediv-placeholder"></div>').insertAfter('#titlediv');
			},

			gallery: function() {
				$('#cfpf-format-link-url, #cfpf-format-quote-fields, #cfpf-format-video-fields').hide();
				$('#titlewrap, #cfpf-format-gallery-preview').show();
				CF.postFormats.restoreFeaturedImage();
			},

			video: function() {
				$('#cfpf-format-link-url, #cfpf-format-quote-fields, #cfpf-format-gallery-preview').hide();
				$('#titlewrap, #cfpf-format-video-fields').show();
				CF.postFormats.restoreFeaturedImage();
			},

			quote: function() {
				$('#titlewrap, #cfpf-format-link-url, #cfpf-format-video-fields, #cfpf-format-gallery-preview').hide();
				$('#cfpf-format-quote-fields').show().find(':input:first').focus();
				CF.postFormats.restoreFeaturedImage();
			}
		};
	}(jQuery);
	
	// move tabs in to place
	$('#cf-post-format-tabs').insertBefore($('form#post')).show();
	$('#cfpf-format-link-url').insertAfter($('#titlediv'));
	$('#cfpf-format-video-fields').insertAfter($('#titlediv'));
	$('#cfpf-format-gallery-preview').find('dt a').each(function() {
		$(this).replaceWith($(this.childNodes)); // remove links
	}).end().insertAfter($('#titlediv'));
	$('#cfpf-format-quote-fields').insertAfter($('#titlediv'));
	
	// tab switch
	$('#cf-post-format-tabs').delegate('a', 'click', function(e) {
		CF.postFormats.switchTab(this);
		e.stopPropagation();
		e.preventDefault();
	}).find('.current').each(function() {
		CF.postFormats.switchWPFormat($(this).attr('href'));
	});
	
	// refresh gallery on lightbox close
	$('#TB_window').live('unload', function() {
		if (!$('#cfpf-format-gallery-preview').is(':visible')) {
			return;
		}
		var $preview = $('#cfpf-format-gallery-preview');
// spinner
		$preview.find('.cp-elm-container').html('<p><img src="' + cfpf_post_format.wpspin_light + '" alt="' + cfpf_post_format.loading + '" /></p>');
// AJAX call for gallery snippet
		$.post(
			ajaxurl,
			{
				'action': 'cfpf_gallery_preview',
				'id': $('#post_ID').val()
			},
			function(response) {
// replace
				$preview.replaceWith(response.html);
				$('#cfpf-format-gallery-preview').find('dt a').each(function() {
					$(this).replaceWith($(this.childNodes)); // remove links
				}).end().show();
			},
			'json'
		);
	});
	
	$('#cfpf-format-gallery-preview').delegate('.none a', 'click', function(e) {
		$('#add_image, #content-add_media').click();
		e.preventDefault();
	});
	
});