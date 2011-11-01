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
				$(format_hash).trigger('click');
				switch (format_hash) {
					case '#post-format-0':
					case '#post-format-aside':
					case '#post-format-chat':
						CF.postFormats.standard();
						break;
					case '#post-format-status':
					case '#post-format-link':
					case '#post-format-image':
					case '#post-format-gallery':
					case '#post-format-video':
					case '#post-format-quote':
					case '#post-format-audio':
						eval('CF.postFormats.' + format_hash.replace('#post-format-', '') + '();');
				}
			},

			standard: function() {
				$('#cfpf-format-link-url, #cfpf-format-quote-fields, #cfpf-format-video-fields, #cfpf-format-audio-fields, #cfpf-format-gallery-preview').hide();
				$('#titlewrap').show();
				$('#postimagediv-placeholder').replaceWith($('#postimagediv'));
			},
			
			status: function() {
				$('#titlewrap, #cfpf-format-link-url, #cfpf-format-quote-fields, #cfpf-format-video-fields, #cfpf-format-audio-fields, #cfpf-format-gallery-preview').hide();
				$('#postimagediv-placeholder').replaceWith($('#postimagediv'));
				$('#content:visible').focus();
			},

			link: function() {
				$('#cfpf-format-quote-field, #cfpf-format-video-fields, #cfpf-format-audio-fields, #cfpf-format-gallery-preview').hide();
				$('#titlewrap, #cfpf-format-link-url').show();
				$('#postimagediv-placeholder').replaceWith($('#postimagediv'));
			},
			
			image: function() {
				$('#cfpf-format-link-url, #cfpf-format-quote-fields, #cfpf-format-video-fields, #cfpf-format-audio-fields, #cfpf-format-gallery-preview').hide();
				$('#titlewrap').show();
				$('#postimagediv').after('<div id="postimagediv-placeholder"></div>').insertAfter('#titlediv');
			},

			gallery: function() {
				$('#cfpf-format-link-url, #cfpf-format-quote-fields, #cfpf-format-video-fields, #cfpf-format-audio-fields').hide();
				$('#titlewrap, #cfpf-format-gallery-preview').show();
				$('#postimagediv-placeholder').replaceWith($('#postimagediv'));
			},

			video: function() {
				$('#cfpf-format-link-url, #cfpf-format-quote-fields, #cfpf-format-gallery-preview').hide();
				$('#titlewrap, #cfpf-format-video-fields').show();
				$('#postimagediv-placeholder').replaceWith($('#postimagediv'));
			},

			quote: function() {
				$('#titlewrap, #cfpf-format-link-url, #cfpf-format-video-fields, #cfpf-format-audio-fields, #cfpf-format-gallery-preview').hide();
				$('#cfpf-format-quote-fields').show().find(':input:first').focus();
				$('#postimagediv-placeholder').replaceWith($('#postimagediv'));
			},

			audio: function() {
				$('#cfpf-format-link-url, #cfpf-format-quote-fields, #cfpf-format-video-fields, #cfpf-format-gallery-preview').hide();
				$('#titlewrap, #cfpf-format-audio-fields').show();
				$('#postimagediv-placeholder').replaceWith($('#postimagediv'));
			}

		};
	}(jQuery);
	
	// move tabs in to place
	$('#cf-post-format-tabs').insertBefore($('form#post')).show();
	$('#cfpf-format-link-url, #cfpf-format-video-fields, #cfpf-format-audio-fields').insertAfter($('#titlediv'));
	$('#cfpf-format-gallery-preview').find('dt a').each(function() {
		$(this).replaceWith($(this.childNodes)); // remove links
	}).end().insertAfter($('#titlediv'));
	$('#cfpf-format-quote-fields').insertAfter($('#titlediv'));
	
	// tab switch
	$('#cf-post-format-tabs a').live('click', function(e) {
		CF.postFormats.switchTab(this);
		e.stopPropagation();
		e.preventDefault();
	}).filter('.current').each(function() {
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
	
	$('#cfpf-format-gallery-preview .none a').live('click', function(e) {
		$('#add_image, #content-add_media').click();
		e.preventDefault();
	});
	
});