<?php
function pgcal_shortcode($atts) {
  $default = array();
  $globalSettings = get_option('pgcal_settings', $default);

  $args = shortcode_atts(
    array(
      'gcal'                       => "",
      // 'locale'                     => $locale,
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
      'use_tooltip'                => $globalSettings['use_tooltip'] ? "true" : "false",
      'no_link'                    => $globalSettings['no_link'] ? "true" : "false",
    ),
    $atts
  );

  // Add the attributes from the shortcode OVERRIDING the stored settings
  $pgcalSettings = $args;



  wp_enqueue_script('fullcalendar');
  wp_enqueue_script('fc_locales');
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




  // wp_enqueue_script('pgcal_loader');


  $shortcode_output = "
  <div id='pgcalendar-" . $pgcalSettings["id_hash"] . "' class='pgcal-container'>" . esc_html__("loading...", "pretty-google-calendar") . "</div>
  <div class='pgcal-branding'>" . esc_html__("Powered by", "pretty-google-calendar") . " <a href='https://wordpress.org/plugins/pretty-google-calendar/'>Pretty Google Calendar</a></div>
  ";

  $script = "
    document.addEventListener('DOMContentLoaded', function() {
      function pgcal_inlineScript(settings) {
        var ajaxurl = '" . admin_url('admin-ajax.php') . "';
        pgcal_render_calendar(settings, ajaxurl);
      }

      pgcal_inlineScript(" . json_encode($pgcalSettings) . ");
    });
  ";
  wp_add_inline_script('pgcal_loader', $script);


  return $shortcode_output;
}
