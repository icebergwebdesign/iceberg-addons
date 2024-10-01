<?php

namespace IcebergAddons\Admin;

class FreshdeskWidget {

    /**
     * Initializes the Freshdesk widget by registering necessary hooks.
     */
    public static function init() {
        // Register the scripts when WordPress is loaded
        add_action('wp_loaded', [__CLASS__, 'enqueue_freshdesk_widget']);

        // Enqueue the scripts for both frontend and admin dashboard
        add_action('wp_enqueue_scripts', [__CLASS__, 'enqueue_freshdesk_widget']);
        add_action('admin_enqueue_scripts', [__CLASS__, 'enqueue_freshdesk_widget']);
    }

    /**
     * Enqueues the Freshdesk widget JavaScript and CSS files.
     */
    public static function enqueue_freshdesk_widget() {
        // Check if the site is publicly visible
        $search_engine_visibility = get_option('blog_public');

        // Only load the widget if the site is public and the user has permission to edit posts
        if ($search_engine_visibility != '0' && current_user_can('edit_posts')) {
            // Enqueue the local Freshdesk JavaScript file
            wp_enqueue_script(
                'iceberg-freshdesk', 
                ICEBERG_ADDONS_URL . 'assets/js/freshdesk-widget.js', 
                [], 
                '1.0', 
                true
            );

            // Enqueue the Freshdesk widget script from Freshworks
            wp_enqueue_script(
                'iceberg-freshdesk-ext', 
                'https://widget.freshworks.com/widgets/63000001724.js', 
                [], 
                '1.0', 
                true
            );

            // Enqueue the Freshdesk widget CSS
            wp_enqueue_style(
                'iceberg-freshdesk-css', 
                ICEBERG_ADDONS_URL . 'assets/css/freshdesk-style.css', 
                [], 
                '1.0'
            );
        }
    }
}
