<?php
/*
Plugin Name: Pretty Google Calendar
Plugin URI: https://github.com/lbell/pretty-google-calendar
Description: Google Calendars that aren't ugly.
Version: 1.7.1
Author: LBell
Author URI: http://lorenbell.com
Text Domain: pretty-google-calendar
*/
/*  Copyright 2020 LBell

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


define('PGCAL_VER', "1.7.1");
define('PGCAL_DIR', plugin_dir_path(__FILE__)); // Trailing slash
define('PGCAL_TEMPLATE_DIR', PGCAL_DIR . 'templates/');
define('PGCAL_URL', plugin_dir_url(__FILE__));

load_plugin_textdomain('pretty-google-calendar', false, PGCAL_DIR . 'languages');

require(PGCAL_DIR . 'util/utils.php');
require(PGCAL_DIR . 'admin/admin.php');
require(PGCAL_DIR . 'init/shortcode.php');
require(PGCAL_DIR . 'init/init.php');

// require(PGCAL_DIR . 'dev/console-log.php'); // DEBUG
