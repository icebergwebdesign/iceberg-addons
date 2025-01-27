<?php
/**
 * Template for the Image Attributes settings page
 *
 * @package    IcebergAddOns
 * @since      1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="icebergaddons-wrapper">
    <div class="icebergaddons-header">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <div class="notice notice-info">
            <p>
                <?php _e('This feature automatically generates alt text for images during upload based on the filename. You can also bulk generate alt text for existing images using the Media Library bulk actions.', 'iceberg-addons'); ?>
            </p>
        </div>
    </div>
    <div class="icebergaddons-content">
        <div class="icebergaddons-card icebergaddons-card--half">
            <form action="options.php" method="post">
                <?php
                settings_fields(Iceberg_Image_Attributes::OPTION_NAME);
                do_settings_sections('iceberg-image-attributes');
                submit_button('Save Settings');
                ?>
            </form>            
            </div>
            <div class="icebergaddons-card icebergaddons-card--quarter">            
                <h3><?php _e('How It Works', 'iceberg-addons'); ?></h3>
                <ul>
                    <li><?php _e('Images are processed automatically during upload', 'iceberg-addons'); ?></li>
                    <li><?php _e('Alt text is generated from the filename', 'iceberg-addons'); ?></li>
                    <li><?php _e('Special characters and file extensions are removed', 'iceberg-addons'); ?></li>
                    <li><?php _e('Text is properly formatted for readability', 'iceberg-addons'); ?></li>
                </ul>
            </div>

            <div class="icebergaddons-card icebergaddons-card--quarter">
                <h3><?php _e('Bulk Processing', 'iceberg-addons'); ?></h3>
                <p>
                    <?php _e('To generate alt text for existing images:', 'iceberg-addons'); ?>
                </p>
                <ol>
                    <li><?php _e('Go to Media Library', 'iceberg-addons'); ?></li>
                    <li><?php _e('Switch to List View', 'iceberg-addons'); ?></li>
                    <li><?php _e('Select images to process', 'iceberg-addons'); ?></li>
                    <li><?php _e('Choose "Generate Alt Text" from Bulk Actions', 'iceberg-addons'); ?></li>
                </ol>
            </div>        
    </div>
</div>
