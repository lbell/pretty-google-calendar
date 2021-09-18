<?php

/**
 * Register submenu item for settings / docs
 * @return void 
 */
function pgcal_add_settings_page() {
	add_submenu_page(
		'edit.php?post_type=hy_directory', //$parent_slug
		'Directory Help',               //$page_title
		'Directory Help',               //$menu_title
		'manage_options',               //$capability
		'directory_help',               //$menu_slug
		'pgcal_render_settings_page'  //$function
	);
}


/**
 * Register shortcode(s)
 *
 * @return void
 */
function pgcal_register_shortcodes() {
	add_shortcode('pretty_google_calendar', 'pgcal_shortcode');
}


/**
 * Register thumbnail sizes
 *
 * @return void
 */
function pgcal_register_thumbnail() {
	add_theme_support('post-thumbnails');
	if (function_exists('add_image_size')) {
		add_image_size('pgcal-thumb-100', 100, 100, TRUE);
		add_image_size('pgcal-medium-300', 300, 300, TRUE);
	}
}


/**
 * Register front-end styles
 */
function pgcal_register_frontend_css() {
	wp_register_style('pgcal_css', PGCAL_URL . 'public/css/pgcal.css', null, PGCAL_VER);
	wp_register_style('pgcal_tippy', PGCAL_URL . 'public/css/tippy.css', null, PGCAL_VER);
	wp_register_style('fullcalendar', 'https://cdn.jsdelivr.net/npm/fullcalendar@5/main.min.css', null, null); // TODO: main.min.css
	wp_register_style('tippy_light', 'https://unpkg.com/tippy.js@6/themes/light.css', null, null);
}


/**
 * Register front-end scripts
 */
function pgcal_register_frontend_js() {
	// wp_register_script('fc_locales', 'https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/4.2.0/core/locales-all.js', null, null, true);
	wp_register_script('fullcalendar', 'https://cdn.jsdelivr.net/npm/fullcalendar@5/main.js', null, null, true); // TODO: main.min.js

	wp_register_script('popper', 'https://unpkg.com/@popperjs/core@2', null, null, true);
	wp_register_script('tippy', 'https://unpkg.com/tippy.js@6', null, null, true);

	wp_register_script('pgcal_helpers', PGCAL_URL . 'public/js/helpers.js', null, PGCAL_VER, true);
	wp_register_script('pgcal_loader', PGCAL_URL . 'public/js/pgcal.js', null, PGCAL_VER, true);
	wp_register_script('pgcal_tippy', PGCAL_URL . 'public/js/tippy.js', null, PGCAL_VER, true);
}


/**
 * Register all the things on init
 *
 * @return void
 */
function pgcal_init() {
	pgcal_register_shortcodes();
	pgcal_register_thumbnail();
	pgcal_register_frontend_css();
	pgcal_register_frontend_js();
	pgcal_add_settings_page();
	// wp_enqueue_style('list-card-css');
}
add_action('init', 'pgcal_init', 0);


/**
 * Register admin styles
 */
function pgcal_register_admin_css() {
	wp_register_style('pgcal-admin-css', PGCAL_URL . 'public/css/pgcal-admin.css');
	wp_enqueue_style('pgcal-admin-css');
}


/**
 * Register admin scripts and styles
 */
function pgcal_admin_inits() {
	pgcal_register_admin_css();
}
add_action('admin_init', 'pgcal_admin_inits');

if (is_admin())
	$pgcal_settings_page = new pgcalSettings();
