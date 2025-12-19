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
      'id_hash'                    => pgc_generate_unique_id_hash(),
      'use_tooltip'                => isset($globalSettings['use_tooltip']) ? "true" : "false",
      'no_link'                    => isset($globalSettings['no_link']) ? "true" : "false",
      'fc_args'                    => '{}',
    ),
    $atts
  );

  // Add the attributes from the shortcode OVERRIDING the stored settings
  $pgcalSettings = $args;
  $pgcalSettings["id_hash"] = preg_replace('/[\W]/', '', $pgcalSettings["id_hash"]);

  // Auto-resolve views based on user-provided attributes
  $pgcalSettings['views'] = pgc_resolve_views($atts, $args);

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
  <div id='pgcalendar-" . esc_attr($pgcalSettings["id_hash"]) . "' class='pgcal-container'>" . esc_html__("loading...", "pretty-google-calendar") . "</div>
  <div class='pgcal-branding'>" . esc_html__("Powered by", "pretty-google-calendar") . " <a href='https://wordpress.org/plugins/pretty-google-calendar/'>Pretty Google Calendar</a></div>
  ";

  return $shortcode_output;
}
