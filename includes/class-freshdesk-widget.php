<?php
class Iceberg_Addons_Freshdesk_Widget {
    
    public function register_hooks() {
        // Only check if we're in admin and user has sufficient permissions
        if (is_admin() && current_user_can('edit_posts')) {
            add_action('admin_enqueue_scripts', [$this, 'enqueue_assets']);
        }
    }

    public function enqueue_assets() {
        // Only load if search engines are allowed (blog_public = 1)
        if (get_option('blog_public') == 1) {
            wp_enqueue_style('dashicons');
            wp_enqueue_style(
                'iceberg-addons-freshdesk-widget',
                ICEBERG_ADDONS_URL . 'admin/css/freshdesk-widget.css',
                [],
                ICEBERG_ADDONS_VERSION
            );
            wp_enqueue_script(
                'iceberg-addons-freshdesk-widget',
                ICEBERG_ADDONS_URL . 'admin/js/freshdesk-widget.js',
                ['jquery'],
                ICEBERG_ADDONS_VERSION,
                true
            );
            wp_enqueue_script(
                'iceberg-freshdesk-ext',
                'https://widget.freshworks.com/widgets/63000001724.js', 
                '', 
                '1.0', 
                true 
            );
        }
    }
}