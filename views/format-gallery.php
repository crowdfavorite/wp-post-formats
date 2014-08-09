<div id="cfpf-format-gallery-preview" class="cf-elm-block cf-elm-block-image" style="display: none;">
	<label><span><?php _e('Gallery Images', 'cf-post-format'); ?></span></label>
	<div class="cf-elm-container">

<?php

// running this in the view so it can be used by multiple functions

$galleries = get_post_galleries($post->ID, false);
$attachments = [];
foreach($galleries as $gallery) {
	$attachments=$attachments + explode(',',$gallery['ids']);
}
$attachments = array_unique($attachments);
if (!empty($attachments)) {
	echo '<ul class="gallery">';
	foreach ($attachments as $attachment) {
		echo '<li>'.wp_get_attachment_image($attachment, 'thumbnail').'</li>';
	}
	echo '</ul>';
}

?>
<p class="none"><a href="#" class="button"><?php _e('Upload Images', 'cf-post-format'); ?></a></p>
	</div>
</div>
