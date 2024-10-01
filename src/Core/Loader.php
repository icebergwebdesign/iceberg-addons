<?php

namespace IcebergAddons\Core;

class Loader {

    public function run() {
        // Register HelpDashboardWidget first
        add_action('wp_dashboard_setup', ['IcebergAddons\\Admin\\HelpDashboardWidget', 'register_help_dashboard_widget']);
        
        // Register IcebergBlogWidget second
        add_action('wp_dashboard_setup', ['IcebergAddons\\Admin\\IcebergBlogWidget', 'register_blog_widget']);

        // Register the note-taking dashboard widget
        add_action('wp_dashboard_setup', ['IcebergAddons\\Admin\\NoteDashboardWidget', 'register_note_dashboard_widget']);
        
        // Hook to save the note when the form is submitted
        add_action('admin_init', ['IcebergAddons\\Admin\\NoteDashboardWidget', 'save_note_content']);
        
        // Other initializations
        add_action('admin_menu', ['IcebergAddons\\Admin\\SettingsPage', 'add_settings_page']);

        // Initialize the Freshdesk Widget
        \IcebergAddons\Admin\FreshdeskWidget::init();
    }
}
