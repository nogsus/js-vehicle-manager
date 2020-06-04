<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

// today
function jsvehiclemanager_dashboard_widgets_todaystats() {

    wp_add_dashboard_widget(
            'jsvehiclemanager_dashboard_widgets_todaystats', // Widget slug.
            __('Today Vehicles Stats', 'js-vehicle-manager'), // Title.
            'jsvehiclemanager_dashboard_widget_function_todaystats' // Display function.
    );
}

add_action('wp_dashboard_setup', 'jsvehiclemanager_dashboard_widgets_todaystats');
function jsvehiclemanager_dashboard_widget_function_todaystats() {
    jsvehiclemanager::addStyleSheets();
    $html = JSVEHICLEMANAGERincluder::getJSModel('jsvehiclemanager')->getTodayStatsForWidget();
    echo $html;
}

// total
function jsvehiclemanager_dashboard_widgets_totalstats() {

    wp_add_dashboard_widget(
            'jsvehiclemanager_dashboard_widgets_totalstats', // Widget slug.
            __('Total Vehicles Stats', 'js-vehicle-manager'), // Title.
            'jsvehiclemanager_dashboard_widget_function_totalstats' // Display function.
    );
}

add_action('wp_dashboard_setup', 'jsvehiclemanager_dashboard_widgets_totalstats');
function jsvehiclemanager_dashboard_widget_function_totalstats() {
    jsvehiclemanager::addStyleSheets();
    $html = JSVEHICLEMANAGERincluder::getJSModel('jsvehiclemanager')->getTotalStatsForWidget();
    echo $html;
}

// Last week make graph
function jsvehiclemanager_dashboard_widgets_lastweek_makes_graph() {

    wp_add_dashboard_widget(
            'jsvehiclemanager_dashboard_widgets_lastweek_makes_graph', // Widget slug.
            __('Last Week Top Five Vehicle By Makes', 'js-vehicle-manager'), // Title.
            'jsvehiclemanager_dashboard_widget_function_lastweek_makes_graph' // Display function.
    );
}

add_action('wp_dashboard_setup', 'jsvehiclemanager_dashboard_widgets_lastweek_makes_graph');
function jsvehiclemanager_dashboard_widget_function_lastweek_makes_graph() {
    jsvehiclemanager::addStyleSheets();
    $html = JSVEHICLEMANAGERincluder::getJSModel('jsvehiclemanager')->getLastWeekVehicleByMakeGraphForWidget();
    echo $html;
}

// Latest vehicles
function jsvehiclemanager_dashboard_widgets_latest_vehicles() {

    wp_add_dashboard_widget(
            'jsvehiclemanager_dashboard_widgets_latest_vehicles', // Widget slug.
            __('Newly Arrived Vehicles', 'js-vehicle-manager'), // Title.
            'jsvehiclemanager_dashboard_widget_function_latest_vehicles' // Display function.
    );
}

add_action('wp_dashboard_setup', 'jsvehiclemanager_dashboard_widgets_latest_vehicles');
function jsvehiclemanager_dashboard_widget_function_latest_vehicles() {
    jsvehiclemanager::addStyleSheets();
    $html = JSVEHICLEMANAGERincluder::getJSModel('jsvehiclemanager')->getLatestVehiclesForWidget();
    echo $html;
}

// Latest sellers
function jsvehiclemanager_dashboard_widgets_latest_sellers() {

    wp_add_dashboard_widget(
            'jsvehiclemanager_dashboard_widgets_latest_sellers', // Widget slug.
            __('Newly Registered Users', 'js-vehicle-manager'), // Title.
            'jsvehiclemanager_dashboard_widget_function_latest_sellers' // Display function.
    );
}

add_action('wp_dashboard_setup', 'jsvehiclemanager_dashboard_widgets_latest_sellers');
function jsvehiclemanager_dashboard_widget_function_latest_sellers() {
    jsvehiclemanager::addStyleSheets();
    $html = JSVEHICLEMANAGERincluder::getJSModel('jsvehiclemanager')->getLatestSellersForWidget();
    echo $html;
}

?>