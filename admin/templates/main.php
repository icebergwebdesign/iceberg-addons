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
    </div>
    <div class="icebergaddons-content">
    <div class="icebergaddons-card--full icebergaddons-card">
        <h1>We Are Website Developers Who Answer the Phone!</h1>
        <p>At Iceberg Web Design, our primary core values drive us. We are committed, passionate, balanced, team players. Our top-notch, responsive customer service displays our commitment to you, and we are passionate about solving your digital problems. As a result, we form lasting relationships and ensure a collaborative approach to web design and marketing projects. This consistently leads to successful projects and clients who confidently recommend us.</p>
    </div>
    
    <div class="icebergaddons-card--half icebergaddons-card">
        <h2>Need Help?</h2>
        <p>Iceberg Web Design is here for you! Email us at <a href="mailto:support@icebergwebdesign.com">support@icebergwebdesign.com</a> or call us at <a href="tel:763-350-8762">763-350-8762</a> for questions and assistance with your website.</p>
        <p><strong>Resources:</strong></p>
        <ul>
            <li><a href="https://dashboard.icebergwebdesign.com/customer-tools/page-builder/" target="_blank" rel="noopener">Page Builder Instructional Video</a></li>
            <li><a href="https://dashboard.icebergwebdesign.com/contact/" target="_blank" rel="noopener">Submit a Support Ticket</a></li>
        </ul>
        <br>
        <img src="https://dashboard.icebergwebdesign.com/wp-content/uploads/2022/07/iceberg-web-design-black.png" width="250" alt="Iceberg Web Design Logo">
    </div>
        <div class="icebergaddons-card icebergaddons-card--half">
            <h2>Latest Blog from Iceberg Web Design</h2>
            <?php
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

            // Display the RSS feed items
            echo '<div class="rss-widget">';
            echo '<ul>';

            if (!$rss_items) {
                echo '<li>No news items found.</li>';
            } else {
                foreach ($rss_items as $item) {
                    $title = esc_html($item->get_title());
                    $link = esc_url($item->get_permalink()) . '?utm_source=dashboard';
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
            ?>
        </div>
    </div>
    
</div>
