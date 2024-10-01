<?php

namespace IcebergAddons\Admin;

class NoteDashboardWidget {

    /**
     * Registers the note-taking dashboard widget.
     */
    public static function register_note_dashboard_widget() {
        // Check if the current user is an admin with username 'icebergdev'
        if (current_user_can('administrator') ) {
            wp_add_dashboard_widget(
                'iceberg_note_widget',           // Widget slug
                'Iceberg Notes',                 // Widget title
                [__CLASS__, 'display_note_widget'] // Display callback
            );
            // Move this widget to the third position
            HelpDashboardWidget::move_widget_to_position('iceberg_note_widget', 3);
        }
    }

    /**
     * Displays the note-taking widget with a WYSIWYG editor.
     */
    public static function display_note_widget() {
        // Fetch the saved note from the database (stored as an option)
        $note_content = get_option('iceberg_note_content', '');

        // Display the WYSIWYG editor
        echo '<form method="post" action="">';
        wp_editor($note_content, 'iceberg_note_content', [
            'textarea_name' => 'iceberg_note_content',
            'media_buttons' => false, // Disable media buttons
            'textarea_rows' => 10,
        ]);

        // Generate a nonce field for security
        wp_nonce_field('iceberg_note_widget_save', 'iceberg_note_widget_nonce');

        // Add the submit button
        submit_button('Save Note');

        echo '</form>';
    }

    /**
     * Saves the note content when the form is submitted.
     */
    public static function save_note_content() {
        // Verify the nonce and the permission
        if (isset($_POST['iceberg_note_widget_nonce']) && wp_verify_nonce($_POST['iceberg_note_widget_nonce'], 'iceberg_note_widget_save')) {
            // Check if the user is an administrator and has permission
            if (current_user_can('administrator') ) {
                // Save the content as an option
                if (isset($_POST['iceberg_note_content'])) {
                    update_option('iceberg_note_content', wp_kses_post($_POST['iceberg_note_content']));
                }
            }
        }
    }
}
