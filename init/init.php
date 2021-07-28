<?php

/**
 * Register submenu item for settings / docs
 * @return void 
 */
function fgcal_add_settings_page() {
	add_submenu_page(
		'edit.php?post_type=hy_directory', //$parent_slug
		'Directory Help',               //$page_title
		'Directory Help',               //$menu_title
		'manage_options',               //$capability
		'directory_help',               //$menu_slug
		'fgcal_render_settings_page'  //$function
	);
}


/**
 * Register shortcode(s)
 *
 * @return void
 */
function fgcal_register_shortcodes() {
	add_shortcode('full_gcal', 'fgcal_shortcode');
}


/**
 * Register thumbnail sizes
 *
 * @return void
 */
function fgcal_register_thumbnail() {
	add_theme_support('post-thumbnails');
	if (function_exists('add_image_size')) {
		add_image_size('fgcal-thumb-100', 100, 100, TRUE);
		add_image_size('fgcal-medium-300', 300, 300, TRUE);
	}
}


/**
 * Register front-end styles
 */
function fgcal_register_frontend_css() {
	wp_register_style('fgcal_css', FGCAL_URL . 'public/css/fgcal.css');
	wp_register_style('fgcal_tooltip', FGCAL_URL . 'public/css/tooltip.css');
	wp_register_style('fullcalendar', 'https://cdn.jsdelivr.net/npm/fullcalendar@5/main.css'); // TODO: main.min.css
}


/**
 * Register front-end scripts
 */
function fgcal_register_frontend_js() {
	// wp_register_script('fc_locales', 'https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/4.2.0/core/locales-all.js', null, null, true);
	wp_register_script('fullcalendar', 'https://cdn.jsdelivr.net/npm/fullcalendar@5/main.js', null, null, true); // TODO: main.min.js

	wp_register_script('popper', 'https://unpkg.com/popper.js/dist/umd/popper.min.js', null, null, true);
	wp_register_script('tooltip', 'https://unpkg.com/tooltip.js/dist/umd/tooltip.min.js', null, null, true);

	wp_register_script('fgcal_helpers', FGCAL_URL . '/public/js/helpers.js', null, null, true);
	wp_register_script('fgcal_loader', FGCAL_URL . '/public/js/fgcal.js', null, null, true);
	wp_register_script('fgcal_tooltip', FGCAL_URL . '/public/js/tooltip.js', null, null, true);
}


/**
 * Register all the things on init
 *
 * @return void
 */
function fgcal_init() {
	fgcal_register_shortcodes();
	fgcal_register_thumbnail();
	fgcal_register_frontend_css();
	fgcal_register_frontend_js();
	fgcal_add_settings_page();
	// wp_enqueue_style('list-card-css');
}
add_action('init', 'fgcal_init', 0);


/**
 * Register admin styles
 */
function fgcal_register_admin_css() {
	wp_register_style('fgcal-admin-css', FGCAL_URL . 'public/css/fgcal-admin.css');
	wp_enqueue_style('fgcal-admin-css');
}


/**
 * Register admin scripts and styles
 */
function fgcal_admin_inits() {
	fgcal_register_admin_css();
}
add_action('admin_init', 'fgcal_admin_inits');

if (is_admin())
	$wpfgc_settings_page = new fgcalSettings();
