<?php
/**
 * The notes functionality of the plugin.
 *
 * @package    IcebergAddOns
 */

class Iceberg_Addons_Notes {
    /**
     * Register all hooks for the notes functionality.
     */
    public function register_hooks() {
        // Assets
        add_action('admin_enqueue_scripts', [$this, 'enqueue_assets']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);

        // AJAX handlers
        add_action('wp_ajax_iceberg_add_note', [$this, 'ajax_add_note']);
        add_action('wp_ajax_iceberg_edit_note', [$this, 'ajax_edit_note']);
        add_action('wp_ajax_iceberg_delete_note', [$this, 'ajax_delete_note']);
        add_action('wp_ajax_iceberg_fetch_notes', [$this, 'ajax_fetch_notes']);

        // Admin bar and interface
        add_action('admin_bar_menu', [$this, 'add_admin_bar_icon'], 100);
        add_action('admin_footer', [$this, 'render_notes_panel']);
        add_action('wp_footer', [$this, 'render_notes_panel']);

        // Block editor integration
        add_action('enqueue_block_editor_assets', [$this, 'enqueue_block_editor_assets']);

        // Redirect single note pages
        add_action('template_redirect', [$this, 'redirect_single_notes']);
    }

    /**
     * Register the custom post type for notes.
     */
    public function register_post_type() {
        $args = array(
            'public' => false,
            'show_ui' => false,
            'show_in_rest' => false,
            'exclude_from_search' => true,
            'publicly_queryable' => false,
            'capability_type' => 'post',
            'supports' => array('title', 'editor'),
            'capabilities' => array(
                'create_posts' => 'edit_posts',
                'edit_post' => 'edit_posts',
                'delete_post' => 'edit_posts',
            ),
        );
        register_post_type('iceberg_note', $args);
    }

    /**
     * Enqueue assets for notes functionality.
     */
    public function enqueue_assets() {
        if (is_user_logged_in() && current_user_can('edit_posts')) {
            wp_enqueue_style(
                'iceberg-notes',
                ICEBERG_ADDONS_URL . 'public/css/notes.css',
                [],
                ICEBERG_ADDONS_VERSION
            );

            wp_enqueue_script(
                'iceberg-notes',
                ICEBERG_ADDONS_URL . 'public/js/notes.js',
                ['jquery', 'wp-editor'],
                ICEBERG_ADDONS_VERSION,
                true
            );

            wp_localize_script('iceberg-notes', 'icebergNotes', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('iceberg_notes_nonce'),
                'strings' => array(
                    'confirmDelete' => __('Are you sure you want to delete this note?', 'iceberg-addons'),
                    'saveError' => __('Error saving note. Please try again.', 'iceberg-addons'),
                    'deleteError' => __('Error deleting note. Please try again.', 'iceberg-addons'),
                    'loadError' => __('Error loading notes. Please refresh.', 'iceberg-addons')
                )
            ));
        }
    }

    /**
     * Add notes icon to admin bar.
     */
    public function add_admin_bar_icon($wp_admin_bar) {
        if (current_user_can('edit_posts')) {
            $wp_admin_bar->add_node(array(
                'id' => 'iceberg-notes',
                'title' => '<span class="ab-icon dashicons dashicons-media-default"></span>' . __('iNotes', 'iceberg-addons'),
                'href' => '#',
                'meta' => array('class' => 'iceberg-notes-icon')
            ));
        }
    }

    /**
     * Render the notes panel in footer.
     */
    public function render_notes_panel() {
        if (current_user_can('edit_posts')) {
            include ICEBERG_ADDONS_PATH . 'public/partials/notes-panel.php';
        }
    }

    /**
     * AJAX handler for adding a note.
     */
    public function ajax_add_note() {
        check_ajax_referer('iceberg_notes_nonce', 'nonce');

        if (!current_user_can('edit_posts')) {
            wp_send_json_error('Unauthorized');
        }

        $title = isset($_POST['title']) ? sanitize_text_field(wp_unslash($_POST['title'])) : '';
        $content = isset($_POST['content']) ? wp_kses_post(wp_unslash($_POST['content'])) : '';

        $note_id = wp_insert_post(array(
            'post_type' => 'iceberg_note',
            'post_title' => $title,
            'post_content' => $content,
            'post_status' => 'publish',
            'post_author' => get_current_user_id(),
        ));

        if ($note_id) {
            update_post_meta($note_id, '_note_timestamp', current_time('mysql'));
            wp_send_json_success(array('note_id' => $note_id));
        } else {
            wp_send_json_error('Failed to save note');
        }
    }

    /**
     * AJAX handler for editing a note.
     */
    public function ajax_edit_note() {
        check_ajax_referer('iceberg_notes_nonce', 'nonce');

        if (!current_user_can('edit_posts')) {
            wp_send_json_error('Unauthorized');
        }

        $note_id = isset($_POST['note_id']) ? intval($_POST['note_id']) : 0;
        $title = isset($_POST['title']) ? sanitize_text_field(wp_unslash($_POST['title'])) : '';
        $content = isset($_POST['content']) ? wp_kses_post(wp_unslash($_POST['content'])) : '';

        $updated = wp_update_post(array(
            'ID' => $note_id,
            'post_title' => $title,
            'post_content' => $content
        ));

        if ($updated) {
            wp_send_json_success();
        } else {
            wp_send_json_error('Failed to update note');
        }
    }

    /**
     * AJAX handler for deleting a note.
     */
    public function ajax_delete_note() {
        check_ajax_referer('iceberg_notes_nonce', 'nonce');

        if (!current_user_can('edit_posts')) {
            wp_send_json_error('Unauthorized');
        }

        $note_id = isset($_POST['note_id']) ? intval($_POST['note_id']) : 0;
        
        if ($note_id && wp_delete_post($note_id, true)) {
            wp_send_json_success();
        } else {
            wp_send_json_error('Failed to delete note');
        }
    }

    /**
     * AJAX handler for fetching notes.
     */
    public function ajax_fetch_notes() {
        check_ajax_referer('iceberg_notes_nonce', 'nonce');

        if (!current_user_can('edit_posts')) {
            wp_send_json_error('Unauthorized');
        }

        $notes = get_posts(array(
            'post_type' => 'iceberg_note',
            'posts_per_page' => -1,
            'orderby' => 'date',
            'order' => 'DESC'
        ));

        $result = array();
        foreach ($notes as $note) {
            $author = get_user_by('id', $note->post_author);
            $result[] = array(
                'id' => $note->ID,
                'title' => $note->post_title,
                'content' => $note->post_content,
                'timestamp' => get_the_date('M j, Y - g:ia', $note->ID),
                'author' => $author ? $author->display_name : __('Unknown', 'iceberg-addons')
            );
        }

        wp_send_json_success($result);
    }

    /**
     * Enqueue block editor assets.
     */
    public function enqueue_block_editor_assets() {
        wp_enqueue_script(
            'iceberg-notes-editor',
            ICEBERG_ADDONS_URL . 'public/js/notes-editor.js',
            array('wp-plugins', 'wp-edit-post', 'wp-element', 'wp-components'),
            ICEBERG_ADDONS_VERSION,
            true
        );
    }

    /**
     * Redirect single note pages to home.
     */
    public function redirect_single_notes() {
        if (is_singular('iceberg_note')) {
            wp_redirect(home_url());
            exit;
        }
    }
}