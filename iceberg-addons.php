<?php
/**
 * Plugin Name: Iceberg AddOns
 * Plugin URI:  https://www.icebergwebdesign.com/
 * Description: Iceberg AddOns plugin for Iceberg Web Design's client
 * Version:     3.0.3
 * Author:      Iceberg Web Design
 * Author URI:  https://www.icebergwebdesign.com
 * License:     GPL v2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: iceberg-addons
 * Domain Path: /languages
 * 
 * @github-updater
 * GitHub Plugin URI: icebergwebdesign/iceberg-addons
 * GitHub Plugin URI: https://github.com/icebergwebdesign/iceberg-addons
 * Primary Branch: main

 * @package    IcebergAddOns
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

// Define plugin constants
define('ICEBERG_ADDONS_VERSION', '3.0.1');
define('ICEBERG_ADDONS_PATH', plugin_dir_path(__FILE__));
define('ICEBERG_ADDONS_DIR', plugin_dir_path(__FILE__));
define('ICEBERG_ADDONS_URL', plugin_dir_url(__FILE__));

/**
 * The code that runs during plugin activation.
 */
function activate_iceberg_addons() {
    require_once ICEBERG_ADDONS_PATH . 'includes/class-activator.php';
    Iceberg_Addons_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 */
function deactivate_iceberg_addons() {
    require_once ICEBERG_ADDONS_PATH . 'includes/class-deactivator.php';
    Iceberg_Addons_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_iceberg_addons');
register_deactivation_hook(__FILE__, 'deactivate_iceberg_addons');

/**
 * Load translations.
 */
function iceberg_addons_load_textdomain() {
    load_plugin_textdomain(
        'iceberg-addons',
        false,
        dirname(plugin_basename(__FILE__)) . '/languages/'
    );
}
add_action('plugins_loaded', 'iceberg_addons_load_textdomain');

// Composer autoloader
if (file_exists(dirname(__FILE__) . '/vendor/autoload.php')) {
    require_once dirname(__FILE__) . '/vendor/autoload.php';
}

// Load the main plugin class
require_once ICEBERG_ADDONS_PATH . 'includes/class-main.php';

/**
 * Begins execution of the plugin.
 */
function run_iceberg_addons() {
    $plugin = new Iceberg_Addons_Main();
    $plugin->run();
}

// Start the plugin
add_action('plugins_loaded', 'run_iceberg_addons', 11);