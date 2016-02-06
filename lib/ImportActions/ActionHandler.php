<?php
include_once ('ImportAction.php');
/**
 * Class HWCLI_ImportActions_actionHandler
 */
class HWCLI_ImportActions_ActionHandler {
    private $_default = null;
    private $plugins_handler = array('wpseo', 'w3cache');

    public function __construct($default_handler = null) {
        $this->_default = $default_handler;
    }

    /**
     * check exists for action
     * @param $action
     */
    function exists($action) {

    }

    /**
     * @param $action
     * @param $callback
     */
    function register_action($action, $callback) {
        $handle = $this->_handler($action);
        $ajax_handle = str_replace('action_', '', $action);
        hwcli_add_ajax_handle($this->get_action_handle( $handle), $callback);
    }
    /**
     * @param $action
     * @return string
     */
    public function get_action_handle($action) {
        return 'import_'. $action;
    }
    /**
     * get handler from action name
     * @param $action
     * @return mixed
     */
    function _handler($action) {
        if(is_string($action) && strpos($action, ',')!==false) {
            $action = explode(',', $action);
            $action = reset($action); //get first action
        }
        if(is_array($action)) $action = reset($action);
        foreach ($this->plugins_handler as $handler) {
            if(strpos($action, 'action_'. $handler)!==false) return $handler;
        }
    }


    /**
     * @param $action
     */
    public function execute($action, $ajax_action = '') {
        if(!$ajax_action) $ajax_action = '_hwcli_import_'. $this->_handler($action);
        //register ajax action
        if($ajax_action && ( function_exists($ajax_action) || is_callable($ajax_action))) {
            $this->register_action($action, $ajax_action) ;
        }

        $handler= $this->_handler($action);
        return $this->_execute($handler, $action);
    }

    /**
     * @param $handler
     * @return mixed
     */
    function get_handler_object($handler) {
        $handler = ucfirst($handler);
        $handler_w = explode('_', $handler);
        $handler = '';
        foreach($handler_w as $w)
            $handler .= ucfirst($w);
        $handler_class = "HWCLI_ImportActions_{$handler}";
        $handler_object = hwcli_instance($handler_class);
        $handler_object->handler= strtolower($handler); //save handler name
        return $handler_object;
    }
    /**
     * @param $handler
     * @param $action
     * @throws Exception
     */
    private function _execute($handler, $action) {
        if(is_array($action)) {
            foreach($action as $_action) {
                $object = $this->_execute($handler,$_action) ;
            }
            return isset($object)? $object : null;
        }

        if (is_string($action) && strpos($action, 'action_') === false)
            throw new Exception(sprintf(__('%s is not an correct action.'), $action));
        if ($handler == '') {
            if ($this->_default && method_exists($this->_default, $action)) {
                call_user_func(array($this->_default, $action));
                return;
            }
        } else {
            $handler = ucfirst($handler);
            $handler_w = explode('_', $handler);
            $handler = '';
            foreach($handler_w as $w)
                $handler .= ucfirst($w);
            $handler_class = "HWCLI_ImportActions_{$handler}";
            $handler_object = hwcli_instance($handler_class);
            if (method_exists($handler_object, $action)) {
                $handler_object->$action();
                return $handler_object;
            }
        }
        throw new Exception(sprintf(__('action %s does not exist for %s'), $action, $handler));
    }
}