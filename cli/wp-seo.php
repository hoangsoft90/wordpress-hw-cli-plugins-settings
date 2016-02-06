<?php
/**
 * Utility commands for my theme/plugin
 */
if(class_exists('WP_CLI_Command')):
class HW_CLI_WPSEO extends WP_CLI_Command {
    /**
     * @param $args
     * @param $assoc_args
     */
    public function settings( $args, $assoc_args ) {
        $options = array(
            'website_name' => get_bloginfo('name'),
            'company_or_person' => 'company',
            'company_name' => '',
            'company_logo' => ''
        );
        $wpseo = array(
            'ignore_blog_public_warning'=>'',
            'ignore_meta_description_warning' => '',
            'ignore_page_comments'=> '',
            'ignore_permalink' => '',
            'ignore_tour' => '1'
        );
        $wpseo_titles = array(
            'separator' => '',
            ''
        );
        update_option('', array_merge($wpseo,$wpseo_titles));
    }

    /**
     * @param $args
     * @param $assoc_args
     */
    public function import_settings( $args, $assoc_args ) {
        $nonce = wp_create_nonce ('import_wpseo_settings');
        //$link = hw_get_ajax_url('hw-import-wpseo', $nonce );
        global $hwcli;
        $result = $hwcli->handler->get_handler_object('wpseo')->import_file( array(
            'settings_import_file' => 'settings.zip'),
            array(
                'action'=> 'wp_handle_upload', 'nonce' => $nonce,
                'hw_import_action' =>'settings'
            )
        );

        WP_CLI::success("Import WP SEO successful.\n". $result);
    }
}
WP_CLI::add_command( 'hw-wpseo', 'HW_CLI_WPSEO' );
endif;

