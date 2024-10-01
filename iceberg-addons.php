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
 * Description:       Iceberg AddOns Plugin
 * Version:           2.0.0
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


if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Define plugin constants
define( 'ICEBERG_ADDONS_VERSION', '1.0.0' );
define( 'ICEBERG_ADDONS_DIR', plugin_dir_path( __FILE__ ) );
define( 'ICEBERG_ADDONS_URL', plugin_dir_url( __FILE__ ) );

// Include custom autoloader
require_once ICEBERG_ADDONS_DIR . 'src/Core/autoload.php';

// Load the core plugin class
if ( class_exists( 'IcebergAddons\\Core\\Loader' ) ) {
    $plugin = new IcebergAddons\Core\Loader();
    $plugin->run();
}