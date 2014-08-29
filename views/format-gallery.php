<div id="cfpf-format-gallery-preview" class="cf-elm-block cf-elm-block-image" style="display: none;">

	<label><span><?php _e('Gallery Images', 'cf-post-format'); ?></span></label>
	
	<div class="cf-elm-container">
		<p>
			<input type="radio" name="_format_gallery_type" id="shortcode" value="shortcode" <?php checked( get_post_meta($post->ID, '_format_gallery_type', true), 'shortcode' ); ?>" id="cfpf-format-gallery-checked-shortcode"  /> Shortcode
			<span style="display:inline-block;"><input type="text" name="_format_gallery_preview_shortcode" value="<?php echo esc_attr(get_post_meta($post->ID, '_format_gallery_preview_shortcode', true)); ?>" id="cfpf-format-gallery-preview-shortcode" /></span>
		</p>
		<p>
			<input type="radio" name="_format_gallery_type" value="attached-images" id="attached-images" <?php checked( get_post_meta($post->ID, '_format_gallery_type', true), 'attached-images' ); ?>" id="cfpf-format-gallery-checked-allimages"  /> All Images
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

	echo '<ul class="gallery">';
	foreach ($attachments as $attachment) {
		echo '<li>'.wp_get_attachment_image($attachment->ID, 'thumbnail').'</li>';
	}
	echo '</ul>';
}

?>
<p class="none"><a href="#" class="button"><?php _e('Upload Images', 'cf-post-format'); ?></a></p>
	</div>
</div>
