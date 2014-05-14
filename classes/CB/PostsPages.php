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
        
        if (is_array($cb_post_page_ids)) {
            foreach ($cb_post_page_ids as $id) {
                wp_delete_post($id, true);
            }
        }

        // delete custom post type classes(posts)
        $posts = get_posts(array(
            'post_type' => Posttypes::$post_type,
            'numberposts' => -1
        ));

        if (is_array($posts)) {
            foreach($posts as $post) {
                wp_delete_post($post->ID, true);
            }
        }

        // delete custom post type terms
        delete_custom_terms(Posttypes::$taxonomy);
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