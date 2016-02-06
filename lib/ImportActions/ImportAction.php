<?php
/**
 * Created by PhpStorm.
 * User: Hoang
 * Date: 16/12/2015
 * Time: 13:01
 */
abstract class HWCLI_ImportActions {
    /**
     * @var string
     */
    public $handler = '';
    /**
     * @var
     */
    private $actionHandler = null;

    function __construct() {
        $this->actionHandler = hwcli_instance('HWCLI_ImportActions_ActionHandler');
    }
    function register_import_action() {
        #$this->actionHandler->register_action('wpseo', '_hwcli_import_wpseo');
    }
    /**
     * @param $file
     * @return string
     */
    function get_import_file_path($file) {
        if(!file_exists($file)) {
            $file = realpath(HWCLI_DATA_PATH. '/'. $file);
        }
        return $file;
    }
    /**
     * get call action from url uri
     */
    static function get_call_action() {
        $plugin = $_REQUEST['for_plugin'];
        $actions = array();
        if(isset($_REQUEST['hw_import_action']) /*&& strpos($_REQUEST['hw_import_action'], ',')!==false*/) {
            foreach (preg_split('#[\s,]#', $_REQUEST['hw_import_action']) as $action) {
                $actions[] = 'action_'. $plugin. '_'. $action;
            }
        }
        return !empty($actions)? $actions : null;//($_REQUEST['hw_import_action'])? 'action_'. $plugin. '_'.$_REQUEST['hw_import_action'].'' : '';
    }
    /**
     * @param $link
     * @param $file
     * @param array $data
     *
     */
    function import_file( $file, $data = array(), $link='') {
        $handle = $this->actionHandler->get_action_handle($this->handler);
        if(!$link) $link = hwcli_get_ajax_url($handle);

        if(is_array($file)) list($file_field,$file) = each($file);
        if(!isset($file_field)) $file_field = 'file';
        //prepare data
        if(!is_array($data)) $data = array();
        $data['base_path'] = ABSPATH;

        try {
            // initialise the curl request
            $request = curl_init($link);
            if (FALSE === $request)
                throw new Exception('failed to initialize');

            curl_setopt($request, CURLOPT_URL, $link);
            // send a file
            curl_setopt($request, CURLOPT_POST, true);
            //post data
            $post = array(
                $file_field => '@' . $this->get_import_file_path($file).'',
                'base_path' => ABSPATH,
                'for_plugin' => $this->handler
            );
            if(is_array($data) && !empty($data)) $post = array_merge($post, $data);
            curl_setopt(
                $request,
                CURLOPT_POSTFIELDS,
                $post
            );

            //curl_setopt($request, CURLOPT_HTTPHEADER, array("Accept:application/x-zip,application/x-zip-compressed,application/zip"));
            // output the response
            curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($request, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($request, CURLOPT_SSL_VERIFYHOST, false);

            $result = curl_exec($request);

            // close the session
            curl_close($request);
            return $result;
        }
        catch(Exception $e) {

            trigger_error(sprintf(
                    'Curl failed with error #%d: %s',
                    $e->getCode(), $e->getMessage()),
                E_USER_ERROR);

        }
    }
}