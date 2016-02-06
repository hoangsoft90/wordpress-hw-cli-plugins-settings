<?php
/*
Plugin Name: Hoangweb WPCLI
Plugin URI:
Description:
Version:     0.1-alpha
Author:      Hoangweb
Author URI:  http://hoangweb.com
*/

include_once ('inc/define.php');
include ('functions.php');

include_once (HWCLI_CLI_DIR. '/wp-seo.php');
include_once (HWCLI_CLI_DIR. '/w3-cache.php');

global $hwcli;
/**
 * Class HWCLI_Main
 */
class HWCLI_Main {
    /**
     * @var
     */
    public $handler = null;

    function __construct() {
        $this->handler = hwcli_instance('HWCLI_ImportActions_ActionHandler');
        $this->load_plugin_setting_from_cli();
        //register activation hook
        register_activation_hook( __FILE__, array($this, '_plugin_activate') );
    }

    /**
     * plugin activation hook
     */
    function _plugin_activate() {
        //copy ajax handler to root of web folder
        if(!file_exists(ABSPATH. '/hw_ajax.php')) copy(HWCLI_PLUGIN_PATH .'/ajax.php' , ABSPATH .'/hw_ajax.php');
    }

    /**
     * load action
     */
    function load_plugin_setting_from_cli() {

        if(defined('DOING_AJAX') && defined('HW_DOING_AJAX')) {
            #$plugin = $_REQUEST['for_plugin'];
            #$action = $plugin.'_settings';

            $call_action = HWCLI_ImportActions::get_call_action();
            $obj = $this->handler->execute($call_action);
        }
        //$wpseo = $this->handler->get_handler_object('wpseo');

    }
}
$hwcli = new HWCLI_Main();