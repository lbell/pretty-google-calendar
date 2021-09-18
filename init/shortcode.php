<?php

function pgcal_shortcode($atts) {
	$default = array();
	$pgcalSettings = get_option('pgcal_settings', $default);

	$args = shortcode_atts(
		array(
			'gcal'     => "",
		),
		$atts
	);

	// Add the attributes from the shortcode OVERRIDING the stored settings
	$pgcalSettings = array_merge($pgcalSettings, $args);
	$pgcalSettings['wplocale'] = stdLocale(get_locale());

	// $pgcalSettings['wptzstring'] = get_option('timezone_string');
	// $pgcalSettings['wptzoffset'] = get_option('gmt_offset');

	// Load Scripts
	// Full Calendar
	wp_enqueue_script('fullcalendar');
	// wp_enqueue_script('fc_locales');

	// Popper / Tippy
	if (isset($pgcalSettings['use_tooltip'])) {
		wp_enqueue_script('popper');
		wp_enqueue_script('tippy');
		wp_enqueue_script('pgcal_tippy');

		wp_enqueue_style('pgcal_tippy');
		wp_enqueue_style('tippy_light');
	}

	// Load Local Scripts
	wp_enqueue_script('pgcal_helpers');
	wp_enqueue_script('pgcal_loader');

	// Load Styles
	wp_enqueue_style('fullcalendar');
	wp_enqueue_style('pgcal_css');

	// Pass PHP data to script(s)
	wp_localize_script('pgcal_loader', 'pgcalSettings', $pgcalSettings);


	return "
      <div id='pgcalendar'>loading...</div>
      <div id='tz_message' style='color: grey;'></div>
    ";
}
