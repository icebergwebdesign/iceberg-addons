<?php
/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * @package    IcebergAddOns
 */

class Iceberg_Addons_Main {
    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @var Iceberg_Addons_Loader
     */
    protected $loader;

    /**
     * Initialize the plugin.
     */
    public function __construct() {
        // Check WordPress version
        global $wp_version;
        if (version_compare($wp_version, '5.8', '<')) {
            add_action('admin_notices', [$this, 'display_version_notice']);
            return;
        }

        // Initialize components
        $this->load_dependencies();
        $this->init_components();
    }

    /**
     * Load the required dependencies for this plugin.
     */
    private function load_dependencies() {
        // Core plugin classes
        require_once ICEBERG_ADDONS_PATH . 'includes/class-loader.php';
        require_once ICEBERG_ADDONS_PATH . 'includes/class-notes.php';
        require_once ICEBERG_ADDONS_PATH . 'includes/class-freshdesk-widget.php';
        require_once ICEBERG_ADDONS_PATH . 'includes/class-help-dashboard-widget.php';
        require_once ICEBERG_ADDONS_PATH . 'includes/class-blog-dashboard-widget.php';
        require_once ICEBERG_ADDONS_PATH . 'includes/class-image-attributes.php';

        $this->loader = new Iceberg_Addons_Loader();
    }

    /**
     * Initialize plugin components and register hooks.
     */
    private function init_components() {
        // Add main menu
        add_action('admin_menu', [$this, 'add_main_menu']);

        // Initialize Notes
        $notes = new Iceberg_Addons_Notes();
        $notes->register_hooks();
        add_action('init', [$notes, 'register_post_type']);

        // Initialize Freshdesk Widget
        $freshdesk = new Iceberg_Addons_Freshdesk_Widget();
        $freshdesk->register_hooks();

        // Initialize Dashboard Widgets
        add_action('wp_dashboard_setup', function() {
            // Register Help Dashboard Widget first (position 1)
            Iceberg_Help_Dashboard_Widget::register_help_dashboard_widget();
            
            // Register Blog Dashboard Widget second (position 2)
            Iceberg_Blog_Dashboard_Widget::register_blog_dashboard_widget();
        });

        // Initialize Image Attributes
        new Iceberg_Image_Attributes();

        // Add loader actions
        $this->loader->add_actions();
    }

    /**
     * Add the main Iceberg Web Design menu
     */
    /**
     * Get the base64 encoded SVG icon
     *
     * @return string
     */
    private function get_menu_icon() {
        $svg = file_get_contents(ICEBERG_ADDONS_PATH . 'assets/images/iceberg-logo.svg');
        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }

    /**
     * Add the main Iceberg Web Design menu
     */
    public function add_main_menu() {
        add_menu_page(
            'Iceberg Web Design',
            'Iceberg',
            'manage_options',
            'iceberg-web-design',
            [$this, 'render_main_page'],
            $this->get_menu_icon(),
            30
        );

        // Add custom CSS to style the menu icon like a Dashicon
        add_action('admin_head', function() {
            ?>
            <style>
                /* Base icon styles */
                #adminmenu .toplevel_page_iceberg-web-design .wp-menu-image {
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }
                
                /* SVG icon styling */
                #adminmenu .toplevel_page_iceberg-web-design .wp-menu-image img {
                    width: 20px;
                    height: 20px;
                    padding: 7px 0;
                }

                /* Color states like Dashicons */
                #adminmenu .toplevel_page_iceberg-web-design .wp-menu-image img {
                    opacity: 0.6;
                    filter: grayscale(100%) brightness(200%);
                    transition: all 0.1s ease-in-out;
                }

                #adminmenu .toplevel_page_iceberg-web-design:hover .wp-menu-image img,
                #adminmenu .toplevel_page_iceberg-web-design.current .wp-menu-image img,
                #adminmenu .toplevel_page_iceberg-web-design.wp-has-current-submenu .wp-menu-image img {
                    opacity: 1;
                    filter: grayscale(0%) brightness(100%);
                }

                /* Dark mode support */
                .admin-color-light #adminmenu .toplevel_page_iceberg-web-design .wp-menu-image img {
                    filter: grayscale(100%) brightness(80%);
                }
                
                .admin-color-modern #adminmenu .toplevel_page_iceberg-web-design .wp-menu-image img {
                    filter: grayscale(100%) brightness(200%);
                }
            </style>
            <?php
        });
    }

    /**
     * Render the main plugin page
     */
    public function render_main_page() {
        if (!current_user_can('manage_options')) {
            return;
        }
        
        
        // Load the template
        require_once ICEBERG_ADDONS_PATH . 'admin/templates/main.php';
    }

    /**
     * Display version notice if WordPress version is too old.
     */
    public function display_version_notice() {
        echo '<div class="notice notice-error"><p>Iceberg AddOns requires WordPress 5.8 or later.</p></div>';
    }

    /**
     * Run the plugin.
     */
    public function run() {
        do_action('iceberg_addons_init');
    }
}