<?php
namespace CB;

if (!defined("ABSPATH")) exit;

/**
 * Add rewrite endpoint for several actions
 */
add_action('init', function () {
    add_rewrite_endpoint('payment', EP_PERMALINK);
    add_rewrite_endpoint('register', EP_PERMALINK);
});

/**
 * Register rewrite endpoint
 */
add_filter( 'template_include', function ($single_template) {
    global $wp_query, $post;

    if (!is_singular(Posttypes::$post_type))
        return $single_template;

    $template = null;

    if (isset($wp_query->query_vars['payment'])) {
        $template = CB_TEMPLATES . 'single-class-schedule-payment.php';

        if (!is_student_logged_in()) {
            wp_redirect(get_permalink($post->ID));
            exit;
        }

    } else if (isset($wp_query->query_vars['register'])) {
        if (is_student_logged_in()) {
            wp_redirect(get_permalink($post->ID) . 'payment');
            exit;
        }

        $template = CB_TEMPLATES . 'single-class-schedule-register.php';
    }

    if (isset($template) && $post->post_type == Posttypes::$post_type) {
        $single_template = $template;
    }

    return $single_template;
}, 9999);

add_action( 'delete_post', function ($post_id) {
    $cb_post_page_ids = get_option('cb_post_page_ids');

    if (empty($cb_post_page_ids)) return $post_id;

    $key = array_search($post_id, $cb_post_page_ids);

    if ($key !== false) unset($cb_post_page_ids[$key]);

    update_option('cb_post_page_ids', $cb_post_page_ids);
});