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
            'git-updater'    => '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',
            'iceberg-addons'    => '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',
            'iceberg-custom-elements'    => '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',
            'custom-swatches-for-iris-color-picker'    => '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',
        );
    } );