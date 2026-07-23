<?php
if ( ! defined( 'ABSPATH' ) ) exit; 
add_action("wp_dashboard_setup", "wpa_dashboard_widget");
function wpa_dashboard_widget()
{
    if ( current_user_can('administrator') ) {
        add_meta_box(
            'wpa_dashboard_widget',
            'NO SPAM Anti Spam Statistics',
            'wpa_dashboard_widget_function',
            'dashboard',
            'side',
            'high'
        );
    }
}
 
function wpa_dashboard_widget_function(){
    $currentStats = json_decode( get_option( 'wpa_stats', '{}' ), true );
    $total = isset( $currentStats['total'] ) ? $currentStats['total'] : array();

    // Fallback values
    $today_count  = 0;
    $week_count   = 0;
    $month_count  = 0;
    $all_time     = isset( $total['all_time'] ) ? $total['all_time'] : 0;

    if ( isset($total['today']['date']) && function_exists('wpa_check_date') ) {
        if ( wpa_check_date( $total['today']['date'], 'today' ) ) {
            $today_count = $total['today']['count'];
        }
    }
    if ( isset($total['week']['date']) ) {
        if ( wpa_check_date( $total['week']['date'], 'week' ) ) {
            $week_count = $total['week']['count'];
        }
    }
    if ( isset($total['month']['date']) ) {
        if ( wpa_check_date( $total['month']['date'], 'month' ) ) {
            $month_count = $total['month']['count'];
        }
    }

    echo '<ul style="list-style:disc; padding-left:20px; margin:0;">';
    echo '<li><strong>Today:</strong> ' . intval($today_count) . '</li>';
    echo '<li><strong>This Week:</strong> ' . intval($week_count) . '</li>';
    echo '<li><strong>This Month:</strong> ' . intval($month_count) . '</li>';
    echo '<li><strong>All Time:</strong> ' . intval($all_time) . '</li>';
    echo '</ul>';
    echo '<p style="text-align:right;"><a href="' . admin_url('admin.php?page=wp-armour&tab=stats') . '">View full statistics</a></p>';
}