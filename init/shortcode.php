<?php

function fgcal_shortcode($atts) {
	$default = array();
	$fgcalSettings = get_option('fgcal_settings', $default);

	$args = shortcode_atts(
		array(
			'gcal'     => "",
		),
		$atts
	);

	// Add the attributes from the shortcode OVERRIDING the stored settings
	$fgcalSettings = array_merge($fgcalSettings, $args);
	$fgcalSettings['wplocale'] = stdLocale(get_locale());

	// $fgcalSettings['wptzstring'] = get_option('timezone_string');
	// $fgcalSettings['wptzoffset'] = get_option('gmt_offset');

	// Load Scripts

	// Full Calendar
	wp_enqueue_script('fullcalendar');
	// wp_enqueue_script('fc_locales');

	// Popper / Tooltip
	if (isset($fgcalSettings['use_tooltip'])) {
		wp_enqueue_script('popper');
		wp_enqueue_script('tooltip');
		wp_enqueue_script('fgcal_tooltip');

		wp_enqueue_style('fgcal_tooltip');
	}

	// Load Local Scripts
	wp_enqueue_script('fgcal_helpers');
	wp_enqueue_script('fgcal_loader');

	// Load Styles
	wp_enqueue_style('fullcalendar');

	// Pass PHP data to script(s)
	wp_localize_script('fgcal_loader', 'fgcalSettings', $fgcalSettings);


	return "
      <div id='fgcalendar'>loading...</div>
      <div id='tz_message' style='color: grey;'></div>
    ";
}
