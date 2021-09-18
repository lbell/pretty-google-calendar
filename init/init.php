<?php

/**
 * Register shortcode(s)
 *
 * @return void
 */
function pgcal_register_shortcodes() {
	add_shortcode('pretty_google_calendar', 'pgcal_shortcode');
}


/**
 * Register front-end styles
 */
function pgcal_register_frontend_css() {
	wp_register_style('pgcal_css', PGCAL_URL . 'public/css/pgcal.css', null, PGCAL_VER);
	wp_register_style('pgcal_tippy', PGCAL_URL . 'public/css/tippy.css', null, PGCAL_VER);
	wp_register_style('fullcalendar', 'https://cdn.jsdelivr.net/npm/fullcalendar@5/main.min.css', null, null);
	wp_register_style('tippy_light', 'https://unpkg.com/tippy.js@6/themes/light.css', null, null);
}


/**
 * Register front-end scripts
 */
function pgcal_register_frontend_js() {
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
	pgcal_register_frontend_css();
	pgcal_register_frontend_js();
	// pgcal_add_settings_page();
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
