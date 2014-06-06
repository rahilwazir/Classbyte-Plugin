<?php
namespace CB;

if (!defined("ABSPATH")) exit;

class Ajax
{
    public function __construct()
    {
        add_action('wp_ajax_cb_form', array($this, 'cb_handle_form'));
        add_action('wp_ajax_nopriv_cb_form', array($this, 'cb_handle_form'));

        add_action('wp_ajax_cb_switch_forms', array($this, 'cb_switching_form'));
        add_action('wp_ajax_nopriv_cb_switch_forms', array($this, 'cb_switching_form'));
    }

    public function cb_handle_form()
    {
        if (isset($_POST['action'], $_POST['form_name']) && $_POST['action'] === 'cb_form') {
            $form_data = $_POST['form_data'];

            simplify_serialize_data($form_data);

            if (!wp_verify_nonce($form_data['_cb_nonce'], 'cb_forms-only-ajax')) {
                wp_send_json_error("Invalid token");
            }

            $cb_errors = array();

            /**
             * Enroll form validation
             */
            if ($_POST['form_name'] === "cb_enroll_form") {
                if (!wp_verify_nonce($form_data['course_token'], $form_data['course_id'])) {
                    wp_send_json_error("Invalid token");
                }
            }

            /**
             * Register form validation
             */
            if ($_POST['form_name'] === "cb_reg_form") {
                $cb_errors['studentsname'] = ($form_data['studentsname'] === '') ? __('required.')
                    : (!validate_name($form_data['studentsname']) ? __('should be alphabetic.') : '');

                $cb_errors['studentlastname'] = ($form_data['studentlastname'] === '') ? __('required.')
                    : (!validate_name($form_data['studentlastname']) ? __('should be alphabetic.') : '');

                $cb_errors['studentzip'] = ($form_data['studentzip'] === '') ? __('required.')
                    : (!is_numeric($form_data['studentzip']) ? __('should contain numbers only.') : '');

                $cb_errors['studentaddress'] = ($form_data['studentaddress'] === '') ? __('required.') : '';

                $cb_errors['studentemddress'] = ($form_data['studentemddress'] === '') ? __('required.')
                    : (!is_email($form_data['studentemddress']) ? __('not valid.') : '');

                $cb_errors['studentpassword'] = ($form_data['studentpassword'] === '') ? __('required.') : '';

                $cb_errors['studentpassword2'] = ($form_data['studentpassword2'] === '') ? __('required.')
                    : (($form_data['studentpassword'] !== $form_data['studentpassword2']) ? __('did not match.') : '');

            }

            /**
             * Login form validation
             */
            if ($_POST['form_name'] === "cb_login_form") {
                $cb_errors['cb_login_email'] = ($form_data['cb_login_email'] === '') ? __('required.')
                    : (!is_email($form_data['cb_login_email']) ? __('not valid.') : '');

                $cb_errors['cb_login_password'] = ($form_data['cb_login_password'] === '') ? __('required.') : '';
            }

            $cb_errors_clean = array_filter($cb_errors);

            if (!empty($cb_errors_clean)) {
                wp_send_json_error($cb_errors_clean);
            } else {
                switch ($_POST['form_name']) {
                    case 'cb_enroll_form':
                        $data = return_include_once('single-class-schedule-register.php', $form_data);
                        wp_send_json_success($data);
                        break;
                    case 'cb_reg_form':
                        $api_post = API::post(API::$apiurls['sign']['up'], $form_data);
                        $response = $api_post->jsonDecode()->getResponse();

                        if (isset($response['success'], $response['action']) && $response['message'] !== '') {
                            if ($response['success'] == true) {
                                $data = return_include_once('class-schedule-login.php', $response);
                                wp_send_json_success($data);
                            } else {
                                wp_send_json_error($response);
                            }
                        }
                        break;
                    case 'cb_login_form':
                        $api_post = API::post(API::$apiurls['sign']['in'], $form_data);
                        $response = $api_post->jsonDecode()->getResponse();

                        if (isset($response['success'], $response['action'])) {
                            if ($response['success'] == true) {
                                wp_send_json_success($response);
                            } else {
                                wp_send_json_error($response);
                            }
                        }
                        break;
                    default:
                        break;
                }
                wp_send_json_success();
            }
        }
        exit;
    }

    public function cb_switching_form()
    {
        if (isset($_POST['action']) && $_POST['action'] === 'cb_switch_forms') {
            $content = '';

            switch ($_POST['form_of']) {
                case "cb_login_form":
                    $content = return_include_once('class-schedule-login.php');
                    break;
                case "cb_registration_form":
                    $content = return_include_once('single-class-schedule-register.php');
                    break;
                default:
                    break;
            }

            wp_send_json_success($content);
        }
        exit;
    }
}