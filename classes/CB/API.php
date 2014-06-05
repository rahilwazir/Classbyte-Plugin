<?php
namespace CB;

if (!defined("ABSPATH")) exit;

class API
{
    private static $email = "";
    private static $apikey = "";

    public static $response = array();

    public static $apiurls = array(
        'auth' => array(
            'verify' => '/auth/verify',
            'userin' => '/auth/userin',
            'userout' => '/auth/userout'
        ),
        'courses' => array(
            'listing' => '/courses/listing'
        ),
        'sign' => array(
            'up' => '/sign/up',
            'in' => '/sign/in'
        )
    );

    public function getResponse()
    {
        return self::$response;
    }

    public static function post($url, $data = array())
    {
        self::$email = get_option('cb_cb_username');
        self::$apikey = get_option('cb_cb_api');

        $url = self::site_url($url);

        if (!self::$email || !self::$apikey) {
            $url = self::site_url('/no');
        }

        $useragent = $_SERVER['HTTP_USER_AGENT'];
        $strCookie = 'PHPSESSID=' . $_COOKIE['PHPSESSID'] . '; path=/';

        session_write_close();

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERPWD, self::$email . ":" . self::$apikey);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch,CURLOPT_USERAGENT, $useragent);
        curl_setopt( $ch, CURLOPT_COOKIE, $strCookie );

        self::$response = curl_exec($ch);
        curl_close($ch);

        return new self;
    }

    public function jsonDecode()
    {
        if (self::$response) {
            self::$response = json_decode(self::$response, true);
        }

        return $this;
    }

    public static function site_url($param = "")
    {
        return 'http://dev.classbyte.net/api' . $param;
        #return site_url('/api' . $param);
    }

    public function insertCourseClasses()
    {
        if (!self::$response || isset(self::$response['code'])) return;

        foreach (self::$response as $course) {
            foreach ($course['classes'] as $class) {
                $title = $class['coursetypename'] . ' ' . date("F-d-Y", strtotime($class['coursedate'])) . ' ' . $class['location'] . ' Class ' . $class['scheduledcoursesid'];

                #if (Posttypes::postExists($title)) continue;

                $my_post = array(
                    'post_title' => $title,
                    'post_name' => sanitize_title($title),
                    'post_status' => 'publish',
                    'post_author' => 1,
                    'post_type' => Posttypes::$post_type,
                    'comment_status' => 'closed'
                );

                $cur_post_id = wp_insert_post($my_post);

                if ($cur_post_id) {
                    update_post_meta($cur_post_id, 'cb_zip', $class['locationzip']);

                    update_post_meta($cur_post_id, 'cb_course_schedule_id', $class['scheduledcoursesid']);

                    update_post_meta($cur_post_id, 'cb_course_id', $class['scheduledcoursesid']);

                    update_post_meta($cur_post_id, 'cb_agency', $course['classes'][0]['agency']);

                    update_post_meta($cur_post_id, 'cb_course_location', $class['location']);

                    update_post_meta($cur_post_id, 'cb_course_date_time', date("l, F d, Y", strtotime($class['coursedate'])) . ' at ' . date("g:i a", strtotime($class['coursetime'])));

                    update_post_meta($cur_post_id, 'cb_course_full_object', $class);

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