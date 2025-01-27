<?php
/**
 * Handles automatic image attribute management
 *
 * @package    IcebergAddOns
 * @since      1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class Iceberg_Image_Attributes {
    /**
     * Option name for settings
     */
    const OPTION_NAME = 'iceberg_image_attributes';

    /**
     * Initialize the class
     */
    public function __construct() {
        $this->init();
    }

    /**
     * Initialize hooks and filters
     */
    public function init() {
        // Admin menu
        add_action('admin_menu', array($this, 'add_admin_menu'));
        
        // Register settings
        add_action('admin_init', array($this, 'register_settings'));
        
        // Image upload hooks
        add_action('add_attachment', array($this, 'process_image_upload'));
        add_filter('wp_generate_attachment_metadata', array($this, 'update_image_metadata'), 10, 2);
        
        // Add bulk action to media library
        add_filter('bulk_actions-upload', array($this, 'add_bulk_actions'));
        add_filter('handle_bulk_actions-upload', array($this, 'handle_bulk_actions'), 10, 3);
        
        // Admin notices
        add_action('admin_notices', array($this, 'display_bulk_action_notice'));
    }

    /**
     * Add submenu under Iceberg Web Design
     */
    public function add_admin_menu() {
        // Add submenu to Iceberg Web Design menu
        add_submenu_page(
            'iceberg-web-design',
            'Image Attributes',
            'Image Attributes',
            'manage_options',
            'iceberg-image-attributes',
            array($this, 'render_settings_page')
        );
    }

    /**
     * Register plugin settings
     */
    public function register_settings() {
        register_setting(
            self::OPTION_NAME,
            self::OPTION_NAME,
            array(
                'type' => 'object',
                'default' => array(
                    'auto_alt_text' => '1',
                ),
                'sanitize_callback' => array($this, 'sanitize_settings')
            )
        );

        add_settings_section(
            'iceberg_image_attributes_section',
            'Image Attributes Settings',
            array($this, 'render_section_info'),
            'iceberg-image-attributes'
        );

        add_settings_field(
            'auto_alt_text',
            'Auto Generate Alt Text',
            array($this, 'render_auto_alt_text_field'),
            'iceberg-image-attributes',
            'iceberg_image_attributes_section'
        );
    }

    /**
     * Render the settings page
     */
    public function render_settings_page() {
        if (!current_user_can('manage_options')) {
            return;
        }
        
        // Load the template
        require_once ICEBERG_ADDONS_PATH . 'admin/templates/image-attributes.php';
    }

    /**
     * Render section information
     */
    public function render_section_info() {
        echo '<p>Configure automatic image attribute settings.</p>';
    }

    /**
     * Render auto alt text toggle field
     */
    public function render_auto_alt_text_field() {
        $options = get_option(self::OPTION_NAME);
        $value = isset($options['auto_alt_text']) ? $options['auto_alt_text'] : '1';
        ?>
        <fieldset>
            <label>
                <input type="checkbox" name="<?php echo self::OPTION_NAME; ?>[auto_alt_text]"
                       value="1" <?php checked('1', $value); ?>>
                Automatically generate alt text from image filename
            </label>
            <p class="description">
                When enabled, alt text will be automatically generated from the image filename during upload.
            </p>
        </fieldset>
        <?php
    }

    /**
     * Sanitize settings before save
     *
     * @param array $input The raw input array
     * @return array The sanitized input array
     */
    public function sanitize_settings($input) {
        if (!is_array($input)) {
            $input = array();
        }

        $sanitized = array();
        
        // For checkboxes, if the value isn't set in POST data, it means it's unchecked
        $sanitized['auto_alt_text'] = '0'; // Default to unchecked
        
        // Only set to '1' if it exists in input AND equals '1'
        if (isset($input['auto_alt_text']) && $input['auto_alt_text'] === '1') {
            $sanitized['auto_alt_text'] = '1';
        }
        
        return $sanitized;
    }

    /**
     * Process image upload
     *
     * @param int $attachment_id The attachment ID
     */
    public function process_image_upload($attachment_id) {
        if (!wp_attachment_is_image($attachment_id)) {
            return;
        }

        $options = get_option(self::OPTION_NAME);
        if (empty($options['auto_alt_text'])) {
            return;
        }

        $this->generate_alt_text($attachment_id);
    }

    /**
     * Generate and set alt text for an image
     *
     * @param int $attachment_id The attachment ID
     */
    public function generate_alt_text($attachment_id) {
        $filename = get_post_field('post_title', $attachment_id);
        
        // Clean the filename
        $alt_text = $this->clean_filename($filename);
        
        // Update the alt text
        update_post_meta($attachment_id, '_wp_attachment_image_alt', $alt_text);
    }

    /**
     * Clean filename for use as alt text
     *
     * @param string $filename The filename to clean
     * @return string Cleaned text
     */
    private function clean_filename($filename) {
        // Remove file extension
        $filename = preg_replace('/\\.[^.\\s]{3,4}$/', '', $filename);
        
        // Replace dashes and underscores with spaces
        $filename = preg_replace('/[-_]+/', ' ', $filename);
        
        // Convert to title case
        $filename = ucwords(strtolower($filename));
        
        return $filename;
    }

    /**
     * Add bulk action to media library
     *
     * @param array $bulk_actions Existing bulk actions
     * @return array Modified bulk actions
     */
    public function add_bulk_actions($bulk_actions) {
        $bulk_actions['generate_alt_text'] = __('Generate Alt Text', 'iceberg-addons');
        return $bulk_actions;
    }

    /**
     * Handle bulk action
     *
     * @param string $redirect_to Redirect URL
     * @param string $doaction Action name
     * @param array $post_ids Selected post IDs
     * @return string Modified redirect URL
     */
    public function handle_bulk_actions($redirect_to, $doaction, $post_ids) {
        if ($doaction !== 'generate_alt_text') {
            return $redirect_to;
        }

        foreach ($post_ids as $post_id) {
            if (wp_attachment_is_image($post_id)) {
                $this->generate_alt_text($post_id);
            }
        }

        $redirect_to = add_query_arg('bulk_alt_text_generated', count($post_ids), $redirect_to);
        return $redirect_to;
    }

    /**
     * Update image metadata
     *
     * @param array $metadata Attachment metadata
     * @param int $attachment_id Attachment ID
     * @return array Modified metadata
     */
    public function update_image_metadata($metadata, $attachment_id) {
        if (wp_attachment_is_image($attachment_id)) {
            $this->process_image_upload($attachment_id);
        }
        return $metadata;
    }

    /**
     * Display admin notice for bulk actions
     */
    public function display_bulk_action_notice() {
        if (!empty($_REQUEST['bulk_alt_text_generated'])) {
            $count = intval($_REQUEST['bulk_alt_text_generated']);
            $message = sprintf(
                _n(
                    'Alt text generated for %s image.',
                    'Alt text generated for %s images.',
                    $count,
                    'iceberg-addons'
                ),
                number_format_i18n($count)
            );
            
            echo '<div class="notice notice-success is-dismissible"><p>' .
                 esc_html($message) . '</p></div>';
        }
    }
}