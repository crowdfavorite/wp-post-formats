<?php

$gallery_type = cfpf_post_gallery_type();

?>
<div id="cfpf-format-gallery-preview" class="cf-elm-block cf-elm-block-image" style="display: none;">

	<label><span><?php _e('Gallery Images', 'cf-post-format'); ?></span></label>

	<div class="cf-elm-container cfpf-gallery-options">
		<p>
			<input type="radio" name="_format_gallery_type" value="shortcode" <?php checked($gallery_type, 'shortcode' ); ?> id="cfpf-format-gallery-type-shortcode"  />
			<label for="cfpf-format-gallery-type-shortcode"><?php _e('Shortcode', 'cf-post-format'); ?></label>
			<input type="text" name="_format_gallery_shortcode" value="<?php echo esc_attr(get_post_meta($post->ID, '_format_gallery_shortcode', true)); ?>" id="cfpf-format-gallery-shortcode" />
		</p>
		<p>
			<input type="radio" name="_format_gallery_type" value="attached-images" <?php checked($gallery_type, 'attached-images' ); ?> id="cfpf-format-gallery-type-attached" />
			<label for="cfpf-format-gallery-type-attached"><?php _e('Images uploaded to this post', 'cf-post-format'); ?></label>
		</p>


<?php

// running this in the view so it can be used by multiple functions

$attachments = get_posts(array(
	'post_type' => 'attachment',
	'numberposts' => -1,
	'post_status' => null,
	'post_parent' => $post->ID,
	'order' => 'ASC',
	'orderby' => 'menu_order ID',
));

if ($attachments) {

	if (is_ssl()) {
		add_filter('wp_get_attachment_image_attributes', 'cfpf_ssl_gallery_preview', 10, 2);
	}

	echo '<ul class="gallery clearfix">';
	foreach ($attachments as $attachment) {
		echo '<li>'.wp_get_attachment_image($attachment->ID, 'thumbnail').'</li>';
	}
	echo '</ul>';
}

?>
		<p class="none" style="float: none; clear: both;">
			<a href="#" class="button"><?php _e('Upload Images', 'cf-post-format'); ?></a>
		</p>
	</div>
</div>
