<?php
ini_set('display_errors', 'Off');

//mimic the actuall admin-ajax
define('DOING_AJAX', true);
define('HW_DOING_AJAX', true);

//get ajax action
if(isset($_GET['action']) ) $_action = $_GET['action'];
elseif(isset($_REQUEST['action'])) $_action = $_REQUEST['action'];

if (empty( $_action) || !isset($_POST['base_path']))
    die('-1');

//make sure you update this line
//to the relative location of the wp-load.php
require_once($_POST['base_path']. '/wp-load.php');
/*
require_once($_POST['base_path'] ."/wp-config.php");
$wp->init();
$wp->parse_request();
$wp->query_posts();
$wp->register_globals();
$wp->send_headers();
*/
//wp upload tool
include_once ABSPATH . 'wp-admin/includes/media.php';
if ( ! function_exists( 'wp_handle_upload' ) ) {
    include_once ABSPATH . 'wp-admin/includes/file.php';
}
include_once ABSPATH . 'wp-admin/includes/image.php';

//Typical headers
header('Content-Type: text/html');
send_nosniff_header();

//Disable caching
header('Cache-Control: no-cache');
header('Pragma: no-cache');

/*Next we need to call the actual methods we want to invok*/
$action = esc_attr(trim($_action));

//A bit of security
$allowed_actions = array(
    'custom_action1',
    'custom_action2'
);

//For logged in users
add_action('SOMETEXT_custom_action1', 'handler_fun1');
add_action('SOMETEXT_custom_action2', 'handler_fun1');

//For guests
add_action('SOMETEXT_nopriv_custom_action2', 'handler_fun2');
add_action('SOMETEXT_nopriv_custom_action1', 'handler_fun1');

if(1||in_array($action, $allowed_actions)) {
    if(is_user_logged_in()) {
        do_action('HW_AJAX_'.$action);
    }
    else {
        do_action('HW_AJAX_nopriv_'.$action);
    }
} else {
    die('-1');
}
