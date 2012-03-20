<div id="cfpf-format-quote-fields" style="display: none;">
	<div class="cf-elm-block">
		<label for="cfpf-format-quote-source-name"><?php _e('Source Name', 'cf-post-format'); ?></label>
		<input type="text" name="_format_quote_source_name" value="<?php echo esc_attr(get_post_meta($post->ID, '_format_quote_source_name', true)); ?>" id="cfpf-format-quote-source-name" tabindex="1" />
	</div>
	<div class="cf-elm-block">
		<label for="cfpf-format-quote-source-url"><?php _e('Source URL', 'cf-post-format'); ?></label>
		<input type="text" name="_format_quote_source_url" value="<?php echo esc_attr(get_post_meta($post->ID, '_format_quote_source_url', true)); ?>" id="cfpf-format-quote-source-url" tabindex="1" />
	</div>
</div>