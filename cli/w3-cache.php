<?php
/**
 * Utility commands for my theme/plugin
 * wp hw-yoast
 */
if(class_exists('WP_CLI_Command')):
class HW_CLI_W3_Total_Cache extends WP_CLI_Command {
    /**
     * @param $args
     * @param $assoc_args
     */
    public function import_settings( $args, $assoc_args ) {
        global $hwcli;
        $result = $hwcli->handler->get_handler_object('w3cache')->import_file( array(
                'config_file' => 'w3-total-cache.php'),
            array(
                'hw_import_action' => 'import,flush_pgcache,flush_objectcache,flush_dbcache,flush_minify,flush_browser_cache'
            )
        );

        WP_CLI::success("Import W3 Total Cache successful.\n". $result);
    }
}
WP_CLI::add_command( 'hw-w3cache', 'HW_CLI_W3_Total_Cache' );
endif;