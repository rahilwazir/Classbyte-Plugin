<?php
namespace CB;

if (!defined("ABSPATH")) exit;

class ClassByte extends Abstract_ClassByte
{
    public function __construct()
    {
        register_activation_hook(__DIR__ . basename(__FILE__), array($this, 'activation'));
        register_deactivation_hook(__DIR__ . basename(__FILE__), array($this, 'deactivation'));

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

    public function activation()
    {
        if (!PostsPages::exists()) {
            PostsPages::add();
        } else {
            PostsPages::unTrashAll();
        }
    }

    public function deactivation()
    {
        PostsPages::trashAll();
    }

    public function scripts()
    {
        wp_enqueue_script('bootstrap-js', CB_URL . 'js/bootstrap.min.js', array('jquery'), false, true);
        wp_enqueue_script('cb', CB_URL . 'js/cb.js', array('jquery'), false, true);

        wp_enqueue_style('bootstrap-css', CB_URL . 'css/bootstrap.min.css');
        wp_enqueue_style('bootstrap-theme-css', CB_URL . 'css/bootstrap-theme.min.css');

        wp_localize_script('cb', 'cbConfig', array(
            'site_url' => site_url(),
            'admin_url' => admin_url('admin-ajax.php')
        ));
    }
}