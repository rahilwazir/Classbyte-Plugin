<?php
if (!defined("ABSPATH")) exit;

function store_post_page_ids($id)
{
    $id = intval(trim($id));

    if (empty($id))
        return;

    $arr = array();
    $cb_post_page_ids = get_option('cb_post_page_ids');

    if (!$cb_post_page_ids) {
        array_push($arr, $id);
        add_option('cb_post_page_ids', $arr);
    } else {
        array_push($cb_post_page_ids, $id);
        update_option('cb_post_page_ids', $cb_post_page_ids);
    }
}

function recursive_array_search($needle,$haystack)
{
    foreach($haystack as $key=>$value) {
        $current_key=$key;
        if($needle === $value OR (is_array($value) && recursive_array_search($needle, $value) !== false)) {
            return $current_key;
        }
    }
    return false;
}

function delete_custom_terms($taxonomy){
    global $wpdb;

    $query = 'SELECT t.name, t.term_id
            FROM ' . $wpdb->terms . ' AS t
            INNER JOIN ' . $wpdb->term_taxonomy . ' AS tt
            ON t.term_id = tt.term_id
            WHERE tt.taxonomy = "' . $taxonomy . '"';

    $terms = $wpdb->get_results($query);

    foreach ($terms as $term) {
        wp_delete_term( $term->term_id, $taxonomy );
    }
}