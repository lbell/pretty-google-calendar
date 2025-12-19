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
