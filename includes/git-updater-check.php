<?php

// Check if needed functions exists - if not, require them
if ( ! function_exists( 'get_plugins' ) || ! function_exists( 'is_plugin_active' ) ) {
    require_once ABSPATH . 'wp-admin/includes/plugin.php';
}

/**
 * Checks if Git Updater is installed
 *
 * @return bool
 */
function is_git_updater_installed(): bool {
    if ( check_plugin_installed( 'git-updater/git-updater.php' ) ) {
        return true;
    }
    return false;
}

/**
 * Check if Git Updater is activated
 *
 * @return bool
 */
function is_git_updater_active(): bool {
    if ( check_plugin_active( 'git-updater/git-updater.php' ) ) {
        return true;
    }

    return false;
}

/**
 * Check if plugin is installed by getting all plugins from the plugins dir
 *
 * @param $plugin_slug
 *
 * @return bool
 */
function check_plugin_installed( $plugin_slug ): bool {
    $installed_plugins = get_plugins();

    return array_key_exists( $plugin_slug, $installed_plugins ) || in_array( $plugin_slug, $installed_plugins, true );
}

/**
 * Check if plugin is installed
 *
 * @param string $plugin_slug
 *
 * @return bool
 */
function check_plugin_active( $plugin_slug ): bool {
    if ( is_plugin_active( $plugin_slug ) ) {
        return true;
    }

    return false;
}

/**
 * Show admin notices if something wrong
 *
 */
function iceberg_gitupdater_admin_notice() {
    $installed = is_git_updater_installed();
    $active    = is_git_updater_active();
	$class = 'notice notice-error';
    if ( $installed && $active ) {
        //$class = 'notice notice-warning is-dismissible';
        //$message = __( "Don't forget to check Git Updater setting to make sure Iceberg AddOns receive updates.", 'iceberg-addons' );
        //printf( '<div data-dismissible="iceberg-addons-git-updater-forever" class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) ); 
        return;
    }
    elseif ( $installed  && !$active ){
        //var_dump ($installed);
        //var_dump ($active);   
        $message = __( 'Iceberg AddOns requires Git Updater plugin activated to receive updates.', 'iceberg-addons' );
        printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) ); 
    }
    else{
        //var_dump ($installed);
        //var_dump ($active);        
        $message = __( 'Iceberg AddOns requires <a href="https://git-updater.com/git-updater/" target="_blank">Git Updater plugin</a>.', 'iceberg-addons' );      
        printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ),  $message  );   
    }
}
add_action( 'admin_notices', 'iceberg_gitupdater_admin_notice' );