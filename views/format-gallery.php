<div id="cfpf-format-gallery-preview" class="cf-elm-block cf-elm-block-image" style="display: none;">
	<label><span><?php _e('Gallery Images', 'cf-post-format'); ?></span></label>
	<div class="cf-elm-container">

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