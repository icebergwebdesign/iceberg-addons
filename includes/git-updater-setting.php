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