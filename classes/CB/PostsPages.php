<?php
namespace CB;

if (!defined("ABSPATH")) exit;

class PostsPages
{
    public static function add()
    {
        $title = "Class Schedule";

        $my_post = array(
            'post_title'    => $title,
            'post_content'  => '[cb_class_listing]',
            'post_status'   => 'publish',
            'post_author'   => 1,
            'post_type'     => 'page',
            'comment_status' => 'closed'
        );

        // Insert the post into the database
        $post_id = wp_insert_post( $my_post );

        store_post_page_ids($post_id);
    }

    public static function exists()
    {
        $cb_post_page_ids = get_option('cb_post_page_ids');

        if (!$cb_post_page_ids) {
            return false;
        }

        return true;
    }

    public static function trashAll()
    {
        $cb_post_page_ids = get_option('cb_post_page_ids');
        var_dump($cb_post_page_ids);
        if (!$cb_post_page_ids) {
            return;
        }

        foreach ($cb_post_page_ids as $id) {
            wp_trash_post($id);
        }
    }

    public static function deleteAll()
    {
        self::unTrashAll();

        $cb_post_page_ids = get_option('cb_post_page_ids');

        if (!$cb_post_page_ids)
            return;

        foreach ($cb_post_page_ids as $id) {
            wp_delete_post($id, true);
        }

        $posts = get_posts(array(
            'post_type' => Posttypes::$post_type
        ));

        foreach($posts as $post) {
            wp_delete_post($post->ID, true);

            $cat = get_the_terms($post->ID, Posttypes::$taxonomy);
            $cat_key = array_slice(array_keys($cat), 0, 1);
            $cat = $cat[$cat_key[0]];

            wp_delete_term($cat->term_id, Posttypes::$taxonomy);
        }

    }

    public static function unTrashAll()
    {
        $cb_post_page_ids = get_option('cb_post_page_ids');

        if (!$cb_post_page_ids)
            return;

        foreach ($cb_post_page_ids as $id) {
            wp_update_post(array(
                'ID' => $id,
                'post_status' => 'publish'
            ));
        }
    }
}