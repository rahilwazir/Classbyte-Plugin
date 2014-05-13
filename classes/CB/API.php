<?php
namespace CB;

if (!defined("ABSPATH")) exit;

class API extends Abstract_ClassByte
{
    private static $email = "";
    private static $apikey = "";

    public static $response = array();

    public static $apiurls = array(
        'auth' => array(
            'verify' => '/auth/verify'
        ),
        'courses' => array(
            'listing' => '/courses/listing'
        )
    );

    public static function post($url)
    {
        $url = self::site_url($url);

        if (empty(self::$email) || empty(self::$apikey))
            $url = self::site_url('/no');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERPWD, self::$email . ":" . self::$apikey);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, array());
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        self::$response = curl_exec($ch);
        curl_close($ch);
    }

    public static function jsonDecode()
    {
        if (!self::$response) {
            return false;
        }

        self::$response = json_decode(self::$response, true);
    }

    public static function site_url($param = "")
    {
        return 'http://dev.classbyte.net/api' . $param;
        #return site_url('/api' . $param);
    }

    public static function insertCourseClasses()
    {
        if (!self::$response || isset(self::$response['code'])) return;

        foreach(self::$response as $course) {
            foreach ($course['classes'] as $class) {
                $title = $class['coursetypename'] . '_' . date("F-d-Y",strtotime($class['coursedate'])) . '_' . $class['location'] . '_class_' . $class['scheduledcoursesid'];

                if (Posttypes::postExists($title)) continue;

                $my_post = array(
                    'post_title' => $title,
                    'post_status' => 'publish',
                    'post_author' => 1,
                    'post_type' => Posttypes::$post_type
                );

                $cur_post_id = wp_insert_post($my_post);

                if ($cur_post_id) {
                    update_post_meta($cur_post_id, 'cb_zip', $class['locationzip']);

                    update_post_meta($cur_post_id, 'cb_course_schedule_id', $class['scheduledcoursesid']);

                    update_post_meta($cur_post_id, 'cb_course_id', $class['scheduledcoursesid']);

                    update_post_meta($cur_post_id, 'cb_course_location', $class['location']);

                    update_post_meta($cur_post_id, 'cb_course_date_time', date("l, F d, Y",strtotime($class['coursedate'])) . ' at ' . date("g:i a",strtotime($class['coursetime'])));

                    $cat = \wp_insert_term($course['course']['course_name'], Posttypes::$taxonomy);

                    if (is_wp_error($cat) && array_key_exists('term_exists', $cat->errors))
                        $cat_ID = absint($cat->error_data['term_exists']);
                    else
                        $cat_ID = $cat['term_id'];

                    wp_set_post_terms($cur_post_id, $cat_ID, Posttypes::$taxonomy);
                }
            }
        }

    }
}