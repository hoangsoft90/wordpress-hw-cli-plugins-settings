<?php
/**
 * Created by PhpStorm.
 * User: Hoang
 * Date: 16/12/2015
 * Time: 11:09
 */
class HWCLI_ImportActions_W3cache extends HWCLI_ImportActions {
    /**
     * W3_Config
     * @var
     */
    public $_config;

    function __construct() {
        parent::__construct();
        $this->_config = w3_instance('W3_Config');
    }
    function action_w3cache_settings() {
        #if(! $this->compare('wpseo')) return;
        echo 'call action_w3cache_settings';
    }
    /*--------------------object cache--------------------*/
    /**
     * Flush object cache action
     *
     * @return void
     */
    function action_w3cache_flush_objectcache() {
        $this->flush_objectcache();

        $this->_config->set('notes.need_empty_objectcache', false);

        $this->_config->save();

        /*w3_admin_redirect(array(
            'w3tc_note' => 'flush_objectcache'
        ), true);*/
    }
    /**
     * Flush object cache
     *
     * @return void
     */
    function flush_objectcache() {
        $flusher = w3_instance('W3_CacheFlush');
        $flusher->objectcache_flush();
    }

    /*----------------db cache-------------------*/
    /**
     * Flush database cache action
     *
     * @return void
     */
    function action_w3cache_flush_dbcache() {
        $this->flush_dbcache();

        /*w3_admin_redirect(array(
            'w3tc_note' => 'flush_dbcache'
        ), true);*/
    }
    /**
     * Flush database cache
     *
     * @return void
     */
    function flush_dbcache() {
        $flusher = w3_instance('W3_CacheFlush');
        $flusher->dbcache_flush();
    }

    /*----------------page cache-------------------*/
    /**
     * Flush page cache
     *
     * @return void
     */
    function flush_pgcache() {
        $flusher = w3_instance('W3_CacheFlush');
        $flusher->pgcache_flush();
    }
    /**
     * Flush page cache action
     *
     * @return void
     */
    function action_w3cache_flush_pgcache() {
        $_config = w3_instance('W3_Config');
        $this->flush_pgcache();

        $_config->set('notes.need_empty_pgcache', false);
        $_config->set('notes.plugins_updated', false);

        $_config->save();

        /*w3_admin_redirect(array(
            'w3tc_note' => 'flush_pgcache'
        ), true);*/
    }
    /*----------------minify cache------------------*/
    /**
     * Flush minify cache
     *
     * @return void
     */
    private function flush_minify() {
        $w3_minify = w3_instance('W3_Minify');
        $w3_minify->flush();
    }
    /**
     * Flush minify action
     *
     * @return void
     */
    function action_w3cache_flush_minify() {
        $this->flush_minify();

        $this->_config->set('notes.need_empty_minify', false);

        $this->_config->save();

        /*w3_admin_redirect(array(
            'w3tc_note' => 'flush_minify'
        ), true);*/
    }
    /*----------------browser cache------------------*/
    /**
     * Flush browsers cache
     */
    function flush_browser_cache() {
        if ($this->_config->get_boolean('browsercache.enabled')) {
            $this->_config->set('browsercache.timestamp', time());

            $this->_config->save();
        }
    }
    /**
     * Flush browser cache action
     *
     * @return void
     */
    function action_w3cache_flush_browser_cache() {
        $this->flush_browser_cache();

        w3_admin_redirect(array(
            'w3tc_note' => 'flush_browser_cache'
        ), true);
    }
    /**
     * execute('wpseo_disable')
     */
    function action_w3cache_disable() {

    }
    /**
     * Import config action
     * copy from :plugins/w3-total-cache/lib/W3/AdminActions/ConfigActionsAdmin.php
     * @return void
     */
    function action_w3cache_import() {
        if(!class_exists('W3_Config')) {
            echo 'Sory W3 total cache inactive.';
            return;
        }
        $error = '';
        if(!function_exists('w3_config_save')) {
            include_once (WP_PLUGIN_DIR. '/w3-total-cache/inc/functions/admin.php');
        }

        $config = new W3_Config();

        if (!isset($_FILES['config_file']['error']) || $_FILES['config_file']['error'] == UPLOAD_ERR_NO_FILE) {
            $error = 'config_import_no_file';
        } elseif ($_FILES['config_file']['error'] != UPLOAD_ERR_OK) {
            $error = 'config_import_upload';
        } else {
            ob_start();
            $imported = $config->import($_FILES['config_file']['tmp_name']);
            ob_end_clean();

            if (!$imported) {
                $error = 'config_import_import';
            }
        }

        if ($error) {
            /*w3_admin_redirect(array(
                'w3tc_error' => $error
            ), true);*/
            echo $error;
        }

        w3_config_save(w3_instance('W3_Config'), $config, w3_instance('W3_ConfigAdmin'));
        /*w3_admin_redirect(array(
            'w3tc_note' => 'config_import'
        ), true);*/
    }
}