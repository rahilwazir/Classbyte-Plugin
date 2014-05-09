<?php
namespace CB;

if (!defined("ABSPATH")) exit;

class Shortcodes extends Abstract_ClassByte
{
    public function __construct()
    {
        add_shortcode('cb_class_listing', array($this, 'classListing'));
        $this->api = new API();
    }

    public function classListing($atts, $content = null)
    {
        extract(shortcode_atts(array(

        ), $atts));

        $r = $this->api->post($this->api->apiurls['courses']['listing'])->jsonDecode();
        #var_dump($r);
        return;
    }

}