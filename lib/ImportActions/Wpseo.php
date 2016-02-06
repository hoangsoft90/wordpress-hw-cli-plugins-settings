<?php
/**
 * Created by PhpStorm.
 * User: Hoang
 * Date: 16/12/2015
 * Time: 11:09
 */
/**
 * Class HWCLI_ImportActions_Wpseo
 * note: ucfirst for action name
 */
class HWCLI_ImportActions_Wpseo extends HWCLI_ImportActions {
    function __construct() {
        parent::__construct();

    }
    function action_wpseo_settings() {
        #if(! $this->compare('wpseo')) return;
        echo 'call action_wpseo_settings';
    }

    /**
     * execute('wpseo_disable')
     */
    function action_wpseo_disable() {

    }
}