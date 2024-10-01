<?php

namespace IcebergAddons\Utils;

class GitUpdater {

    /**
     * Initializes the GitUpdater by adding necessary hooks and filters.
     */
    public static function init() {
        // Add the filter to set Git updater options
        add_filter('gu_set_options', [__CLASS__, 'set_git_updater_options']);

        // Show admin notices if something is wrong with Git Updater
        add_action('admin_notices', [__CLASS__, 'admin_notice']);
    }

    /**
     * Retrieves GitHub access tokens from the database or environment variables.
     *
     * @return array Git updater options including GitHub access tokens.
     */
    public static function set_git_updater_options() {
        return [
            'github_access_token' => self::get_github_access_token(),
            'git-updater'         => self::get_github_access_token(),
            'iceberg-addons'      => self::get_github_access_token(),
            'iceberg-custom-elements' => self::get_github_access_token(),
            'custom-swatches-for-iris-color-picker' => self::get_github_access_token(),
        ];
    }

    /**
     * Retrieves the GitHub access token from environment variables or WordPress options.
     *
     * @return string GitHub access token.
     */
    private static function get_github_access_token() {
        // Try to get the token from an environment variable (recommended)
        $token = getenv('GITHUB_ACCESS_TOKEN');

        // Fallback to getting the token from the WordPress options table
        if (!$token) {
            $token = get_option('github_access_token', '');
        }

        // If no token is set, return an empty string
        return $token ? sanitize_text_field($token) : '';
    }

    /**
     * Checks if Git Updater plugin is installed by verifying if the plugin exists.
     *
     * @return bool True if the plugin is installed, false otherwise.
     */
    public static function is_git_updater_installed(): bool {
        return self::check_plugin_installed('git-updater/git-updater.php');
    }

    /**
     * Checks if Git Updater plugin is active.
     *
     * @return bool True if the plugin is active, false otherwise.
     */
    public static function is_git_updater_active(): bool {
        return self::check_plugin_active('git-updater/git-updater.php');
    }

    /**
     * Checks if a plugin is installed by getting all plugins from the plugins directory.
     *
     * @param string $plugin_slug The plugin slug (e.g., 'plugin-folder/plugin-file.php').
     * @return bool True if the plugin is installed, false otherwise.
     */
    private static function check_plugin_installed(string $plugin_slug): bool {
        $installed_plugins = get_plugins();
        return array_key_exists($plugin_slug, $installed_plugins);
    }

    /**
     * Checks if a plugin is active.
     *
     * @param string $plugin_slug The plugin slug (e.g., 'plugin-folder/plugin-file.php').
     * @return bool True if the plugin is active, false otherwise.
     */
    private static function check_plugin_active(string $plugin_slug): bool {
        return is_plugin_active($plugin_slug);
    }

    /**
     * Displays an admin notice if Git Updater is not installed or activated.
     */
    public static function admin_notice() {
        $installed = self::is_git_updater_installed();
        $active = self::is_git_updater_active();
        $class = 'notice notice-error';

        if ($installed && $active) {
            // Git Updater is installed and active, no notice needed
            return;
        } elseif ($installed && !$active) {
            // Git Updater is installed but not active
            $message = __('Iceberg AddOns requires Git Updater plugin to be activated to receive updates.', 'iceberg-addons');
            printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), esc_html($message));
        } else {
            // Git Updater is not installed
            $message = __('Iceberg AddOns requires <a href="https://git-updater.com/git-updater/" target="_blank">Git Updater plugin</a>.', 'iceberg-addons');
            printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), $message);
        }
    }
}
