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