<?php
/**
 * Hide Git Updater Setting Menu
 *
 */
//add_filter( 'gu_hide_settings', '__return_true' );

/**
 * Set Git Updater Access Token
 *
 */
add_filter( 'gu_set_options',
    function () {
        return array( 
            'github_access_token' => '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',
            'iceberg-addons'    => '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',
            'iceberg-custom-elements'    => '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',
            'custom-swatches-for-iris-color-picker'    => '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',
        );
    } );