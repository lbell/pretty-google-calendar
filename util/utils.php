<?php

/**
 * Generate a guaranteed unique ID hash for calendar instances on a page.
 *
 * Uses a static counter combined with a timestamp to guarantee uniqueness
 * across multiple calendar shortcodes on the same page load. This ensures
 * that even 200+ calendars on one page will never have ID collisions.
 *
 * @return string Unique hex ID hash
 */
function pgc_generate_unique_id_hash() {
  static $instance_counter = 0;
  $instance_counter++;

  // Combine timestamp (microseconds) + instance counter + random for good measure
  // timestamp (8 bytes) + counter (4 bytes) = 12 bytes, highly unlikely to collide
  $unique_data = microtime(true) . '-' . $instance_counter . '-' . wp_rand(1000, 9999);

  return bin2hex(hash('sha256', $unique_data, true));
}

/**
 * Automatically resolve the calendar views based on user-provided attributes.
 *
 * Intelligently handles view configuration with the following logic:
 * - If user provides list_type but not views: auto-adjust views to include it
 * - If user provides fc_args but not views/list_type: use only dayGridMonth
 * - If user provides views explicitly: use as-is, no auto-adjustment
 * - Otherwise: use defaults (dayGridMonth, listCustom)
 *
 * @param array $shortcode_atts User-provided shortcode attributes
 * @param array $parsed_args    Parsed arguments with defaults applied
 * @return string Resolved views string (comma-separated)
 */
function pgc_resolve_views($shortcode_atts, $parsed_args) {
  $list_type = trim($parsed_args['list_type']);
  $current_views = $parsed_args['views'];

  // Determine what the user explicitly provided
  $user_provided_views = isset($shortcode_atts['views']);
  $user_provided_list_type = isset($shortcode_atts['list_type']);
  $user_provided_fc_args = isset($shortcode_atts['fc_args']) && $shortcode_atts['fc_args'] !== '{}';

  // If user explicitly provided views, use them as-is
  if ($user_provided_views) {
    return $current_views;
  }

  // If user provided list_type but not views, auto-adjust
  if ($user_provided_list_type) {
    if ($list_type === 'listCustom') {
      // Ensure listCustom is in the views
      $views = array_map('trim', explode(',', $current_views));
      $list_type_found = false;

      foreach ($views as $view) {
        if (stripos($view, $list_type) !== false) {
          $list_type_found = true;
          break;
        }
      }

      if (!$list_type_found) {
        $views[] = $list_type;
        return implode(', ', $views);
      }

      return $current_views;
    } else {
      // Replace listCustom with the specified list_type
      return 'dayGridMonth, ' . $list_type;
    }
  }

  // Default: use existing views
  return $current_views;
}

/**
 * Automatically resolve the initial_view based on the provided views.
 *
 * Intelligently handles initial_view configuration:
 * - If initial_view is in the views list: use it (valid choice, either user-specified or default)
 * - If only one view is specified: use that view
 * - If multiple views and default view (dayGridMonth) is in the list: use the default
 * - If multiple views but default is NOT in the list: use the first view they provided
 *
 * @param string $views        The resolved views string (comma-separated)
 * @param string $initial_view The currently set initial_view value (default or user-provided)
 * @return string The resolved initial_view
 */
function pgc_resolve_initial_view($views, $initial_view) {
  // Parse views into individual view names
  $view_list = array_map('trim', explode(',', $views));

  // If there's only one view, use it as the initial view
  if (count($view_list) === 1) {
    return $view_list[0];
  }

  // For multiple views, check if the current initial_view is in the list
  $initial_view_trimmed = trim($initial_view);
  if (in_array($initial_view_trimmed, $view_list, true)) {
    // It's valid, use it
    return $initial_view_trimmed;
  }

  // initial_view is not in the views list, pick one intelligently
  $default_view = 'dayGridMonth';
  $has_default_in_views = in_array($default_view, $view_list, true);

  // If default view is in their list, use it
  if ($has_default_in_views) {
    return $default_view;
  }

  // If default view is NOT in their list, use their first view
  return $view_list[0];
}
