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
            $this->registerAdmin(new Dashboard());
        }

        $this->registerWidgets(new Widgets());
        $this->registerAPI(new API());
        $this->registerSC(new Shortcodes());

        $this->registerPosttypes(new Posttypes());

        add_action('wp_enqueue_scripts', array($this, 'scripts'));
    }

    private function registerSC(Shortcodes $shortcodes)
    {
        $this->shortcodes = $shortcodes->__init();
    }

    private function registerWidgets(Widgets $widgets)
    {
        $this->widget = $widgets->__init();
    }

    private function registerAdmin(Dashboard $dashboard)
    {
        $this->dashboard = $dashboard->__init();
    }

    private function registerPosttypes(Posttypes $posttypes)
    {
        $this->posttypes = $posttypes->__init();
    }

    private function registerAPI(API $api)
    {
        $this->api = $api->__init();
        #$r = $this->api->post($this->api->apiurls['courses']['listing'])->jsonDecode();
        #var_dump($r);
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
        wp_enqueue_script('cb', plugins_url('js/cb.js', __DIR__), array('jquery'), false, true);

        wp_localize_script('cb', 'cbConfig', array(
            'site_url' => site_url(),
            'admin_url' => admin_url('admin-ajax.php')
        ));
    }

    /*public function __set($name, $value)
    {
        $this->var[$name] = $value;
    }

    public function __get($name)
    {
        return $this->var[$name];
    }*/
}