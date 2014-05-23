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
                wp_send_json_error("Invalid Token");
            }

            $cb_errors = array();

            if ($_POST['form_name'] === "cb_reg_form") {
                $cb_errors['studentsname'] = ($form_data['studentsname'] === '') ? __('required.')
                    : (!validate_name($form_data['studentsname']) ? __('should be alphabetic.') : '');

                $cb_errors['studentlastname'] = ($form_data['studentlastname'] === '') ? __('required.')
                    : (!validate_name($form_data['studentlastname']) ? __('should be alphabetic.') : '');

                $cb_errors['studentaddress'] = ($form_data['studentaddress'] === '') ? __('required.') : '';

                $cb_errors['studentemddress'] = ($form_data['studentemddress'] === '') ? __('required.')
                    : (!is_email($form_data['studentlastname']) ? __('not valid.') : '');

                $cb_errors['studentpassword'] = ($form_data['studentpassword'] === '') ? __('required.') : '';

                $cb_errors['studentpassword2'] = ($form_data['studentpassword2'] === '') ? __('required.')
                    : (($form_data['studentpassword'] !== $form_data['studentpassword2']) ? __('did not match.') : '');

            }

            $cb_errors_clean = array_filter($cb_errors);

            if (!empty($cb_errors_clean)) {
                wp_send_json_error($cb_errors_clean);
            } else {
                ob_start();
                include_once CB_TEMPLATES . 'single-class-schedule-step2.php';
                wp_send_json_success(ob_get_clean());
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
                    ob_start();
                    include_once CB_TEMPLATES . 'single-class-schedule-step2-login.php';
                    $content = ob_get_clean();
                    break;
                case "cb_registration_form":
                    ob_start();
                    include_once CB_TEMPLATES . 'single-class-schedule-step2.php';
                    $content = ob_get_clean();
                    break;
                default:
                    break;
            }

            wp_send_json_success($content);
        }
        exit;
    }
}