<?php
namespace CB;

if (!defined("ABSPATH")) exit;

class ClassByte extends Abstract_ClassByte
{
    public function __construct()
    {
        if (is_admin()) {
            new Dashboard();
        }

        $this->registerWidgets(new Widgets());
        $this->registerSC(new Shortcodes());

        $this->registerPosttypes(new Posttypes());

        add_action('wp_enqueue_scripts', array($this, 'scripts'));
    }

    private function registerSC(Shortcodes $shortcodes)
    {
        $this->shortcodes = $shortcodes;
    }

    private function registerWidgets(Widgets $widgets)
    {
        $this->widget = $widgets;
    }

    private function registerDashboard(Dashboard $dashboard)
    {
        $this->dashboard = $dashboard;
    }

    private function registerPosttypes(Posttypes $posttypes)
    {
        $this->posttypes = $posttypes;
    }

    public static function activation()
    {
        Posttypes::registerPostType();

        flush_rewrite_rules();

        if ( ! current_user_can( 'activate_plugins' ) )
            return;

        if (!PostsPages::exists()) {
            PostsPages::add();
        } else {
            PostsPages::unTrashAll();
        }
    }

    public static function deactivation()
    {
        Posttypes::registerPostType();

        flush_rewrite_rules();

        if ( ! current_user_can( 'activate_plugins' ) )
            return;

        PostsPages::trashAll();
    }

    public static function uninstall()
    {
        if ( ! current_user_can( 'activate_plugins' ) )
            return;

        global $wpdb;

        // Remove all the form settings
        $wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE 'cb_%'" );

        // Delete all posts/pages
        PostsPages::deleteAll();
    }

    public function scripts()
    {
        wp_enqueue_script('bootstrap-js', ASSETS_URL . 'js/bootstrap.min.js', array('jquery'), false, true);
        wp_enqueue_script('cb', ASSETS_URL . 'js/cb.js', array('jquery'), false, true);

        wp_enqueue_style('bootstrap-css', ASSETS_URL . 'css/bootstrap.min.css');
        wp_enqueue_style('bootstrap-theme-css', ASSETS_URL . 'css/bootstrap-theme.min.css');

        wp_localize_script('cb', 'cbConfig', array(
            'site_url' => site_url(),
            'admin_url' => admin_url('admin-ajax.php')
        ));
    }
}