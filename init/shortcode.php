<?php

function fgcal_shortcode($atts) {

	shortcode_atts(
		array(
			'tax'     => "role",
			'term'    => NULL,    // Can be slug or name
			'show'    => "all",   // all, current, past
			'style'   => "list",  // default list, or any other type added by a plugin
			'columns' => "1",
			'headers' => "1",     // Show headers 1 = yes, 0 = no
		),
		$atts
	);

	// // Validate input
	// $tax = sanitize_text_field($tax);
	// $term = sanitize_text_field($term);
	// $show = sanitize_text_field($show);
	// $style = sanitize_text_field($style);
	// $columns = is_int((int)$columns) ? $columns : 1;
	// $headers = in_array($headers, ["0", "1"], true) ? $headers : "1";


	// Get stored settings

	$fgcalSettings = get_option('fgcal_settings');
	// Add the attributes from the shortcode OVERRIDING the stored settings
	$fgcalSettings = array_merge($fgcalSettings, $atts);

	$fgcalSettings['wplocale'] = $stdLocale(get_locale());
	// $fgcalSettings['wptzstring'] = get_option('timezone_string');
	// $fgcalSettings['wptzoffset'] = get_option('gmt_offset');


	// Load CDN Scripts
	// Superagent
	// wp_enqueue_script('superagent');
	// Full Calendar
	wp_enqueue_script('fullcalendar');
	wp_enqueue_script('fc_daygrid');
	wp_enqueue_script('fc_list');
	wp_enqueue_script('fc_gcal');
	wp_enqueue_script('fc_locales');

	// Popper / Tooltip
	if (isset($fgcalSettings['use_tooltip'])) {
		wp_enqueue_script('popper');
		wp_enqueue_script('tooltip');
		wp_enqueue_style('fgcal_tooltip');
	}

	// Load Local Scripts
	wp_enqueue_script('fgcal_helpers');
	wp_enqueue_script('fgcal_loader');

	// Load Styles
	wp_enqueue_style('fullcalendar');
	wp_enqueue_style('fc_daygrid');
	wp_enqueue_style('fc_list');
	wp_enqueue_style('fgcal_css');

	// Pass options to scripts
	wp_localize_script('fgcalLoader', 'fgcalSettings', $fgcalSettings);

	return "
      <div id='fgcalendar'>loading...</div>
      <div id='tz_message' style='color: grey;'></div>
    ";
}
