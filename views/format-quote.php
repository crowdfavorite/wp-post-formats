<div id="cfpf-format-quote-fields" style="display: none;">
	<div class="cp-elm-block">
		<label for="cfpf-format-quote-src-name"><?php _e('src Name', 'cf-post-format'); ?></label>
		<input type="text" name="_format_quote_src_name" value="<?php echo esc_attr(get_post_meta($post->ID, '_format_quote_src_name', true)); ?>" id="cfpf-format-quote-src-name" tabindex="1" />
	</div>
	<div class="cp-elm-block">
		<label for="cfpf-format-quote-src-url"><?php _e('src URL', 'cf-post-format'); ?></label>
		<input type="text" name="_format_quote_src_url" value="<?php echo esc_attr(get_post_meta($post->ID, '_format_quote_src_url', true)); ?>" id="cfpf-format-quote-src-url" tabindex="1" />
	</div>
</div>