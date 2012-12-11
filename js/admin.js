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
			
			switchWPFormat: function(formatHash) {
				$(formatHash).trigger('click');
				switch (formatHash) {
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
						CF.postFormats[formatHash.replace('#post-format-', '')]();
				}
				$(document).trigger('cf-post-formats-switch', formatHash);
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
				$('#cfpf-format-quote-fields, #cfpf-format-video-fields, #cfpf-format-audio-fields, #cfpf-format-gallery-preview').hide();
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
				$('#cfpf-format-link-url, #cfpf-format-quote-fields, #cfpf-format-gallery-preview, #cfpf-format-audio-fields').hide();
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
	
	$(document).trigger('cf-post-formats-init');
	
	// tab switch
	$('#cf-post-format-tabs a').live('click', function(e) {
		CF.postFormats.switchTab(this);
		e.stopPropagation();
		e.preventDefault();
	}).filter('.current').each(function() {
		CF.postFormats.switchWPFormat($(this).attr('href'));
	});

	// WordPress 3.5 compatibility
	// props: https://gist.github.com/4192094
	
	var postId = $('#post_ID').val();
	var gallery = wp.media.query({ uploadedTo: postId });

	// Run the query.
	// This returns a promise (like $.ajax) so you can do things when it completes.
	gallery.more();

	// Bind your events for when the contents of the gallery changes.
	gallery.on( 'add remove reset', function() {
		// Something changed, update your stuff.

		var $preview = $('#cfpf-format-gallery-preview');
// spinner
		$preview.find('.cf-elm-container').html('<p><img src="' + cfpf_post_format.wpspin_light + '" alt="' + cfpf_post_format.loading + '" /></p>');
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
// find it again
				$preview = $('#cfpf-format-gallery-preview')
				$preview.find('dt a').each(function() {
					$(this).replaceWith($(this.childNodes)); // remove links
				}).end();
// only show if tab is selected
				if ($('#cf-post-format-tabs a.current').attr('href').indexOf('#post-format-gallery') != -1) {
					$preview.show();
				}
			},
			'json'
		);

	}, gallery );

	
	$('#cfpf-format-gallery-preview .none a').live('click', function(e) {
		$('#wp-content-media-buttons .insert-media').mousedown().mouseup().click();
		e.preventDefault();
	});

});
