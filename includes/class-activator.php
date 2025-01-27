<?php
/**
 * Fired during plugin activation.
 *
 * @package    IcebergAddOns
 */

class Iceberg_Addons_Activator {
    /**
     * Activate the plugin.
     *
     * Initialize any plugin data, create necessary database tables,
     * and set up default options during plugin activation.
     */
    public static function activate() {
        // Set default options
        $default_options = array(
            'enable_notes' => true,
            'notes_roles' => array('administrator', 'editor', 'author')
        );

        add_option('iceberg_addons_options', $default_options);

        // Register notes post type to flush rewrite rules
        require_once ICEBERG_ADDONS_PATH . 'includes/class-notes.php';
        $notes = new Iceberg_Addons_Notes();
        $notes->register_post_type();

        // Flush rewrite rules
        flush_rewrite_rules();

        // Set activation flag
        add_option('iceberg_addons_activated', true);
        
        // Store activation time
        add_option('iceberg_addons_activation_time', time());

        // Store version number
        add_option('iceberg_addons_version', ICEBERG_ADDONS_VERSION);

        do_action('iceberg_addons_activated');
    }
}