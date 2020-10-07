<?php

/**
 * @package WP Vehicle Manager
 * @version 1.1.6
 */
/*
  Plugin Name: WP Vehicle Manager
  Plugin URI: https://wpvehiclemanager.com/
  Description: WP Vehicle Manager is Word Press most comprehensive and easist show room plugin.
  Author: Joom Sky
  Version: 1.1.6
  Text Domain: js-vehicle-manager
  Author URI: https://wpvehiclemanager.com/
 */

if (!defined('ABSPATH'))
    die('Restricted Access');

class jsvehiclemanager {

    public static $_path;
    public static $_pluginpath;
    public static $_data; /* data[0] for list , data[1] for total paginition ,data[2] fieldsorderring */
    public static $_pageid;
    public static $_config;
    public static $_sorton;
    public static $_sortorder;
    public static $_ordering;
    public static $_msg;
    public static $_error_flag;
    public static $_error_flag_message;
    public static $_js_login_redirct_link;
    public static $_car_manager_theme;
    public static $_currentversion;
    public static $_active_addons;
    public static $_addon_query;
    public static $_db;
    public static $_wpprefixforuser;

    function __construct() {
        // to check what addons are active and create an array.
        $plugin_array = get_option('active_plugins');
        $addon_array = array();
        foreach ($plugin_array as $key => $value) {
            $plugin_name = pathinfo($value, PATHINFO_FILENAME);
            if(strstr($plugin_name, 'js-vehicle-manager-')){
                $addon_array[] = str_replace('js-vehicle-manager-', '', $plugin_name);
            }
        }
        self::$_active_addons = $addon_array;
        // above code is its right place

        self::includes();
        global $wpdb;
        self::$_path = plugin_dir_path(__FILE__);
        self::$_pluginpath = plugins_url('/', __FILE__);
        self::$_data = array();
        self::$_msg = null;
        self::$_error_flag = null;
        self::$_error_flag_message = null;
        self::$_car_manager_theme = 0;
        self::$_currentversion = '116';
        self::$_addon_query = array('select'=>'','join'=>'','where'=>'');
        self::$_db = $wpdb;

        if(is_multisite()) {
            self::$_wpprefixforuser = $wpdb->base_prefix;
        }else{
            self::$_wpprefixforuser = self::$_db->prefix;
        }

        JSVEHICLEMANAGERincluder::getJSModel('configuration')->getConfiguration();
        register_activation_hook(__FILE__, array($this, 'jsvehiclemanager_activate'));
        register_deactivation_hook(__FILE__, array($this, 'jsvehiclemanager_deactivate'));
        if(version_compare(get_bloginfo('version'),'5.1', '>=')){ //for wp version >= 5.1
            add_action('wp_insert_site', array($this, 'jsvehiclemanager_new_site')); //when new site is added in multisite
        }else{ //for wp version < 5.1
            add_action('wpmu_new_blog', array($this, 'jsvehiclemanager_new_blog'), 10, 6);
        }
        add_filter('wpmu_drop_tables', array($this, 'jsvehiclemanager_delete_site')); //when site is deleted in multisite
        add_action('plugins_loaded', array($this, 'load_plugin_textdomain'));
        add_action('template_redirect', array($this, 'printVehicle'), 5); // Only for the print resume in wordpress
        add_action('admin_init', array($this, 'jsvehiclemanager_activation_redirect'));//for post installation screens
        add_action('jsvehiclemanager_cronjobs_action', array($this,'jsvehiclemanager_cronjobs'));
        add_action('reset_jsvm_aadon_query', array($this,'reset_jsvm_aadon_query') );
        // current theme to handle vehicle manager calls to car manager
        $theme = get_template();
        if($theme == 'car-manager'){
            self::$_car_manager_theme = 1;
        }else{
            define( 'CAR_MANAGER_IMAGE', self::$_pluginpath . 'includes/images' );
        }
    }

    function jsvehiclemanager_activation_redirect(){
        if (get_option('jsvehiclemanager_do_activation_redirect') == true) {
            update_option('jsvehiclemanager_do_activation_redirect',false);
            exit(wp_redirect(admin_url('admin.php?page=jsvm_postinstallation&jsvmlt=stepone')));
        }
    }

    function printVehicle() {
        $printVehicle = JSVEHICLEMANAGERrequest::getVar('jsvmlt');
        if ($printVehicle == 'printvehicle') {
            $vehicleid = JSVEHICLEMANAGERrequest::getVar('jsvehiclemanagerid');
            JSVEHICLEMANAGERincluder::getJSModel('vehicle')->getVehicledetailbyId($vehicleid);
            wp_head();
            if( self::$_car_manager_theme == 1 )
                car_manager_enqueue_scripts();
            else
                self::addStyleSheets();
            JSVEHICLEMANAGERincluder::include_file('printvehicle', 'print');
            exit();
        }
    }

    function jsvehiclemanager_cronjobs(){
        // Send email for the expiry credits packs
        $ck = JSVEHICLEMANAGERincluder::getJSModel('configuration')->getConfigValue('cron_job_alert_key');
        if(in_array('vehiclealert', jsvehiclemanager::$_active_addons)){
            JSVEHICLEMANAGERincluder::getJSModel('vehiclealert')->sendVehicleAlertByAlertType($ck);
        }
        if(in_array('credits', jsvehiclemanager::$_active_addons)){
            jsvehiclemanager::getDataForExpiredCreditPacks();
        }
    }

    public static function getDataForExpiredCreditPacks() {
        $db = new jsvehiclemanagerdb();
        $query = " SELECT phistory.id,phistory.uid
                    FROM `#__js_vehiclemanager_paymenthistory` AS phistory
                    WHERE DATE_ADD(phistory.created, INTERVAL phistory.expireindays DAY) < CURDATE(); ";
        $db->setQuery($query);
        $data = $db->loadObjectList();
        foreach ($data as $row ) {
            JSVEHICLEMANAGERincluder::getJSModel('emailtemplate')->sendMail(2, 2, $row->id);
        }
    }

    function jsvehiclemanager_activate($network_wide = false) {
        include_once 'includes/activation.php';
        if(function_exists('is_multisite') && is_multisite() && $network_wide){
            global $wpdb;
            $blogs = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
            foreach($blogs as $blog_id){
                switch_to_blog( $blog_id );
                JSVEHICLEMANAGERactivation::jsvehiclemanager_activate();
                restore_current_blog();
            }
        }else{
            JSVEHICLEMANAGERactivation::jsvehiclemanager_activate();
        }
        wp_schedule_event(time(), 'daily', 'jsvehiclemanager_cronjobs_action');
        add_option('jsvehiclemanager_do_activation_redirect', true);
    }

    function jsvehiclemanager_new_site($new_site){
        $pluginname = plugin_basename(__FILE__);
        if(is_plugin_active_for_network($pluginname)){
            include_once 'includes/activation.php';
            switch_to_blog($new_site->blog_id);
            JSVEHICLEMANAGERactivation::jsvehiclemanager_activate();
            restore_current_blog();
        }
    }

    function jsvehiclemanager_new_blog($blog_id, $user_id, $domain, $path, $site_id, $meta){
        $pluginname = plugin_basename(__FILE__);
        if(is_plugin_active_for_network($pluginname)){
            include_once 'includes/activation.php';
            switch_to_blog($blog_id);
            JSVEHICLEMANAGERactivation::jsvehiclemanager_activate();
            restore_current_blog();
        }
    }

    function jsvehiclemanager_delete_site($tables){
        include_once 'includes/deactivation.php';
        $tablestodrop = JSVEHICLEMANAGERdeactivation::jsvehiclemanager_tables_to_drop();
        foreach($tablestodrop as $tablename){
            $tables[] = $tablename;
        }
        return $tables;
    }

    function jsvehiclemanager_deactivate($network_wide = false) {
        include_once 'includes/deactivation.php';
        if(function_exists('is_multisite') && is_multisite() && $network_wide){
            global $wpdb;
            $blogs = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
            foreach($blogs as $blog_id){
                switch_to_blog( $blog_id );
                JSVEHICLEMANAGERdeactivation::jsvehiclemanager_deactivate();
                restore_current_blog();
            }
        }else{
            JSVEHICLEMANAGERdeactivation::jsvehiclemanager_deactivate();
        }
    }

    /*
     * Include the required files
     */

    function includes() {
        require_once 'includes/jsvehiclemanagerdb.php';
        require_once 'includes//classes/class.upload.php';
        if (is_admin()) {
            include_once 'includes/jsvehiclemanageradmin.php';
        }
        // include_once 'includes/jsvehiclemanager-wc.php';
        include_once 'includes/jsvehiclemanager-hooks.php';
        include_once 'includes/captcha.php';
        include_once 'includes/recaptchalib.php';
        include_once 'includes/layout.php';
        include_once 'includes/pagination.php';
        include_once 'includes/includer.php';
        include_once 'includes/formfield.php';
        include_once 'includes/request.php';
        include_once 'includes/formhandler.php';
        include_once 'includes/ajax.php';
        require_once 'includes/constants.php';
        require_once 'includes/messages.php';
        include_once 'includes/shortcodes.php';
        include_once 'includes/paramregister.php';
        include_once 'includes/breadcrumbs.php';
        include_once 'includes/dashboardapi.php';
        // Widgets
        require_once 'includes/widgets/widgets.php';
        //include_once 'includes/addon-updater/jsvmupdater.php';
    }

    /*
     * Localization
     */
    public function load_plugin_textdomain() {
        load_plugin_textdomain('js-vehicle-manager', false, dirname(plugin_basename(__FILE__)) . '/languages/');
    }

    /*
     * function for the Style Sheets
     */
    static function addStyleSheets() {
        wp_enqueue_script('jquery');
        wp_enqueue_style('jsauto-tokeninput', jsvehiclemanager::$_pluginpath . 'includes/css/tokeninput.css');
        wp_enqueue_script('jsauto-commonjs', jsvehiclemanager::$_pluginpath . 'includes/js/common.js');
        wp_localize_script('jsauto-commonjs', 'common', array('ajaxurl' => admin_url('admin-ajax.php'),'insufficient_credits' => __('You have insufficient credits, you can not perform this action','js-vehicle-manager') , 'terms_conditions' => __('Please Accept Terms And Conditions So You Can Proceed','js-vehicle-manager'), 'required_fields_error_message' => __('You have not answered all required fields','js-vehicle-manager'), 'wp_vm_nonce' => wp_create_nonce('wp_js_vm_nonce_check')));
        wp_enqueue_script('jsauto-formvalidator', jsvehiclemanager::$_pluginpath . 'includes/js/jquery.form-validator.js');
        wp_enqueue_script('jsauto-tokeninput', jsvehiclemanager::$_pluginpath . 'includes/js/jquery.tokeninput.js');
        // wp_enqueue_script('jsauto-chosen-js', jsvehiclemanager::$_pluginpath . 'includes/js/chosen/chosen.jquery.min.js');
    }

    /*
     * function to get the pageid from the wpoptions
     */
    public static function getPageid() {
        if(jsvehiclemanager::$_pageid != ''){
            return jsvehiclemanager::$_pageid;
        }else{
            $pageid = JSVEHICLEMANAGERrequest::getVar('page_id','GET');
            if($pageid){
                return $pageid;
            }else{ // in case of categories popup
                $module = JSVEHICLEMANAGERrequest::getVar('jsvmme');
                if($module == 'category'){
                    $pageid = JSVEHICLEMANAGERrequest::getVar('page_id','POST');
                    if($pageid)
                        return $pageid;
                }
            }
            $id = 0;
            $db = new jsvehiclemanagerdb();
            $query = "SELECT configvalue FROM `#__js_vehiclemanager_config` WHERE configname = 'default_pageid'";
            $db->setQuery($query);
            $pageid = $db->loadResult();
            if ($pageid)
                $id = $pageid;
            return $id;
        }
    }

    /*
     * function to get the pageid from the wpoptions
     */
    public static function getPageidModule() {
        $id = 0;
        $db = new jsvehiclemanagerdb();
        $query = "SELECT configvalue FROM `#__js_vehiclemanager_config` WHERE configname = 'default_pageid'";
        $db->setQuery($query);
        $pageid = $db->loadResult();
        if ($pageid)
            $id = $pageid;
        return $id;
    }

    public static function setPageID($id) {
        jsvehiclemanager::$_pageid = $id;
    }

    /*
     * function to parse the spaces in given string
     */

    public static function parseSpaces($string) {
        return str_replace('%20', ' ', $string);
    }

    public static function tagfillin($string) {
        return str_replace(' ', '_', $string);
    }

    public static function tagfillout($string) {
        return str_replace('_', ' ', $string);
    }

    static function makeUrl($args = array()){
        global $wp_rewrite;
        $pageid = JSVEHICLEMANAGERrequest::getVar('jsvehiclemanagerpageid');
        if(is_numeric($pageid)){
            $permalink = get_the_permalink($pageid);
        }else{
            if(isset($args['jsvehiclemanagerpageid']) && is_numeric($args['jsvehiclemanagerpageid'])){
                $permalink = get_the_permalink($args['jsvehiclemanagerpageid']);
            }else{
                $permalink = get_the_permalink();
            }
        }

        if (!$wp_rewrite->using_permalinks()){
            if(!strstr($permalink, 'page_id') && !strstr($permalink, '?p=')){
                $page['page_id'] = get_option('page_on_front');
                $args = $page + $args;
            }
            $redirect_url = add_query_arg($args,$permalink);
            return $redirect_url;
        }
        if(isset($args['jsvmme']) && isset($args['jsvmlt'])){
            // Get the original query parts
            $redirect = @parse_url($permalink);
            if (!isset($redirect['query']))
                $redirect['query'] = '';

            if(strstr($permalink, '?')){ // if variable exist
                $redirect_array = explode('?', $permalink);
                $_redirect = $redirect_array[0];
            }else{
                $_redirect = $permalink;
            }

            if($_redirect[strlen($_redirect) - 1] == '/'){
                $_redirect = substr($_redirect, 0, strlen($_redirect) - 1);
            }
            // If is layout
            $changename = false;
            if(file_exists(WP_PLUGIN_DIR.'/js-jobs/js-jobs.php')){
                $changename = true;
            }
            if(file_exists(WP_PLUGIN_DIR.'/js-support-ticket/js-support-ticket.php')){
                $changename = true;
            }
            if (isset($args['jsvmlt'])) {
                $layout = '';
                switch ($args['jsvmlt']) {
                    case 'vehiclesbycity':
                        $layout = 'vehicle-by-cities';
                    break;
                    case 'vehiclesbycondition':
                        $layout = 'vehicle-by-conditions';
                    break;
                    case 'sellersbycity':
                        $layout = 'seller-by-cities';
                    break;
                    case 'ratelist':
                        $layout = 'pricing';
                    break;
                    case 'creditslog':
                        $layout = ($changename === true) ? 'vehicle-credit-logs' : 'credit-logs';
                    break;
                    case 'stats':
                        $layout = ($changename === true) ? 'vehicle-stats' : 'stats';
                    break;
                    case 'creditspack':
                        $layout = ($changename === true) ? 'vehicle-packages' : 'packages';
                    break;
                    case 'vehiclesbymake':
                        $layout = 'vehicle-by-makes';
                    break;
                    case 'dashboard':
                        $layout = ($changename === true) ? 'vehicle-my-profile' : 'my-profile';
                    break;
                    case 'profile':
                        $layout = ($changename === true) ? 'vehicle-edit-profile' : 'edit-profile';
                    break;
                    case 'sellerlist':
                        $layout = 'sellers';
                    break;
                    case 'viewsellerinfo':
                        $layout = 'seller';
                    break;
                    case 'purchasehistory':
                        $layout = ($changename === true) ? 'vehicle-purchase-history' : 'purchase-history';
                    break;
                    case 'userregister':
                        $layout = ($changename === true) ? 'vehicle-registration' : 'registration';
                    break;
                    case 'login':
                        $layout = ($changename === true) ? 'vehicle-login' : 'login';
                    break;
                    case 'comparevehicles':
                        $layout = 'compare';
                    break;
                    case 'formvehicle':
                        $layout = 'add-vehicle';
                    break;
                    case 'myvehicles':
                        $layout = 'my-vehicles';
                    break;
                    case 'printvehicle':
                        $layout = 'print-vehicle';
                    break;
                    case 'vehiclepdf':
                        $layout = 'vehicle-pdf';
                    break;
                    case 'shortlistvehicles':
                        $layout = 'shortlisted-vehicles';
                    break;
                    case 'vehicledetail':
                        $layout = 'vehicle-detail';
                    break;
                    case 'vehiclesearch':
                        $layout = 'vehicle-search';
                    break;
                    case 'vehiclealerts':
                        $layout = 'vehicle-alerts';
                    break;
                    case 'vehiclesbytype':
                        $layout = 'vehicle-by-types';
                    break;
                    case 'pdf':
                        $layout = 'vehicle-pdf';
                    break;
                    case 'passwordlostform':
                        $layout = 'car-manager-lost-password';
                    break;
                    case 'resetnewpasswordform':
                        $layout = 'car-manager-reset-new-password';
                    break;
                    default:
                        $layout = $args['jsvmlt'];
                    break;
                }
                if(is_home() || is_front_page()){
                    if($_redirect == site_url()){
                        $layout = 'vm-'.$layout;
                    }
                }
                $_redirect .= '/' . $layout;
            }
            // If is list
            if (isset($args['list'])) {
                $_redirect .= '/' . $args['list'];
            }
            // If is sortby
            if (isset($args['sortby'])) {
                $_redirect .= '/' . $args['sortby'];
            }
            // If is jsvehiclemanagerid
            if (isset($args['jsvehiclemanagerid'])) {
                if($args['jsvmlt'] == 'vehicledetail'){
                    $vehicle_seo = JSVEHICLEMANAGERincluder::getJSModel('configuration')->getConfigValue('vehicle_seo');
                    if(!empty($vehicle_seo)){
                        $vehicle_seo = JSVEHICLEMANAGERincluder::getJSModel('vehicle')->makeVehicleSeo($vehicle_seo , $args['jsvehiclemanagerid']);
                        if($vehicle_seo != ''){
                            $id = JSVEHICLEMANAGERincluder::getJSModel('common')->parseID($args['jsvehiclemanagerid']);
                            $_redirect .= '/' . $vehicle_seo . '-' . $id;
                        }
                    }
                }else{
                    $_redirect .= '/' . $args['jsvehiclemanagerid'];
                }
            }
            // If is conditionid
            if (isset($args['conditionid'])) {
                $alias = JSVEHICLEMANAGERincluder::getJSModel('conditions')->getConditionTitlebyId($args['conditionid']);
                $alias = JSVEHICLEMANAGERincluder::getJSModel('common')->removeSpecialCharacter($alias);
                $_redirect .= '/'.$alias.'_10' . $args['conditionid'];
            }
            // If is vehicletypeid
            if (isset($args['vehicletypeid'])) {
                $alias = JSVEHICLEMANAGERincluder::getJSModel('vehicletype')->getVehicleTypeTitlebyId($args['vehicletypeid']);
                $alias = JSVEHICLEMANAGERincluder::getJSModel('common')->removeSpecialCharacter($alias);
                $_redirect .= '/'.$alias.'_11' . $args['vehicletypeid'];
            }
            // If is modelyearid
            if (isset($args['modelyearid'])) {
                $alias = JSVEHICLEMANAGERincluder::getJSModel('modelyear')->getModelyearTitlebyId($args['modelyearid']);
                $alias = JSVEHICLEMANAGERincluder::getJSModel('common')->removeSpecialCharacter($alias);
                $_redirect .= '/'.$alias.'_12' . $args['modelyearid'];
            }
            // If is cityid
            if (isset($args['cityid'])) {
                $alias = JSVEHICLEMANAGERincluder::getJSModel('city')->getCityNamebyId($args['cityid']);
                $alias = JSVEHICLEMANAGERincluder::getJSModel('common')->removeSpecialCharacter($alias);
                $_redirect .= '/'.$alias.'_13' . $args['cityid'];
            }
            // If is makeid
            if (isset($args['makeid'])) {
                $alias = JSVEHICLEMANAGERincluder::getJSModel('make')->getMakeTitlebyId($args['makeid']);
                $alias = JSVEHICLEMANAGERincluder::getJSModel('common')->removeSpecialCharacter($alias);
                $_redirect .= '/'.$alias.'_14' . $args['makeid'];
            }
            // If is modelid
            if (isset($args['modelid'])) {
                $alias = JSVEHICLEMANAGERincluder::getJSModel('model')->getModelTitlebyId($args['modelid']);
                $alias = JSVEHICLEMANAGERincluder::getJSModel('common')->removeSpecialCharacter($alias);
                $_redirect .= '/'.$alias.'_15' . $args['modelid'];
            }
            // If is sellerid
            if (isset($args['sellerid'])) {
                $alias = JSVEHICLEMANAGERincluder::getJSModel('user')->getUserNamebyId($args['sellerid']);
                $alias = JSVEHICLEMANAGERincluder::getJSModel('common')->removeSpecialCharacter($alias);
                $_redirect .= '/'.$alias.'_16' . $args['sellerid'];
            }

            // If is jsvehiclemanagerredirecturl
            if (isset($args['jsvehiclemanagerredirecturl'])) {
                $_redirect .= '?jsvehiclemanagerredirecturl=' . $args['jsvehiclemanagerredirecturl'];
            }
            return $_redirect;
        }else{ // incase of form
            $redirect_url = add_query_arg($args,$permalink);
            return $redirect_url;
        }
    }

    static function bjencode($array){
        return base64_encode(json_encode($array));
    }

    static function bjdecode($array){
        return base64_decode(json_encode($array));
    }

    static function generateHash($id){
        if(!is_numeric($id)){
            return '';
        }
        return base64_encode(json_encode(base64_encode($id)));
    }

    function reset_jsvm_aadon_query(){
        jsvehiclemanager::$_addon_query = array('select'=>'','join'=>'','where'=>'');
    }

    static function checkAddonActiveOrNot($for){
        if(in_array($for, jsvehiclemanager::$_active_addons)){
            return true;
        }
        return false;
    }

}

$jsvehiclemanager = new jsvehiclemanager();

add_action( 'login_form_middle', 'addLostPasswordLink' , 20 );
function addLostPasswordLink() {
    $losturl = "";
    if(jsvehiclemanager::$_car_manager_theme == 1){
        $losturl = '<a href="'.esc_url(jsvehiclemanager::makeUrl(array('jsvmme'=>'jsvehiclemanager', 'jsvmlt'=>'passwordlostform', 'jsvehiclemanagerpageid'=>jsvehiclemanager::getPageid()))).'">'. __('Lost your password','js-vehicle-manager') .'?</a>';
    }else{
        $losturl = '<a href="'.site_url().'/wp-login.php?action=lostpassword">'. __('Lost your password','js-vehicle-manager') .'?</a>';
    }

    return $losturl;
}

add_action('init', 'jsvehiclemanager_custom_init_session', 1);

function jsvehiclemanager_custom_init_session() {
    if (!session_id())
        session_start();
    if(isset($_SESSION['jsvehiclemanager_apply_visitor'])){
        $layout = JSVEHICLEMANAGERrequest::getVar('jsvmlt');
        if($layout != null && $layout != 'addresume'){ // reset the session id
            unset($_SESSION['jsvehiclemanager_apply_visitor']);
        }
    }
    if(isset($_SESSION['wp-jsvehiclemanager']) && isset($_SESSION['wp-jsvehiclemanager']['resumeid'])){
        $layout = JSVEHICLEMANAGERrequest::getVar('jsvmlt');
        if($layout != null && $layout != 'addresume'){ // reset the session id
            unset($_SESSION['wp-jsvehiclemanager']);
        }
    }
}

// define the upgrader_pre_download callback
function filter_upgrader_pre_download_jsvehiclemanager( $bool, $package, $instance ) {
    if(strstr($package,'js-vehicle-manager'))
        return true;
    else
        return false;
};
// add the filter
//add_filter( 'upgrader_pre_download', 'filter_upgrader_pre_download_jsvehiclemanager', 10, 3 );

$file   = basename( __FILE__ );
$folder = basename( dirname( __FILE__ ) );
$hook = "in_plugin_update_message-{$folder}/{$file}";

//add_action( $hook, 'update_message_jsvehiclemanager_plugin', 10, 2 );
function update_message_jsvehiclemanager_plugin( $plugin_data, $r ){
    echo '<style >
            tr#js-vehicle-manager-update div.update-message.notice.inline.notice-warning.notice-alt{border:0px;background:none;}
            tr#js-vehicle-manager-update div.update-message.notice.inline.notice-warning.notice-alt p{display:none;}
            </style>';
    echo '<div style="width:calc(100% + 30px);margin-left:-30px;background-color: #d54e21; padding: 10px; color: #f9f9f9; margin-top: 10px">'.__('You can update your plugin from Admin','js-vehicle-manager').' -> '.__('JS Jobs','js-vehicle-manager').' -> <a style="color:#ffffff;text-decoration:underline;font-weight:bold;" href="'.admin_url('admin.php?page=jsvehiclemanager&jsvmlt=stepone').'">'.__('Update','js-vehicle-manager').'</a></div>';
}

function jsvehiclemanager_register_plugin_styles(){
    wp_enqueue_script('jquery');
    include_once 'includes/css/site_color.php';
    wp_enqueue_style('jsauto-site', jsvehiclemanager::$_pluginpath . 'includes/css/site.css');
    wp_enqueue_style('jsauto-site-tablet', jsvehiclemanager::$_pluginpath . 'includes/css/site_tablet.css',array(),'','(min-width: 481px) and (max-width: 780px)');
    wp_enqueue_style('jsauto-site-mobile-landscape', jsvehiclemanager::$_pluginpath . 'includes/css/site_mobile_landscape.css',array(),'','(min-width: 481px) and (max-width: 650px)');
    wp_enqueue_style('jsauto-site-mobile', jsvehiclemanager::$_pluginpath . 'includes/css/site_mobile.css',array(),'','(max-width: 480px)');
    // wp_enqueue_style('jsauto-chosen-site', jsvehiclemanager::$_pluginpath . 'includes/js/chosen/chosen.min.css');
    if (is_rtl()) {
        wp_register_style('jsauto-site-rtl', jsvehiclemanager::$_pluginpath . 'includes/css/sitertl.css');
        wp_enqueue_style('jsauto-site-rtl');
    }
}

add_action( 'wp_enqueue_scripts', 'jsvehiclemanager_register_plugin_styles' );

function jsvehiclemanager_admin_register_plugin_styles() {
    wp_enqueue_style('jsauto-admin-desktop-css', jsvehiclemanager::$_pluginpath . 'includes/css/admin_desktop.css',array(),'','all');
    wp_enqueue_style('jsauto-admin-mobile-css', jsvehiclemanager::$_pluginpath . 'includes/css/admin_mobile.css',array(),'','(max-width: 480px)');
    wp_enqueue_style('jsauto-admin-mobile-landscape-css', jsvehiclemanager::$_pluginpath . 'includes/css/admin_mobile_landscape.css',array(),'','(min-width: 481px) and (max-width: 660px)');
    wp_enqueue_style('jsauto-admin-tablet-css', jsvehiclemanager::$_pluginpath . 'includes/css/admin_tablet.css',array(),'','(min-width: 481px) and (max-width: 782px)');
    if (is_rtl()) {
        wp_register_style('jsauto-admincss-rtl', jsvehiclemanager::$_pluginpath . 'includes/css/adminrtl.css');
        wp_enqueue_style('jsauto-admincss-rtl');
    }
}
add_action( 'admin_enqueue_scripts', 'jsvehiclemanager_admin_register_plugin_styles' );

// Social Post
add_action("wp_head","jsvm_set_socialmedia_metatags");
function jsvm_set_socialmedia_metatags(){
    $module = JSVEHICLEMANAGERrequest::getVar('jsvmme');
    $layout = JSVEHICLEMANAGERrequest::getVar('jsvmlt');
    if($module == 'vehicle' && $layout == 'vehicledetail'){
        $vehicleid = JSVEHICLEMANAGERrequest::getVar('jsvehiclemanagerid');
        $vehicleid = JSVEHICLEMANAGERincluder::getJSModel('common')->parseID($vehicleid);
        if(!is_numeric($vehicleid)) return;
        $db = new jsvehiclemanagerdb();
        $query = "SELECT vehicles.*,vehicletypes.title AS vehicletitle,
            makes.title maketitle,models.title AS modeltitle,modelyear.title AS modelyeartitle,
            transmission.title AS transmissiontitle,currency.symbol AS currencysymbol,
            user.name AS sellerinfoname,user.weblink AS sellerinfoweblink,
            user.cell AS sellerinfocell,user.phone AS sellerinfophone,user.email AS sellerinfoemail,user.description AS sellerdescription,user.latitude AS sellerlatitude,user.longitude AS sellerlongitude,user.videotypeid AS sellervideotypeid,user.video AS sellervideo,
            user.photo AS sellerphoto,user.cityid AS sellercityid,vehicles.isdiscount, vehicles.discountstart, vehicles.discountend, vehicles.discounttype, vehicles.discount
            FROM `#__js_vehiclemanager_vehicles` AS vehicles
            LEFT JOIN `#__js_vehiclemanager_vehicletypes` AS vehicletypes ON vehicles.vehicletypeid = vehicletypes.id
            LEFT JOIN `#__js_vehiclemanager_makes` AS makes ON vehicles.makeid =makes.id
            LEFT JOIN `#__js_vehiclemanager_models` AS models ON vehicles.modelid =  models.id
            LEFT JOIN `#__js_vehiclemanager_modelyears` AS modelyear ON vehicles.modelyearid = modelyear.id
            LEFT JOIN `#__js_vehiclemanager_transmissions` AS transmission ON transmission.id = vehicles.transmissionid
            LEFT JOIN `#__js_vehiclemanager_currencies` AS currency ON currency.id = vehicles.currencyid
            LEFT JOIN `#__js_vehiclemanager_users` AS user ON user.id = vehicles.uid
            WHERE  vehicles.id = " . $vehicleid;
        $db->setQuery($query);
        $vehicle = $db->loadObject();
        if($vehicle != ''){
            $title = JSVEHICLEMANAGERincluder::getJSModel('common')->returnVehicleTitle($vehicle->maketitle,$vehicle->modeltitle,$vehicle->modelyeartitle);
            $image = JSVEHICLEMANAGERincluder::getJSModel('vehicle')->getVehicleImagesByVehicleId($vehicleid);
            $image_path = '';
            foreach ($image as $img) {
                if($img->isdefault == 1){
                    $image_path = $img->file.$img->filename;
                }
            }

            // if(isset($image[0]) && !empty($image)){
            //     $image = $image[0]->file.$image[0]->filename;
            // }
            $description = __("Make","js-vehicle-manager") . ": " . __($vehicle->maketitle,"js-vehicle-manager") . " - " . __("Model","js-vehicle-manager") . ": " . __($vehicle->modeltitle,"js-vehicle-manager"). "-" . __("Transmission","js-vehicle-manager") . ": " . __($vehicle->transmissiontitle,"js-vehicle-manager");
            $vehicleurl = jsvehiclemanager::makeUrl(array('jsvmme'=>'vehicle', 'jsvmlt'=>'vehicledetail', 'jsvehiclemanagerid'=>$vehicleid));
            echo '
            <meta name= "card" content="summary" />
            <meta property="og:type" content="product" />
            <meta name="title" property="og:title" content="'.esc_html($title).'" />
            <meta name="description" property="og:description" content="'.esc_html($description).'" />';
            if($image_path == ''){
                echo ' <meta name="image" property="og:image" content="'.$image_path.'" />';
            }
            echo '
            <meta property="og:url" content="'.$vehicleurl.'">';
        }

    }else{
        //copied from header.php
        echo '<meta name="description" content="';
        bloginfo('description');
        echo '" />';
    }
}

add_action( 'jsvm_addon_update_date_failed', 'jsvmaddonUpdateDateFailed' );

function jsvmaddonUpdateDateFailed(){
    die();
}

add_filter( 'lostpassword_url', 'wdm_lostpassword_url', 20 );
function wdm_lostpassword_url() {
    if(jsvehiclemanager::$_car_manager_theme == 1){
        return esc_url(jsvehiclemanager::makeUrl(array('jsvmme'=>'jsvehiclemanager', 'jsvmlt'=>'passwordlostform', 'jsvehiclemanagerpageid'=>jsvehiclemanager::getPageid())));
    }else{
        return esc_url(site_url().'/wp-login.php?action=lostpassword');
        exit;
    }
}

if(!empty(jsvehiclemanager::$_active_addons)){
    require_once 'includes/addon-updater/jsvmupdater.php';
    $JSVM_Updater  = new JSVM_Updater();
}

?>
