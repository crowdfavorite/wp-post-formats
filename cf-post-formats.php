<?php
/*
Plugin Name: CF Post Formats
Plugin URI: http://crowdfavorite.com
Description: Custom post format admin UI
Version: 1.1
Author: crowdfavorite
Author URI: http://crowdfavorite.com 
*/

/**
 * Copyright (c) 2011-2012 Crowd Favorite, Ltd. All rights reserved.
 *
 * Released under the GPL license
 * http://www.opensource.org/licenses/gpl-license.php
 *
 * This is an add-on for WordPress
 * http://wordpress.org/
 *
 * **********************************************************************
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * **********************************************************************
 */

if (!defined('CFPF_VERSION')) {

define('CFPF_VERSION', '1.1');

function cfpf_base_url() {
	return trailingslashit(apply_filters('cfpf_base_url', plugins_url('', __FILE__)));
}

function cfpf_admin_init() {
	$post_formats = get_theme_support('post-formats');
	if (!empty($post_formats[0]) && is_array($post_formats[0])) {
		if (in_array('link', $post_formats[0])) {
			add_action('save_post', 'cfpf_format_link_save_post');
		}
		if (in_array('status', $post_formats[0])) {
			add_action('save_post', 'cfpf_format_status_save_post', 10, 2);
		}
		if (in_array('quote', $post_formats[0])) {
			add_action('save_post', 'cfpf_format_quote_save_post', 10, 2);
		}
		if (in_array('video', $post_formats[0])) {
			add_action('save_post', 'cfpf_format_video_save_post');
		}
		if (in_array('audio', $post_formats[0])) {
			add_action('save_post', 'cfpf_format_audio_save_post');
		}
	}
}
add_action('admin_init', 'cfpf_admin_init');

// we aren't really adding meta boxes here,
// but this gives us the info we need to get our stuff in.
function cfpf_add_meta_boxes($post_type) {
	if (post_type_supports($post_type, 'post-formats') && current_theme_supports('post-formats')) {
		// assets
		wp_enqueue_script('cf-post-formats', cfpf_base_url().'js/admin.js', array('jquery'), CFPF_VERSION);
		wp_enqueue_style('cf-post-formats', cfpf_base_url().'css/admin.css', array(), CFPF_VERSION, 'screen');

		wp_localize_script(
			'cf-post-formats', 
			'cfpf_post_format', 
			array(
				'loading' => __('Loading...', 'cf-post-formats'),
				'wpspin_light' => admin_url('images/wpspin_light.gif')
			)
		);
		
		add_action('edit_form_after_title', 'cfpf_post_admin_setup');
	}
}
add_action('add_meta_boxes', 'cfpf_add_meta_boxes');

/**
 * Show the post format navigation tabs
 * A lot of cues are taken from the `post_format_meta_box`
 *
 * @return void
 */
function cfpf_post_admin_setup() {
	$post_formats = get_theme_support('post-formats');
	if (!empty($post_formats[0]) && is_array($post_formats[0])) {
		global $post;
		$current_post_format = get_post_format($post->ID);

		// support the possibility of people having hacked in custom 
		// post-formats or that this theme doesn't natively support
		// the post-format in the current post - a tab will be added
		// for this format but the default WP post UI will be shown ~sp
		if (!empty($current_post_format) && !in_array($current_post_format, $post_formats[0])) {
			array_push($post_formats[0], get_post_format_string($current_post_format));
		}
		array_unshift($post_formats[0], 'standard');
		$post_formats = $post_formats[0];

		include('views/tabs.php');

		$format_views = array(
			'link',
			'quote',
			'video',
			'gallery',
			'audio',
		);
		foreach ($format_views as $format) {
			if (in_array($format, $post_formats)) {
				include('views/format-'.$format.'.php');
			}
		}
	}
}

function cfpf_format_link_save_post($post_id) {
	if (!defined('XMLRPC_REQUEST') && isset($_POST['_format_link_url'])) {
		update_post_meta($post_id, '_format_link_url', $_POST['_format_link_url']);
	}
}
// action added in cfpf_admin_init()

function cfpf_format_auto_title_post($post_id, $post) {
	remove_action('save_post', 'cfpf_format_status_save_post', 10, 2);
	remove_action('save_post', 'cfpf_format_quote_save_post', 10, 2);

	$content = trim(strip_tags($post->post_content));
	$title = substr($content, 0, 50);
	if (strlen($content) > 50) {
		$title .= '...';
	}
	$title = apply_filters('cfpf_format_auto_title', $title, $post);
	wp_update_post(array(
		'ID' => $post_id,
		'post_title' => $title
	));

	add_action('save_post', 'cfpf_format_status_save_post', 10, 2);
	add_action('save_post', 'cfpf_format_quote_save_post', 10, 2);
}

function cfpf_format_status_save_post($post_id, $post) {
	if (has_post_format('status', $post)) {
		cfpf_format_auto_title_post($post_id, $post);
	}
}
// action added in cfpf_admin_init()

function cfpf_format_quote_save_post($post_id, $post) {
	if (!defined('XMLRPC_REQUEST')) {
		$keys = array(
			'_format_quote_source_name',
			'_format_quote_source_url',
		);
		foreach ($keys as $key) {
			if (isset($_POST[$key])) {
				update_post_meta($post_id, $key, $_POST[$key]);
			}
		}
	}
	if (has_post_format('quote', $post)) {
		cfpf_format_auto_title_post($post_id, $post);
	}
}
// action added in cfpf_admin_init()

function cfpf_format_video_save_post($post_id) {
	if (!defined('XMLRPC_REQUEST') && isset($_POST['_format_video_embed'])) {
		update_post_meta($post_id, '_format_video_embed', $_POST['_format_video_embed']);
	}
}
// action added in cfpf_admin_init()

function cfpf_format_audio_save_post($post_id) {
	if (!defined('XMLRPC_REQUEST') && isset($_POST['_format_audio_embed'])) {
		update_post_meta($post_id, '_format_audio_embed', $_POST['_format_audio_embed']);
	}
}
// action added in cfpf_admin_init()

function cfpf_gallery_preview() {
	if (empty($_POST['id']) || !($post_id = intval($_POST['id']))) {
		exit;
	}
	global $post;
	$post->ID = $post_id;
	ob_start();
	include('views/format-gallery.php');
	$html = ob_get_clean();
	header('Content-type: text/javascript');
	echo json_encode(compact('html'));
	exit;
}
add_action('wp_ajax_cfpf_gallery_preview', 'cfpf_gallery_preview');

function cfpf_post_has_gallery($post_id = null) {
	if (empty($post_id)) {
		$post_id = get_the_ID();
	}
	$images = new WP_Query(array(
		'post_parent' => $post_id,
		'post_type' => 'attachment',
		'post_status' => 'inherit',
		'posts_per_page' => 1, // -1 to show all
		'post_mime_type' => 'image%',
		'orderby' => 'menu_order',
		'order' => 'ASC'
	));
	return (bool) $images->post_count;
}

function cfpf_pre_ping_post_links($post_links, $pung, $post_id = null) {
	// return if we don't get a post ID (pre WP 3.4)
	if (empty($post_id)) {
		return;
	}
	$url = get_post_meta($post_id, '_format_link_url', true);
	if (!empty($url) && !in_array($url, $pung) && !in_array($url, $post_links)) {
		$post_links[] = $url;
	}
}
add_filter('pre_ping', 'cfpf_pre_ping_post_links', 10, 3);

// For integration with Social plugin (strips {title} from broadcast format on status posts)
function cfpf_social_broadcast_format($format, $post) {
	if (get_post_format($post) == 'status') {
		$format = trim(str_replace(
			array(
				'{title}:',
				'{title} -',
				'{title}',
			),
			'',
			$format
		));
	}
	return $format;
}
add_filter('social_broadcast_format', 'cfpf_social_broadcast_format', 10, 2);

} // end defined check
