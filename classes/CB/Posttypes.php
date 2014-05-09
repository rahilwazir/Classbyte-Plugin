<?php
namespace CB;

if (!defined("ABSPATH")) exit;

class Posttypes
{
    public $post_type = "class-schedule";
    public $taxonomy = "courses";

    public function __construct()
    {
        add_action('init', array($this, 'registerPostType'));
        add_action('init', array($this, 'registerTaxonomy'));
    }

    public function registerPostType()
    {
        $labels = array(
            'name' => _x('Classes', $this->post_type),
            'singular_name' => _x('Class', $this->post_type),
            'add_new' => _x('Add Class', $this->post_type),
            'add_new_item' => __('Add Class'),
            'edit_item' => __('Edit Class'),
            'new_item' => __('New Class'),
            'view_item' => __('View Class'),
            'search_items' => __('Search Class'),
            'not_found' => __('No Class found'),
            'not_found_in_trash' => __('No Class found in Trash'),
            'parent_item_colon' => '',
            'menu_name' => __('Schedule Classes')
        );

        // Some arguments and in the last line 'supports', we say to WordPress what features are supported on the Project post type
        $shows_type = array(
            'labels' => $labels,
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true, #'edit.php?post_type=' . Children::$post_type,
            'capability_type' => 'post',
            'map_meta_cap' => false,
            'capabilities' => array(
                'create_posts' => false
            ),
            'rewrite' => array('slug' => 'book'),
            'has_archive' => true,
            'hierarchical' => true,
            'menu_icon' => '/images/children.png',
            'supports' => array('title', 'editor', 'author', 'thumbnail'),
            'taxonomies' => array($this->taxonomy),
        );

        register_post_type($this->post_type, $shows_type);
    }

    public function registerTaxonomy()
    {
        $labels = array(
            'name' => _x('Courses', $this->taxonomy),
            'singular_name' => _x('Courses', $this->taxonomy),
            'search_items' => __('Search Courses'),
            'popular_items' => __('Popular Courses'),
            'all_items' => __('All Courses'),
            'parent_item' => null,
            'parent_item_colon' => null,
            'edit_item' => __('Edit Course'),
            'update_item' => __('Update Course'),
            'add_new_item' => __('Add New Course'),
            'new_item_name' => __('New Course Name'),
            'menu_name' => __('Courses'),
        );

        // Now register the non-hierarchical taxonomy like tag
        register_taxonomy($this->taxonomy, $this->post_type, array(
            'hierarchical' => true,
            'labels' => $labels,
            'show_ui' => true,
            'show_admin_column' => true,
            'map_meta_cap' => false,
            'capabilities' => array(
                'manage_terms' => 'read', // Using 'edit_users' cap to keep this simple.
                'assign_terms' => 'read',
            ),
            'update_count_callback' => '_update_post_term_count',
            'taxonomies' => array($this->taxonomy),
            'query_var' => true,
        ));
    }
}