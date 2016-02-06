<?php
if(!function_exists('_print')):
/**
 * @param $t
 */
function hwcli_print($t) {
    echo '<textarea>';
    print_r($t);
    echo '</textarea>';
}
endif;
/**
 * @param $name
 * @param $callback
 * @param $allow_frontend
 */
function hwcli_add_ajax_handle($name, $callback, $allow_frontend=true) {
    if(function_exists($callback) || is_callable($callback)) {
        add_action("HW_AJAX_{$name}", $callback);
        if($allow_frontend) add_action("HW_AJAX_nopriv_{$name}", $callback);
    }
}

/**
 * @param string $action
 * @param string $nonce
 * @return string
 */
function hwcli_get_ajax_url($action='', $nonce='') {
    if(!$nonce) $nonce = wp_create_nonce($action);
    $ajax_handle = HWCLI_PLUGIN_URL.('/ajax.php');
    if(file_exists(ABSPATH. '/hw_ajax.php')) {
        $ajax_handle = home_url(). '/hw_ajax.php';
    }
    return $ajax_handle.('?nonce='. $nonce. '&action='. $action );
}
function hwcli_log() {

}
include_once ('includes/functions-action.php');