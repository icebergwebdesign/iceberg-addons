<?php
/*
 * Iceberg Freshdesk Widget
 * source: https://www.icebergwebdesign.com
 * The freshdesk widget will only appear to user with capability "edit_post"
 * You can customize the widget appearance directly from freshdesk
 */


// register the script
add_action( 'wp_loaded', 'iceberg_freshdesk_widget');
// enqueue the script
add_action( 'wp_enqueue_scripts', 'iceberg_freshdesk_widget');
add_action( 'admin_enqueue_scripts', 'iceberg_freshdesk_widget');


if ( ! function_exists( 'iceberg_freshdesk_widget' ) ) {
    
    function iceberg_freshdesk_widget(){    
        $search_engine_visibility = get_option('blog_public');//disable chat function when site is undiscoverable from search engine when usually is use when testing, build, or staging
        if ($search_engine_visibility != '0') {    
            if(current_user_can( 'edit_posts' )) {
                wp_enqueue_script( 'iceberg-freshdesk', ICEBERG_ADDONS_PLUGIN_URL . 'assets/js/freshdesk-widget.js', '', '1.0', true );
                wp_enqueue_script( 'iceberg-freshdesk-ext', 'https://widget.freshworks.com/widgets/63000001724.js', '', '1.0', true );
                wp_enqueue_style( 'iceberg-freshdesk-css', ICEBERG_ADDONS_PLUGIN_URL . 'assets/css/freshdesk-style.css' );
            }
        }
    };
};