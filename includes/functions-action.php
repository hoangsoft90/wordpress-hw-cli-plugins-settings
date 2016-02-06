<?php
/**
 * Created by PhpStorm.
 * User: Hoang
 * Date: 16/12/2015
 * Time: 10:08
 */
/**
 * @hook hw_import_wpseo_settings
 */
function _hwcli_import_wpseo() {
    /*if ( !wp_verify_nonce( $_REQUEST['nonce'], "import_wpseo_settings")) {
        exit("No naughty business please");
    }*/
    #$_FILES['settings_import_file']= $_FILES['file'];
    //$_FILES['settings_import_file']['type']= 'application/octet-stream';

    /*$_FILES['settings_import_file'] = array(
        'file' => HWCLI_DATA_PATH .'/settings.zip',
        'url' => HWCLI_DATA_URL. '/settings.zip',
        'type' => 'application/zip'
    );*/
    $import = new WPSEO_Import();
    if (isset($import) && $import->msg != '' ) echo $import->msg;
    die();
}

/**
 * all process do in import action method
 */
function _hwcli_import_w3cache() {

    die();
}