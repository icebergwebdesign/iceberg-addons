<?php
class Iceberg_Help_Dashboard_Widget {

/**
 * Registers the help dashboard widget.
 */
public static function register_help_dashboard_widget() {
    wp_add_dashboard_widget(
        'iceberg_help_widget',
        'Need Help?',
        [__CLASS__, 'display_help_widget']
    );

    // Move this widget to the top position
    self::move_widget_to_position('iceberg_help_widget', 1);
}

/**
 * Displays the help dashboard widget content.
 */
public static function display_help_widget() {
    echo '
    <p>Iceberg Web Design is here for you! Email us at <a href="mailto:support@icebergwebdesign.com">support@icebergwebdesign.com</a> or call us at <a href="tel:763-350-8762">763-350-8762</a> for questions and assistance with your website.</p>
    <p><strong>Resources:</strong></p>
    <ul>
        <li><a href="https://dashboard.icebergwebdesign.com/customer-tools/page-builder/" target="_blank" rel="noopener">Page Builder Instructional Video</a></li>
        <li><a href="https://dashboard.icebergwebdesign.com/contact/" target="_blank" rel="noopener">Submit a Support Ticket</a></li>
    </ul>
    <br>
    <img src="https://dashboard.icebergwebdesign.com/wp-content/uploads/2022/07/iceberg-web-design-black.png" width="250" alt="Iceberg Web Design Logo">
    ';
}

/**
 * Moves the specified dashboard widget to a given position.
 *
 * @param string $widget_id The widget slug.
 * @param int $position The position to move the widget to.
 */
public static function move_widget_to_position($widget_id, $position) {
    global $wp_meta_boxes;

    if (isset($wp_meta_boxes['dashboard']['normal']['core'][$widget_id])) {
        $widget = [$widget_id => $wp_meta_boxes['dashboard']['normal']['core'][$widget_id]];
        unset($wp_meta_boxes['dashboard']['normal']['core'][$widget_id]);

        // Get all widgets
        $remaining_widgets = $wp_meta_boxes['dashboard']['normal']['core'];

        // Insert this widget at the desired position
        $wp_meta_boxes['dashboard']['normal']['core'] = array_slice($remaining_widgets, 0, $position - 1, true) +
            $widget + array_slice($remaining_widgets, $position - 1, null, true);
    }
}
}