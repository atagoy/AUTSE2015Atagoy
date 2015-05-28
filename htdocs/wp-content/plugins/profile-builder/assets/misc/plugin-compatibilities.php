<?php
/*
 * This file has the sole purpose to help solve compatibility issues with other plugins
 *
 */


    /****************************************************
     * Plugin Name: Captcha
     * Plugin URI: https://wordpress.org/plugins/captcha/
     ****************************************************/

    /*
     * Function that ads the Captcha HTML to Profile Builder login form
     *
     */
    if( function_exists('cptch_display_captcha_custom') ) {
        function wppb_captcha_add_form_login($form_part, $args) {

            $cptch_options = get_option('cptch_options');

            if (1 == $cptch_options['cptch_login_form'])
                $form_part .= cptch_display_captcha_custom();


            return $form_part;
        }

        add_filter('login_form_middle', 'wppb_captcha_add_form_login', 10, 2);
    }


    /*
     * Function that ads the Captcha HTML to Profile Builder form builder
     *
     */
    if( function_exists('cptch_display_captcha_custom') ) {

        function wppb_captcha_add_form_form_builder( $output, $form_location = '' ) {

            if ( $form_location == 'register' ) {
                $cptch_options = get_option('cptch_options');

                if (1 == $cptch_options['cptch_register_form']) {
                    $output = '<li>' . cptch_display_captcha_custom() . '</li>' . $output;
                }
            }


            return $output;
        }

        add_filter( 'wppb_after_form_fields', 'wppb_captcha_add_form_form_builder', 10, 2 );
    }


    /*
     * Function that displays the Captcha error on register form
     *
     */
    if( function_exists('cptch_register_post') ) {

        function wppb_captcha_register_form_display_error() {

            if ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'register') {

                $result = cptch_register_post('', '', new WP_Error());

                if ($result->get_error_message('captcha_wrong'))
                    echo '<p class="wppb-error">' . $result->get_error_message('captcha_wrong') . '</p>';

                if ($result->get_error_message('captcha_blank'))
                    echo '<p class="wppb-error">' . $result->get_error_message('captcha_blank') . '</p>';

            }

        }

        add_action('wppb_before_register_fields', 'wppb_captcha_register_form_display_error' );
    }

    /*
     * Function that validates captcha value on register form
     *
     */
    if( function_exists('cptch_register_post') ) {

        function wppb_captcha_register_form_check_value($output_field_errors) {

            if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'register') {

                $result = cptch_register_post('', '', new WP_Error());

                if ($result->get_error_message('captcha_wrong'))
                    $output_field_errors[] = $result->get_error_message('captcha_wrong');

                if ($result->get_error_message('captcha_blank'))
                    $output_field_errors[] = $result->get_error_message('captcha_blank');
            }


            return $output_field_errors;
        }

        add_filter('wppb_output_field_errors_filter', 'wppb_captcha_register_form_check_value');
    }


    /*
     * Function that ads the Captcha HTML to PB custom recover password form
     *
     */
    if ( function_exists('cptch_display_captcha_custom') ) {

        function wppb_captcha_add_form_recover_password($output, $username_email = '') {


            $cptch_options = get_option('cptch_options');

            if (1 == $cptch_options['cptch_lost_password_form']) {
                $output = str_replace('</ul>', '<li>' . cptch_display_captcha_custom() . '</li>' . '</ul>', $output);
            }


            return $output;
        }

        add_filter('wppb_recover_password_gemerate_password_input', 'wppb_captcha_add_form_recover_password', 10, 2);
    }

    /*
     * Function that changes the messageNo from the Recover Password form
     *
     */
    if( function_exists('cptch_register_post') ) {

        function wppb_captcha_recover_password_message_no($messageNo) {

            if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'recover_password') {

                $result = cptch_register_post('', '', new WP_Error());

                if ($result->get_error_message('captcha_wrong') || $result->get_error_message('captcha_blank'))
                    $messageNo = '';

            }

            return $messageNo;
        }

        add_filter('wppb_recover_password_message_no', 'wppb_captcha_recover_password_message_no');
    }

    /*
     * Function that adds the captcha error message on Recover Password form
     *
     */
    if( function_exists('cptch_register_post') ) {

        function wppb_captcha_recover_password_displayed_message1($message) {

            if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'recover_password') {

                $result = cptch_register_post('', '', new WP_Error());
                $error_message = '';

                if ($result->get_error_message('captcha_wrong'))
                    $error_message = $result->get_error_message('captcha_wrong');

                if ($result->get_error_message('captcha_blank'))
                    $error_message = $result->get_error_message('captcha_blank');

                if( empty($error_message) )
                    return $message;

                if ($message == '<p class="wppb-warning">wppb_captcha_error</p>')
                    $message = '<p class="wppb-warning">' . $error_message . '</p>';
                else
                    $message = $message . '<p class="wppb-warning">' . $error_message . '</p>';

            }

            return $message;
        }

        add_filter('wppb_recover_password_displayed_message1', 'wppb_captcha_recover_password_displayed_message1');
    }


    /*
     * Function that changes the default success message to wppb_captcha_error if the captcha
     * doesn't validate so that we can change the message displayed with the
     * wppb_recover_password_displayed_message1 filter
     *
     */
    if( function_exists('cptch_register_post') ) {

        function wppb_captcha_recover_password_sent_message_1($message) {

            if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'recover_password') {

                $result = cptch_register_post('', '', new WP_Error());

                if ($result->get_error_message('captcha_wrong') || $result->get_error_message('captcha_blank'))
                    $message = 'wppb_captcha_error';

            }

            return $message;
        }

        add_filter('wppb_recover_password_sent_message1', 'wppb_captcha_recover_password_sent_message_1');
    }