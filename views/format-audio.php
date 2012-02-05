<div class="cf-elm-block" id="cfpf-format-audio-fields" style="display: none;">
	<label for="cfpf-format-audio-embed"><?php _e('Audio URL (oEmbed) or Embed Code', 'cf-post-format'); ?></label>
	<textarea name="_format_audio_embed" id="cfpf-format-audio-embed" tabindex="1"><?php echo esc_textarea(get_post_meta($post->ID, '_format_audio_embed', true)); ?></textarea>
</div>	