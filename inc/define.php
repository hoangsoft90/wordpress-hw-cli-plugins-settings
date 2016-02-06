<?php
/**
 * plugin path
 */
define( 'HWCLI_PLUGIN_PATH', plugin_dir_path(dirname(__FILE__)));
define( 'HWCLI_PLUGIN_URL', plugins_url('', dirname(__FILE__) ));
define( 'HWCLI_LIB_DIR' ,HWCLI_PLUGIN_PATH .'lib');

/**
 * data settings files for plugins
 */
define( 'HWCLI_DATA_PATH', HWCLI_PLUGIN_PATH. 'data');
define( 'HWCLI_DATA_URL', HWCLI_PLUGIN_URL. '/data');

define('HWCLI_CLI_DIR', HWCLI_PLUGIN_PATH. 'cli');
/**
 *
 * @param $class
 * @return mixed
 */
function hwcli_instance($class) {
    static $instances = array();
    if (!isset($instances[$class]) ) {
        include_once( HWCLI_LIB_DIR . '/' .
            str_replace('_', '/', substr($class, 6)) . '.php');
        $instances[$class] = new $class();
    }

    $v = $instances[$class];   // Don't return reference
    return $v;
}