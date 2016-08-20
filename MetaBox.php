<?php

/**
 * Created by PhpStorm.
 * User: chidow
 * Date: 2016/05/30
 * Time: 9:37 PM
 */
class MetaBox
{
        private $current_element_id = null;
        private $current_screen_id = null;

        public function create_text_field( $id, $title, $screen = null, $context = 'advanced', $priority = 'default', $callback_args = null )
        {
            add_meta_box( $id, $title, 'text_field_template', $screen = null, $context = 'advanced', $priority = 'default', $callback_args = null );
        }


        public function text_field_template()
        {
            wp_nonce_field('%s_%s_save_field', '%s_%s_meta_box_nonce');

            echo '<h1>Hello</h1>';
            $functionName =  '%s_%s_save_field';
            $$functionName = function($post_id) {
                // Do stuff
            };

        }
}

