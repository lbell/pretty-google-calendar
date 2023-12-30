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
  // 3rd Party
  // wp_register_style('fullcalendar', 'https://cdn.jsdelivr.net/npm/fullcalendar@5/main.min.css', null, PGCAL_VER);
  // wp_register_style('fullcalendar', PGCAL_URL . 'public/lib/fullcalendar/main.min.css', null, PGCAL_VER);
  // wp_register_style('tippy_light', 'https://unpkg.com/tippy.js@6/themes/light.css', null, PGCAL_VER);
  wp_register_style('tippy_light', PGCAL_URL . 'public/lib/tippy/light.css', null, PGCAL_VER);

  // Local
  wp_register_style('pgcal_css', PGCAL_URL . 'public/css/pgcal.css', null, PGCAL_VER);
  wp_register_style('pgcal_tippy', PGCAL_URL . 'public/css/tippy.css', null, PGCAL_VER);
}


/**
 * Register front-end scripts
 */
function pgcal_register_frontend_js() {
  // 3rd Party
  // wp_register_script('fullcalendar', 'https://cdn.jsdelivr.net/npm/fullcalendar@5/main.js', null, PGCAL_VER, true); 
  // wp_register_script('popper', 'https://unpkg.com/@popperjs/core@2', null, PGCAL_VER, true);
  // wp_register_script('tippy', 'https://unpkg.com/tippy.js@6', null, PGCAL_VER, true);

  wp_register_script('fullcalendar', PGCAL_URL . 'public/lib/fullcalendar/index.global.min.js', null, PGCAL_VER, true);
  wp_register_script('fc_googlecalendar', PGCAL_URL . 'public/lib/fullcalendar/google-calendar/index.global.min.js', null, PGCAL_VER, true);
  wp_register_script('fc_locales', PGCAL_URL . 'public/lib/fullcalendar/locales/locales-all.global.min.js', null, PGCAL_VER, true);

  wp_register_script('popper', PGCAL_URL . 'public/lib/popper/popper.min.js', null, PGCAL_VER, true);
  wp_register_script('tippy', PGCAL_URL . 'public/lib/tippy/tippy.min.js', null, PGCAL_VER, true);

  wp_register_script('pgcal_helpers', PGCAL_URL . 'public/js/helpers.js', ['wp-i18n'], PGCAL_VER, true);
  wp_register_script('pgcal_loader', PGCAL_URL . 'public/js/pgcal.js', null, PGCAL_VER, true);
  wp_register_script('pgcal_tippy', PGCAL_URL . 'public/js/tippy.js', null, PGCAL_VER, true);

  wp_set_script_translations('pgcal_helpers', 'pgcal');
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


/**
 * Register Ajax handler to privately pass GCAL api key
 */
function pgcal_ajax_handler() {
  $default = array();
  $globalSettings = get_option('pgcal_settings', $default);

  // Send the data as a JSON response.
  wp_send_json($globalSettings);
}

// Hook the AJAX handler to WordPress.
add_action('wp_ajax_pgcal_ajax_action', 'pgcal_ajax_handler');
add_action('wp_ajax_nopriv_pgcal_ajax_action', 'pgcal_ajax_handler');
