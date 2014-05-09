<?php
namespace CB;

if (!defined("ABSPATH")) exit;

class Shortcodes extends Abstract_ClassByte
{
    public function __init()
    {
        add_shortcode('cb_class_listing', array($this, 'classListing'));

        return $this;
    }

    public function classListing($atts, $content = null)
    {
        var_dump($this);
        extract(shortcode_atts(array(

        ), $atts));

        $r = $this->api->post($this->api->apiurls['courses']['listing'])->jsonDecode();
        #var_dump($r);
        return;
    }

}