<?php
/**
 * Plugin Name
 *
 * @package           IcebergAddOns
 * @author            IWD
 * @copyright         2022 Iceberg Web Design
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Iceberg AddOns
 * Plugin URI:        https://www.icebergwebdesign.com/
 * Description:       Iceberg AddOns
 * Version:           1.2.6
 * Requires at least: 5.8
 * Requires PHP:      7.4
 * Author:            Iceberg Web Design
 * Author URI:        https://www.icebergwebdesign.com
 * Text Domain:       iceberg-addons
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Update URI:        https://icebergwebdesign.com/
 *
 * @github-updater
 * GitHub Plugin URI: icebergwebdesign/iceberg-addons
 * GitHub Plugin URI: https://github.com/icebergwebdesign/iceberg-addons
 * Primary Branch: main
 */

/*
 * Exit if called directly.
 * PHP version check and exit.
*/

if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'ICEBERG_ADDONS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'ICEBERG_ADDONS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/*
 * Call specific file.
 * One file for specific features, not necessarily 1 function.
 */
include_once ICEBERG_ADDONS_PLUGIN_DIR . 'includes/dashboard-widget.php';
include_once ICEBERG_ADDONS_PLUGIN_DIR . 'includes/git-updater-check.php';
include_once ICEBERG_ADDONS_PLUGIN_DIR . 'includes/git-updater-setting.php';
include_once ICEBERG_ADDONS_PLUGIN_DIR . 'includes/freshdesk-widget.php';