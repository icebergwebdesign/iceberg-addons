<?php
/**
 * Fired during plugin deactivation.
 *
 * @package    IcebergAddOns
 */

class Iceberg_Addons_Deactivator {
    /**
     * Deactivate the plugin.
     *
     * Clean up any necessary data and perform deactivation tasks.
     * Note: This is not the same as uninstallation. For complete cleanup,
     * use uninstall.php when the plugin is deleted.
     */
    public static function deactivate() {
        // Flush rewrite rules
        flush_rewrite_rules();

        // Remove any transients
        delete_transient('iceberg_addons_freshdesk_cache');

        // Clear any scheduled hooks
        wp_clear_scheduled_hook('iceberg_addons_daily_cleanup');
        wp_clear_scheduled_hook('iceberg_addons_hourly_sync');

        // Store deactivation time
        update_option('iceberg_addons_deactivation_time', time());

        // Set deactivation flag
        update_option('iceberg_addons_deactivated', true);

        do_action('iceberg_addons_deactivated');
    }

    /**
     * Clean up any plugin-specific temporary data.
     */
    private static function cleanup_temporary_data() {
        global $wpdb;

        // Clean up any temporary tables if they exist
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}iceberg_temp_data");

        // Clean up any temporary options
        $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE 'iceberg_temp_%'");
    }

    /**
     * Remove any plugin-specific capabilities.
     */
    private static function remove_capabilities() {
        // Get all roles
        $roles = wp_roles()->get_names();

        // Remove custom capabilities from all roles
        foreach ($roles as $role_slug => $role_name) {
            $role = get_role($role_slug);
            if ($role) {
                $role->remove_cap('manage_iceberg_notes');
                $role->remove_cap('manage_iceberg_chat');
            }
        }
    }
}