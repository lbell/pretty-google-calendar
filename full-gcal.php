<?php
/*
Plugin Name: Full GCal
Plugin URI: https://github.com/lbell/full-gcal
Description: Google Calendars that aren't ugly.
Version: 1.0.0
Author: LBell
Author URI: http://lorenbell.com
Text Domain: fgcal
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


define('FGCAL_DIR', plugin_dir_path(__FILE__)); // Trailing slash
define('FGCAL_TEMPLATE_DIR', FGCAL_DIR . 'templates/');
define('FGCAL_URL', plugin_dir_url(__FILE__));

load_plugin_textdomain('fgcal', false, FGCAL_DIR . 'languages');

// require(FGCAL_DIR . 'util/dropdown-category-callback.php');
// require(FGCAL_DIR . 'util/post-entries.php');
require(FGCAL_DIR . 'util/utils.php');
// require(FGCAL_DIR . 'util/column-fill.php');

require(FGCAL_DIR . 'init/init.php');
require(FGCAL_DIR . 'init/shortcode.php');
// require(FGCAL_DIR . 'init/templates.php');
// require(FGCAL_DIR . 'init/admin/position-meta-box.php');
require(FGCAL_DIR . 'init/admin/directory-settings-page.php');

require(FGCAL_DIR . 'admin/admin.php');


require(FGCAL_DIR . 'dev/console-log.php'); // DEBUG
