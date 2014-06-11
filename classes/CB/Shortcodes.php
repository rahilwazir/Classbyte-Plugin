<?php
namespace CB;

if (!defined("ABSPATH")) exit;

class Shortcodes
{
    public function __construct()
    {
        add_shortcode('cb_class_listing', array($this, 'classListing'));
        add_shortcode('cb_class_schedule_login', array($this, 'scheduleLogin'));
        add_shortcode('cb_course_history', array($this, 'courseHistory'));
    }

    public function classListing($atts, $content = null)
    {
        include CB_TEMPLATES . 'page-class-schedule.php';
    }

    public function scheduleLogin($atts, $content = null)
    {
        extract(shortcode_atts(array(
            'parent' => "yes",
            'reg_header' => "no"
        ), $atts));

        if ($parent == "yes") echo '<div id="cb-form-area" class="clearfix">';

        include CB_TEMPLATES . 'class-schedule-login.php';

        if ($parent == "yes") echo '</div>';
    }

    public function courseHistory($atts, $content = null)
    {
        include CB_TEMPLATES . 'page-course-history.php';
    }
}