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
        PostsPages::deleteAll(true);
        API::post(API::$apiurls['courses']['listing'])->jsonDecode()->insertCourseClasses();

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
        $response = API::post(API::$apiurls['courses']['history'])->jsonDecode()->getResponse();

        $course_history = array();

        if (isset($response['success'], $response['action']) && $response['success'] == true) {
            $course_history = $response['object'];
        }

        include CB_TEMPLATES . 'page-course-history.php';
    }
}