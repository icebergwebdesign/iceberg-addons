<?php
/**
 * Autoload classes based on the PSR-4 standard.
 */

spl_autoload_register(function ($class) {

    // Define the base directory for the namespace prefix
    $base_dir = ICEBERG_ADDONS_DIR . 'src/';

    // Get the relative class name (strip the namespace prefix)
    $relative_class = str_replace('IcebergAddons\\', '', $class);

    // Replace namespace separators with directory separators and append with .php
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    // If the file exists, require it
    if (file_exists($file)) {
        require $file;
    }
});
