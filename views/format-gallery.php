<div id="cfpf-format-gallery-preview" class="cf-elm-block cf-elm-block-image" style="display: none;">
	<label><span><?php _e('Gallery Images', 'cf-post-format'); ?></span></label>
	<div class="cf-elm-container">

<?php

// running this in the view so it can be used by multiple functions
$attachments = get_post_galleries( $post->ID, false  );


if( !empty( $attachments ) ){

	// iterate through each files
	foreach( $attachments as $attachment ){
	
		if( !empty( $attachment['ids'] ) ){
		
			// go get the ids
			$attachment_ids = $attachment['ids'];
			
			// break the ids into array so we can
			// iterate on each element one by one
			$attachment_id_collection = explode( ',' , $attachment_ids );
			
			$attachment_id_collection_count = count( $attachment_id_collection );
			
			// maybe check if its empty?
			if( 0 == $attachment_id_collection_count || !empty ( $attachment_id_collection ) ){
				echo '<ul class="gallery">';
					for( $i = 0; $i < $attachment_id_collection_count; $i ++ ){
						echo '<li>' . wp_get_attachment_image( $attachment_id_collection[$i] ) . '</li>';
					}
				echo '</ul>';	
			}
		}
	}
}
?>
<p class="none"><a href="#" class="button"><?php _e('Upload Images', 'cf-post-format'); ?></a></p>
	</div>
</div>
