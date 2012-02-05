<div class="cf-elm-block" id="cfpf-format-video-fields" style="display: none;">
	<label for="cfpf-format-video-embed"><?php _e('Video URL (oEmbed) or Embed Code', 'cf-post-format'); ?></label>
	<textarea name="_format_video_embed" id="cfpf-format-video-embed" tabindex="1"><?php echo esc_textarea(get_post_meta($post->ID, '_format_video_embed', true)); ?></textarea>
</div>	