<?php

namespace IcebergAddons\Admin;

class IcebergBlogWidget {

    /**
     * Registers the Iceberg Blog widget.
     */
    public static function register_blog_widget() {
        wp_add_dashboard_widget(
            'iceberg_blog_widget',
            'Iceberg Web Design Blog',
            [__CLASS__, 'display_blog_widget']
        );

        // Move this widget to the second position
        HelpDashboardWidget::move_widget_to_position('iceberg_blog_widget', 2);
    }

    /**
     * Displays the blog widget content.
     */
    public static function display_blog_widget() {
        // The RSS feed URL
        $rss_url = 'https://www.icebergwebdesign.com/feed/';

        // Fetch the RSS feed
        $rss = fetch_feed($rss_url);

        // Check if the RSS feed is valid
        if (is_wp_error($rss)) {
            echo '<p>Could not fetch the RSS feed. Please try again later.</p>';
            return;
        }

        // Limit the number of items displayed
        $max_items = $rss->get_item_quantity(5); // Display 5 news items
        $rss_items = $rss->get_items(0, $max_items);

        // Display the RSS feed items in a format similar to the WordPress news widget
        echo '<div class="rss-widget">';
        echo '<ul>';

        if (!$rss_items) {
            echo '<li>No news items found.</li>';
        } else {
            foreach ($rss_items as $item) {
                $title = esc_html($item->get_title());
                $link = esc_url($item->get_permalink()) . '?utm_source=dashboard'; // Add UTM tracking
                $date = $item->get_date('F j, Y');
                $description = wp_trim_words($item->get_description(), 25, '...');
                
                echo '<li>';
                echo '<a class="rsswidget" href="' . $link . '" target="_blank">' . $title . '</a>';
                echo '<span class="rss-date">' . $date . '</span>';
                echo '<div class="rss-summary">' . $description . '</div>';
                echo '</li>';
            }
        }

        echo '</ul>';
        echo '</div>';
    }
}
