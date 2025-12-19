<?php

function pgcal_shortcode($atts) {

  $default = array();
  $globalSettings = get_option('pgcal_settings', $default);

  $args = shortcode_atts(
    array(
      'gcal'                       => "",
      'locale'                     => "en",
      'list_type'                  => "listCustom", // listDay, listWeek, listMonth, and listYear also day, week, month, and year
      'custom_list_button'         => "list",
      'custom_days'                => "28",
      'views'                      => "dayGridMonth, listCustom",
      'initial_view'               => "dayGridMonth",
      'enforce_listview_on_mobile' => "true",
      'show_today_button'          => "true",
      'show_title'                 => "true",
      'id_hash'                    => bin2hex(random_bytes(5)),
      'use_tooltip'                => isset($globalSettings['use_tooltip']) ? "true" : "false",
      'no_link'                    => isset($globalSettings['no_link']) ? "true" : "false",
      'fc_args'                    => '{}',
    ),
    $atts
  );

  // Add the attributes from the shortcode OVERRIDING the stored settings
  $pgcalSettings = $args;
  $pgcalSettings["id_hash"] = preg_replace('/[\W]/', '', $pgcalSettings["id_hash"]);

  // If list_type was explicitly specified and differs from default, update views accordingly
  $list_type = trim($pgcalSettings['list_type']);
  $user_provided_views = isset($atts['views']); // Check if user explicitly provided views
  $user_provided_list_type = isset($atts['list_type']); // Check if user explicitly provided list_type
  $user_provided_fc_args = isset($atts['fc_args']) && $atts['fc_args'] !== '{}'; // Check if user provided meaningful fc_args

  // Only auto-adjust views if user provided list_type but not views
  if ($user_provided_list_type && !$user_provided_views && $list_type !== "listCustom") {
    // Replace listCustom with the specified list_type in the views
    $pgcalSettings['views'] = "dayGridMonth, " . $list_type;
  } else if ($user_provided_list_type && !$user_provided_views && $list_type === "listCustom") {
    // If list_type is explicitly set to listCustom, ensure it's in views
    $views = array_map('trim', explode(',', $pgcalSettings['views']));
    $list_type_found = false;
    foreach ($views as $view) {
      if (strpos(strtolower($view), strtolower($list_type)) !== false) {
        $list_type_found = true;
        break;
      }
    }
    if (!$list_type_found) {
      $views[] = $list_type;
      $pgcalSettings['views'] = implode(', ', $views);
    }
  } else if ($user_provided_fc_args && !$user_provided_views && !$user_provided_list_type) {
    // If user provided fc_args to configure views but didn't specify views or list_type,
    // use only dayGridMonth to avoid conflicting view buttons
    $pgcalSettings['views'] = "dayGridMonth";
  } else if (!$user_provided_list_type && !$user_provided_views && !$user_provided_fc_args) {
    // Use defaults - no changes needed (dayGridMonth, listCustom)
  } else if ($user_provided_views && !$user_provided_list_type) {
    // User provided views but not list_type - no auto-adjustment needed
  }

  // Include public-facing global settings needed by the frontend.
  // The Google API key is intended for client-side use to render public
  // calendars; embed it directly in the inline settings so anonymous
  // visitors don't rely on an AJAX endpoint to retrieve it.
  if (isset($globalSettings['google_api'])) {
    $pgcalSettings['google_api'] = $globalSettings['google_api'];
  }

  wp_enqueue_script('fullcalendar');
  wp_enqueue_script('fc_googlecalendar');

  if ($pgcalSettings['locale'] !== "en") {
    wp_enqueue_script('fc_locales');
  }

  if ($pgcalSettings['use_tooltip'] === "true") {
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

  // Create a nonce for AJAX requests that may require privileged access.
  $pgcal_nonce = wp_create_nonce('pgcal_ajax_nonce');

  $script = "
    document.addEventListener('DOMContentLoaded', function() {
      function pgcal_inlineScript(settings) {
        var ajaxurl = '" . admin_url('admin-ajax.php') . "';
        var pgcal_ajax_nonce = '" . $pgcal_nonce . "';
        pgcal_render_calendar(settings, ajaxurl, pgcal_ajax_nonce);
      }

      pgcal_inlineScript(" . json_encode($pgcalSettings) . ");
    });
  ";
  wp_add_inline_script('pgcal_loader', $script);

  $shortcode_output = "
  <div id='pgcalendar-" . $pgcalSettings["id_hash"] . "' class='pgcal-container'>" . esc_html__("loading...", "pretty-google-calendar") . "</div>
  <div class='pgcal-branding'>" . esc_html__("Powered by", "pretty-google-calendar") . " <a href='https://wordpress.org/plugins/pretty-google-calendar/'>Pretty Google Calendar</a></div>
  ";

  return $shortcode_output;
}
