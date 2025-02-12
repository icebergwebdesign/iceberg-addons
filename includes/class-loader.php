<?php
/**
 * The loader class that's responsible for maintaining and registering all hooks that power
 * the plugin.
 *
 * @package    IcebergAddOns
 */

class Iceberg_Addons_Loader {
    /**
     * Array of registered actions.
     *
     * @var array
     */
    private $actions = [];

    /**
     * Register all of the hooks related to the plugin functionality.
     */
    public function add_actions() {
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_assets']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_public_assets']);
    }

    /**
     * Register the stylesheets for the admin area.
     */
    public function enqueue_admin_assets() {
        wp_enqueue_style('iceberg-addons-admin', ICEBERG_ADDONS_URL . 'admin/css/admin.css', [], ICEBERG_ADDONS_VERSION);
        wp_enqueue_script('iceberg-addons-admin', ICEBERG_ADDONS_URL . 'admin/js/admin.js', ['jquery'], ICEBERG_ADDONS_VERSION);
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     */
    /* disabled for now
    public function enqueue_public_assets() {
        wp_enqueue_style('iceberg-addons-public', ICEBERG_ADDONS_URL . 'public/css/public.css', [], ICEBERG_ADDONS_VERSION);
        wp_enqueue_script('iceberg-addons-public', ICEBERG_ADDONS_URL . 'public/js/public.js', ['jquery'], ICEBERG_ADDONS_VERSION);
    }
    */
}