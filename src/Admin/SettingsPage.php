<?php

namespace IcebergAddons\Admin;

class SettingsPage {

    /**
     * Adds the settings page under the 'Settings' menu in the WordPress admin area.
     */
    public static function add_settings_page() {
        add_options_page(
            'Iceberg Web Design',  // Page title
            'Iceberg AddOns',      // Menu title
            'manage_options',      // Capability
            'iceberg-addons',      // Menu slug
            [__CLASS__, 'display_settings_page'] // Callback to display the settings page content
        );
    }

    /**
     * Displays the settings page with a larger note editor.
     */
    public static function display_settings_page() {
        // Check if the user has permission to access the settings
        if (!current_user_can('manage_options')) {
            return;
        }

        // Save the note if the form is submitted
        if (isset($_POST['iceberg_note_widget_nonce']) && wp_verify_nonce($_POST['iceberg_note_widget_nonce'], 'iceberg_note_widget_save')) {
            if (isset($_POST['iceberg_note_content'])) {
                update_option('iceberg_note_content', wp_kses_post($_POST['iceberg_note_content']));
                echo '<div class="updated"><p>Note saved successfully!</p></div>';
            }
        }

        // Retrieve the saved note
        $note_content = get_option('iceberg_note_content', '');

        echo '<div class="wrap">';
        echo '<h1>Iceberg AddOns Settings</h1>';
        echo '<form method="post" action="">';

        // Display the WYSIWYG editor for the note
        wp_editor($note_content, 'iceberg_note_content', [
            'textarea_name' => 'iceberg_note_content',
            'textarea_rows' => 20,
            'media_buttons' => false,
        ]);

        // Add a nonce field for security
        wp_nonce_field('iceberg_note_widget_save', 'iceberg_note_widget_nonce');

        // Add a save button
        submit_button('Save Note');

        echo '</form>';
        echo '</div>';
    }
}
