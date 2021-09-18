<?php

/** @package  */
class pgcalSettings {
	/**
	 * Holds the values to be used in the fields callbacks
	 */
	private $options;

	/**
	 * Start up
	 */
	public function __construct() {
		add_action('admin_menu', array($this, 'pgcal_add_plugin_page'));
		add_action('admin_init', array($this, 'pgcal_page_init'));
	}

	/**
	 * Add options page
	 */
	public function pgcal_add_plugin_page() {
		// This page will be under "Settings"
		add_options_page(
			'Settings Admin',
			'Pretty Google Calendar Settings',
			'manage_options',
			'pgcal-setting-admin',
			array($this, 'pgcal_create_admin_page')
		);
	}

	/**
	 * Options page callback
	 */
	public function pgcal_create_admin_page() {
		// Set class property
		$this->options = get_option('pgcal_settings');
?>
		<div class="wrap">
			<h1>Pretty Google Calendar Settings</h1>
			<form method="post" action="options.php">
				<?php
				// This prints out all hidden setting fields
				settings_fields('pgcal_option_group');
				do_settings_sections('pgcal-setting-admin');
				submit_button();
				?>
			</form>
		</div>
<?php
	}

	/**
	 * Register and add settings
	 */
	public function pgcal_page_init() {
		register_setting(
			'pgcal_option_group', // Option group
			'pgcal_settings', // Option name
			array($this, 'pgcal_sanitize') // Sanitize
		);

		add_settings_section(
			'pgcal-main-settings',
			'Main Settings',
			array($this, 'pgcal_print_section_info'), // Callback
			'pgcal-setting-admin' // Page
		);

		add_settings_field(
			'google_api',
			'Google API',
			array($this, 'pgcal_gapi_callback'), // Callback
			'pgcal-setting-admin', // Page
			'pgcal-main-settings' // Section
		);

		add_settings_field(
			'use_tooltip',
			'Use Tooltip',
			array($this, 'pgcal_tooltip_callback'),
			'pgcal-setting-admin',
			'pgcal-main-settings'
		);
	}

	/**
	 * Sanitize each setting field as needed
	 *
	 * @param array $input Contains all settings fields as array keys
	 */
	public function pgcal_sanitize($input) {
		$sanitized_input = array();
		if (isset($input['google_api']))
			// TODO test api?
			$sanitized_input['google_api'] = $input['google_api'];

		if (isset($input['use_tooltip']))
			$sanitized_input['use_tooltip'] = sanitize_text_field($input['use_tooltip']);

		return $sanitized_input;
	}

	/**
	 * Print the Section text
	 */
	public function pgcal_print_section_info() {
		print '<p>Shortcode Usage: [pretty_google_calendar gcal="address@group.calendar.google.com"] </p>
      <p>You must have a google calendar API. See: <a href="https://fullcalendar.io/docs/google-calendar">https://fullcalendar.io/docs/google-calendar</a></p>';
	}


	/**
	 * Get the settings option array and print one of its values
	 */
	public function pgcal_gapi_callback() {
		printf(
			'<input type="text" id="google_api" name="pgcal_settings[google_api]" value="%s" />',
			isset($this->options['google_api']) ? esc_attr($this->options['google_api']) : ''
		);
	}


	public function pgcal_tooltip_callback() {
		printf(
			'<input title="Use the popper/tooltip plugin to display event information." type="checkbox" id="use_tooltip" name="pgcal_settings[use_tooltip]" value="yes" %s />',
			isset($this->options['use_tooltip']) ? 'checked' : ''
		);
	}
}
