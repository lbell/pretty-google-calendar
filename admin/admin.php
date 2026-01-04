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
    add_action('admin_notices', array($this, 'pgcal_replacement_notice'));
    add_action('admin_init', array($this, 'pgcal_dismiss_replacement_notice'));
  }

  /**
   * Display admin notice about plugin replacement
   *
   * Notifies users that Pretty Google Calendar is being replaced by
   * Hydrogen Calendar Embeds which uses simpler .ics feeds.
   *
   * @since 1.7.0
   */
  public function pgcal_replacement_notice() {
    // Check if notice has been dismissed
    if (get_option('pgcal_replacement_notice_dismissed')) {
      return;
    }

    // Only show to users who can manage options
    if (!current_user_can('manage_options')) {
      return;
    }

    $dismiss_url = wp_nonce_url(
      add_query_arg('pgcal_dismiss_replacement_notice', '1'),
      'pgcal_dismiss_replacement_notice',
      'pgcal_nonce'
    );

?>
    <div class="notice notice-info is-dismissible pgcal-replacement-notice">
      <p>
        <strong>👋 <?php esc_html_e('Hey there! Pretty Google Calendar is moving on.', 'pretty-google-calendar'); ?></strong>
      </p>
      <p>
        <?php esc_html_e('We\'ve built something better:', 'pretty-google-calendar'); ?>
        <a href="https://wordpress.org/plugins/hydrogen-calendar-embeds/" target="_blank" rel="noopener noreferrer"><strong>Hydrogen Calendar Embeds</strong></a>
      </p>
      <ul style="list-style: disc; margin-left: 20px;">
        <li><?php esc_html_e('No more fussing with the Google API — just use simple .ics calendar feeds', 'pretty-google-calendar'); ?></li>
        <li><?php esc_html_e('Now Block friendly! (All your previous shortcodes will still work though)', 'pretty-google-calendar'); ?></li>
        <li><?php esc_html_e('More features, fewer bugs, 100% free, and still lightweight', 'pretty-google-calendar'); ?></li>
      </ul>
      <p>
        <em><?php esc_html_e('Future development will happen there. Visit the Pretty Google Calendar Settings page for migration help.', 'pretty-google-calendar'); ?></em>
      </p>
      <p>
        <a href="https://wordpress.org/plugins/hydrogen-calendar-embeds/" class="button button-primary" target="_blank" rel="noopener noreferrer">
          <?php esc_html_e('Get Hydrogen Calendar Embeds', 'pretty-google-calendar'); ?>
        </a>
        <a href="<?php echo esc_url($dismiss_url); ?>" class="button button-secondary" style="margin-left: 10px;">
          <?php esc_html_e('Dismiss Forever', 'pretty-google-calendar'); ?>
        </a>
      </p>
    </div>
  <?php
  }

  /**
   * Handle dismissal of the replacement notice
   *
   * @since 1.7.0
   */
  public function pgcal_dismiss_replacement_notice() {
    if (
      isset($_GET['pgcal_dismiss_replacement_notice']) &&
      isset($_GET['pgcal_nonce']) &&
      wp_verify_nonce(sanitize_text_field(wp_unslash($_GET['pgcal_nonce'])), 'pgcal_dismiss_replacement_notice') &&
      current_user_can('manage_options')
    ) {
      update_option('pgcal_replacement_notice_dismissed', true);
      // Redirect to remove query args from URL
      wp_safe_redirect(remove_query_arg(array('pgcal_dismiss_replacement_notice', 'pgcal_nonce')));
      exit;
    }
  }

  /**
   * Find all posts/pages containing the pretty_google_calendar shortcode
   *
   * @since 1.7.0
   * @return array Array of posts with shortcode info
   */
  public function pgcal_find_shortcode_usages() {
    global $wpdb;

    // Search for posts containing the shortcode
    // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
    $results = $wpdb->get_results(
      $wpdb->prepare(
        "SELECT ID, post_title, post_type, post_content
         FROM {$wpdb->posts}
         WHERE post_content LIKE %s
         AND post_status IN ('publish', 'draft', 'private', 'pending')
         ORDER BY post_type, post_title",
        '%[pretty_google_calendar%'
      )
    );

    $shortcode_usages = array();

    if ($results) {
      foreach ($results as $post) {
        // Extract all shortcodes from the content
        preg_match_all('/\[pretty_google_calendar[^\]]*\]/', $post->post_content, $matches);

        if (!empty($matches[0])) {
          foreach ($matches[0] as $shortcode) {
            $shortcode_usages[] = array(
              'post_id'    => $post->ID,
              'post_title' => $post->post_title,
              'post_type'  => $post->post_type,
              'shortcode'  => $shortcode,
              'edit_link'  => get_edit_post_link($post->ID, 'raw'),
            );
          }
        }
      }
    }

    return $shortcode_usages;
  }

  /**
   * Render the migration helper section on the settings page
   *
   * @since 1.7.0
   */
  public function pgcal_render_migration_helper() {
    $usages = $this->pgcal_find_shortcode_usages();
    $global_settings = get_option('pgcal_settings', array());
  ?>
    <div class="pgcal-migration-helper" style="background: #fff; border: 1px solid #c3c4c7; border-left: 4px solid #2271b1; padding: 15px 20px; margin: 20px 0;">
      <h2 style="margin-top: 0;"><?php esc_html_e('Migration Helper', 'pretty-google-calendar'); ?></h2>
      <p><?php esc_html_e('Below are your current Pretty Google Calendar settings and shortcode usages to help you migrate to Hydrogen Calendar Embeds.', 'pretty-google-calendar'); ?></p>

      <h3><?php esc_html_e('Global Settings', 'pretty-google-calendar'); ?></h3>
      <?php if (!empty($global_settings['google_api'])) : ?>
        <p><strong><?php esc_html_e('Google API Key:', 'pretty-google-calendar'); ?></strong>
          <code><?php echo esc_html($global_settings['google_api']); ?></code>
        </p>
        <p><em><?php esc_html_e('Note: Hydrogen Calendar Embeds uses .ics feeds, so you won\'t need this API key anymore!', 'pretty-google-calendar'); ?></em></p>
      <?php else : ?>
        <p><em><?php esc_html_e('No Google API key configured.', 'pretty-google-calendar'); ?></em></p>
      <?php endif; ?>

      <h3><?php esc_html_e('Shortcode Usages Found', 'pretty-google-calendar'); ?></h3>
      <?php if (!empty($usages)) : ?>
        <p><?php
            printf(
              /* translators: %d: Number of shortcodes found */
              esc_html(_n(
                'Found %d shortcode in your content:',
                'Found %d shortcodes in your content:',
                count($usages),
                'pretty-google-calendar'
              )),
              count($usages)
            );
            ?></p>
        <table class="widefat striped" style="margin-top: 10px;">
          <thead>
            <tr>
              <th><?php esc_html_e('Location', 'pretty-google-calendar'); ?></th>
              <th><?php esc_html_e('Type', 'pretty-google-calendar'); ?></th>
              <th><?php esc_html_e('Current Shortcode', 'pretty-google-calendar'); ?></th>
              <th><?php esc_html_e('Action', 'pretty-google-calendar'); ?></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($usages as $usage) : ?>
              <tr>
                <td><?php echo esc_html($usage['post_title'] ?: __('(no title)', 'pretty-google-calendar')); ?></td>
                <td><?php echo esc_html(ucfirst($usage['post_type'])); ?></td>
                <td>
                  <code style="display: block; max-width: 400px; overflow-x: auto; white-space: nowrap; padding: 5px; background: #f0f0f0;"><?php echo esc_html($usage['shortcode']); ?></code>
                  <button type="button" class="button button-small pgcal-copy-btn" data-copy="<?php echo esc_attr($usage['shortcode']); ?>" style="margin-top: 5px;">
                    <?php esc_html_e('Copy', 'pretty-google-calendar'); ?>
                  </button>
                </td>
                <td>
                  <?php if ($usage['edit_link']) : ?>
                    <a href="<?php echo esc_url($usage['edit_link']); ?>" class="button button-small">
                      <?php esc_html_e('Edit', 'pretty-google-calendar'); ?>
                    </a>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>

        <h4 style="margin-top: 20px;"><?php esc_html_e('Migration Tips', 'pretty-google-calendar'); ?></h4>
        <ul style="list-style: disc; margin-left: 20px;">
          <li><?php esc_html_e('In Hydrogen Calendar Embeds, replace "gcal" with your calendar\'s public .ics URL', 'pretty-google-calendar'); ?></li>
          <li><?php esc_html_e('Most display options (views, locale, etc.) have equivalent settings in the new plugin', 'pretty-google-calendar'); ?></li>
          <li><?php esc_html_e('The new plugin supports blocks in addition to shortcodes', 'pretty-google-calendar'); ?></li>
        </ul>

      <?php else : ?>
        <p><em><?php esc_html_e('No shortcodes found in your posts or pages.', 'pretty-google-calendar'); ?></em></p>
      <?php endif; ?>

      <p style="margin-top: 20px;">
        <a href="https://wordpress.org/plugins/hydrogen-calendar-embeds/" class="button button-primary" target="_blank" rel="noopener noreferrer">
          <?php esc_html_e('Get Hydrogen Calendar Embeds', 'pretty-google-calendar'); ?>
        </a>
      </p>
    </div>

    <script>
      document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.pgcal-copy-btn').forEach(function(btn) {
          btn.addEventListener('click', function() {
            var text = this.getAttribute('data-copy');
            navigator.clipboard.writeText(text).then(function() {
              btn.textContent = '<?php echo esc_js(__('Copied!', 'pretty-google-calendar')); ?>';
              setTimeout(function() {
                btn.textContent = '<?php echo esc_js(__('Copy', 'pretty-google-calendar')); ?>';
              }, 2000);
            });
          });
        });
      });
    </script>
  <?php
  }

  /**
   * Add options page
   */
  public function pgcal_add_plugin_page() {
    // This page will be under "Settings"
    add_options_page(
      esc_attr__('Settings Admin', 'pretty-google-calendar'),
      esc_attr__('Pretty Google Calendar Settings', 'pretty-google-calendar'),
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
    <div class="pgcal-settings-header">

      <div class="pgcal-logo">
        <svg version="1.1" width="141" height="146" viewBox="0 0 141 146" xmlns="http://www.w3.org/2000/svg">
          <path d="M13.3 126.4v-89c0-2.4.9-4.5 2.6-6.3s3.8-2.6 6.2-2.6h8.8v-6.7c0-3.1 1.1-5.7 3.2-7.9 2.2-2.2 4.7-3.3 7.8-3.3h4.4c3 0 5.6 1.1 7.8 3.3s3.2 4.8 3.2 7.9v6.7h26.4v-6.7c0-3.1 1.1-5.7 3.2-7.9 2.2-2.2 4.7-3.3 7.8-3.3h4.4c3 0 5.6 1.1 7.8 3.3s3.2 4.8 3.2 7.9v6.7h8.8c2.4 0 4.4.9 6.2 2.6 1.7 1.8 2.6 3.8 2.6 6.3v88.9c0 2.4-.9 4.5-2.6 6.3s-3.8 2.6-6.2 2.6H22.1c-2.4 0-4.4-.9-6.2-2.6-1.7-1.8-2.6-3.8-2.6-6.2zm8.8 0h96.8V55.2H22.1v71.2zm17.6-84.5c0 .6.2 1.2.6 1.6s.9.6 1.6.6h4.4c.6 0 1.2-.2 1.6-.6s.6-.9.6-1.6v-20c0-.6-.2-1.2-.6-1.6s-.9-.6-1.6-.6h-4.4c-.6 0-1.2.2-1.6.6s-.6 1-.6 1.6v20zm52.8 0c0 .6.2 1.2.6 1.6s.9.6 1.6.6h4.4c.6 0 1.2-.2 1.6-.6s.6-.9.6-1.6v-20c0-.6-.2-1.2-.6-1.6s-.9-.6-1.6-.6h-4.4c-.6 0-1.2.2-1.6.6s-.6 1-.6 1.6v20z" />
          <text transform="scale(.98902 1.0111)" x="60" y="69.305733" font-family="Z003" font-size="19.485px" letter-spacing="0" stroke-width="1.218" text-anchor="middle" word-spacing="0" style="line-height:1.25" xml:space="preserve">
            <tspan x="60" y="69.305733">Pretty</tspan>
            <tspan x="60" y="95.274574">Google</tspan>
            <tspan x="65" y="121.24342">Calendar</tspan>
          </text>
        </svg>
      </div>
      <h1><?php echo esc_html__('Pretty Google Calendar Settings', 'pretty-google-calendar') ?></h1>
      <p>
        <button><a href="https://github.com/sponsors/lbell"><?php echo esc_html__('Sponsor', 'pretty-google-calendar') ?></a></button>
      </p>
    </div>

    <?php $this->pgcal_render_migration_helper(); ?>

    <form method="post" action="options.php">
      <?php
      // This prints out all hidden setting fields
      settings_fields('pgcal_option_group');
      do_settings_sections('pgcal-setting-admin');
      submit_button();
      ?>
    </form>
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
      esc_attr__('Usage', 'pretty-google-calendar'),
      array($this, 'pgcal_print_main_info'), // Callback
      'pgcal-setting-admin' // Page
    );

    add_settings_field(
      'google_api',
      esc_attr__('Google API', 'pretty-google-calendar'),
      array($this, 'pgcal_gapi_callback'), // Callback
      'pgcal-setting-admin', // Page
      'pgcal-main-settings' // Section
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
      $sanitized_input['google_api'] = sanitize_text_field($input['google_api']);
    return $sanitized_input;
  }

  /**
   * Print the Section text
   */
  public function pgcal_print_main_info() {
    printf(
      '<p>%s [pretty_google_calendar gcal="address@group.calendar.google.com"] </p>
      <p>%s <a href="https://fullcalendar.io/docs/google-calendar">https://fullcalendar.io/docs/google-calendar</a></p>
      <p>%s <a href="https://wordpress.org/plugins/pretty-google-calendar/#installation">https://wordpress.org/plugins/pretty-google-calendar/#installation</a></p>
      <p>%s</p>',
      esc_html__("Shortcode Usage:", "pretty-google-calendar"),
      esc_html__("You must have a google calendar API. See:", "pretty-google-calendar"),
      esc_html__("For shortcode usage and options, see:", "pretty-google-calendar"),
      esc_html__("Note: the tooltip and link settings have been moved to shortcode arguments for styling individual calendars.", "pretty-google-calendar")
    );
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
}
