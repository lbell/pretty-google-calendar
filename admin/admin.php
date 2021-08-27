<?php

/** @package  */
class fgcalSettings {
	/**
	 * Holds the values to be used in the fields callbacks
	 */
	private $options;

	/**
	 * Start up
	 */
	public function __construct() {
		add_action('admin_menu', array($this, 'add_plugin_page'));
		add_action('admin_init', array($this, 'page_init'));
	}

	/**
	 * Add options page
	 */
	public function add_plugin_page() {
		// This page will be under "Settings"
		add_options_page(
			'Settings Admin',
			'Full G Calendar Settings',
			'manage_options',
			'fgcal-setting-admin',
			array($this, 'create_admin_page')
		);
	}

	/**
	 * Options page callback
	 */
	public function create_admin_page() {
		// Set class property
		$this->options = get_option('fgcal_settings');
?>
		<div class="wrap">
			<h1>Full G Calendar Settings</h1>
			<form method="post" action="options.php">
				<?php
				// This prints out all hidden setting fields
				settings_fields('fgcal_option_group');
				do_settings_sections('fgcal-setting-admin');
				submit_button();
				?>
			</form>
		</div>
<?php
	}

	/**
	 * Register and add settings
	 */
	public function page_init() {
		register_setting(
			'fgcal_option_group', // Option group
			'fgcal_settings', // Option name
			array($this, 'sanitize') // Sanitize
		);

		add_settings_section(
			'fgcal-main-settings',
			'Main Settings',
			array($this, 'print_section_info'), // Callback
			'fgcal-setting-admin' // Page
		);

		add_settings_field(
			'google_api',
			'Google API',
			array($this, 'gapi_callback'), // Callback
			'fgcal-setting-admin', // Page
			'fgcal-main-settings' // Section
		);

		add_settings_field(
			'use_tooltip',
			'Use Tooltip',
			array($this, 'tooltip_callback'),
			'fgcal-setting-admin',
			'fgcal-main-settings'
		);

		add_settings_field(
			'fixed_tz',
			'Use a fixed timezone (experimental)',
			array($this, 'tz_callback'),
			'fgcal-setting-admin', // Page
			'fgcal-main-settings' // Section
		);
	}

	/**
	 * Sanitize each setting field as needed
	 *
	 * @param array $input Contains all settings fields as array keys
	 */
	public function sanitize($input) {
		$sanitized_input = array();
		if (isset($input['google_api']))
			// TODO test api?
			// $sanitized_input['google_api'] = absint( $input['google_api'] );
			$sanitized_input['google_api'] = $input['google_api'];


		// NOTE: Unnessary since we're the only one providing input
		if (isset($input['use_tooltip']))
			$sanitized_input['use_tooltip'] = sanitize_text_field($input['use_tooltip']);

		// NOTE: Unnessary since we're the only one providing input
		if (isset($input['fixed_tz']))
			$sanitized_input['fixed_tz'] = sanitize_text_field($input['fixed_tz']);

		return $sanitized_input;
	}

	/**
	 * Print the Section text
	 */
	public function print_section_info() {
		print '<p>Shortcode Usage: [full_gcal gcal="address@group.calendar.google.com"] </p>
      <p>You must have a google calendar API. See: <a href="https://fullcalendar.io/docs/google-calendar">https://fullcalendar.io/docs/google-calendar</a></p>';
	}

	/**
	 * Creates a list of timezone names for the option dropdown
	 *
	 * @param  string $selected Previously selected string (to set "selected" marker)
	 * @return string           HTML formatted options list
	 */
	private function create_tz_list($selected) {
		$tzlist = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
		$options = '<option value="local">Change times to local computer TZ</option>';

		foreach ($tzlist as $item) {
			$mark = ($item === $selected) ? 'selected' : '';
			$options .= '<option value="' . $item . '" ' . $mark . '>' . $item . '</option>';
		}

		return $options;
	}


	/**
	 * Get the settings option array and print one of its values
	 */
	public function gapi_callback() {
		printf(
			'<input type="text" id="google_api" name="fgcal_settings[google_api]" value="%s" />',
			isset($this->options['google_api']) ? esc_attr($this->options['google_api']) : ''
		);
	}


	public function tooltip_callback() {
		printf(
			'<input title="Use the popper/tooltip plugin to display event information." type="checkbox" id="use_tooltip" name="fgcal_settings[use_tooltip]" value="yes" %s />',
			isset($this->options['use_tooltip']) ? 'checked' : ''
		);
	}


	public function tz_callback() {
		$tzopt = $this->options['fixed_tz'] ?? '';

		printf(
			'<select name="fgcal_settings[fixed_tz]">
                %s
            </select>',
			$this->create_tz_list($tzopt)
		); // Create the list of options, sending selected
	}
}
