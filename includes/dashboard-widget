<?php
// Iceberg dashboard widget
function iceberg_dashboard_widget_backup() {        
    echo 
        'Iceberg Web Design is here for you! Email us at <a href="mailto:support@icebergwebdesign.com">support@icebergwebdesign.com</a> or call us at <a href="tel:763-350-8762">763-350-8762</a> for questions and assistance with your website.<br>
        <br>
        <strong>Resources:</strong><br>
        <a href="https://dashboard.icebergwebdesign.com/customer-tools/page-builder/" target="_blank" rel="noopener">Page Builder Instructional Video</a><br>
        <a href="https://dashboard.icebergwebdesign.com/contact/" target="_blank" rel="noopener">Submit a Support Ticket</a><br>
        <br>
        <img src="https://dashboard.icebergwebdesign.com/wp-content/uploads/2022/07/iceberg-web-design-black.png" width="250"></a>';
}
    
function add_iceberg_dashboard_widget_plugin() {  
    if ( ! function_exists( 'add_iceberg_dashboard_widget' ) ) {
        //add_action('wp_dashboard_setup', 'add_iceberg_dashboard_backup');
        add_iceberg_dashboard_widget_backup();
    }
    else{        
        add_action( 'admin_notices', 'iceberg_addons_dashboard_widget_admin_notice_warn' );
    }
}

add_action('wp_dashboard_setup', 'add_iceberg_dashboard_widget_plugin');    

function add_iceberg_dashboard_widget_backup() {
    wp_add_dashboard_widget(
        'iceberg_dashboard_widget_backup',		//widget slug
        'Need Help?',     //title
        'iceberg_dashboard_widget_backup'	//display function
    );

    // Globalize the metaboxes array, this holds all the widgets for wp-admin

    global $wp_meta_boxes;

    // Get the regular dashboard widgets array 
    // (which has our new widget already but at the end)

    $normal_dashboard = $wp_meta_boxes['dashboard']['normal']['core'];

    // Backup and delete our new dashboard widget from the end of the array

    $iceberg_widget_backup = array( 'iceberg_dashboard_widget_backup' => $normal_dashboard['iceberg_dashboard_widget_backup'] );
    unset( $normal_dashboard['iceberg_dashboard_widget_backup'] );

    // Merge the two arrays together so our widget is at the beginning

    $sorted_dashboard = array_merge( $iceberg_widget_backup, $normal_dashboard );

    // Save the sorted array back into the original metaboxes 

    $wp_meta_boxes['dashboard']['normal']['core'] = $sorted_dashboard;
}

function iceberg_addons_dashboard_widget_admin_notice_warn() {
    echo "<div class='notice notice-warning is-dismissible'>
          <p>Important: You have installed the new Iceberg AddOns, please remove the related code in theme's function.php</p>
          </div>"; 
    }
