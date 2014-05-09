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