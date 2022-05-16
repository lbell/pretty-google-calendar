<?php

function pgcal_shortcode($atts) {
	$default = array();
	$pgcalSettings = get_option('pgcal_settings', $default);

	$args = shortcode_atts(
		array(
			'gcal'                => "",
			'locale'              => "en",
			'list_type'           => "listCustom", // listDay, listWeek, listMonth, and listYear also day, week, month, and year
			'custom_list_button'  => "list",
			'custom_days'         => "28",
		),
		$atts
	);

	// Add the attributes from the shortcode OVERRIDING the stored settings
	$pgcalSettings = array_merge($pgcalSettings, $args);

	// Load Scripts
	wp_enqueue_script('fullcalendar');
	wp_enqueue_script('fc_locales');
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
      <div class='pgcal-branding'>Powered by <a href='https://wordpress.org/plugins/pretty-google-calendar/'>Pretty Google Calendar</a></div>
    ";
}
