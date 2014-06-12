<?php
namespace CB;

if (!defined("ABSPATH")) exit;

class API
{
    private static $email = "";
    private static $apikey = "";

    public static $url;

    public static $response = array();

    public static $apiurls = array(
        'auth' => array(
            'verify' => '/auth/verify',
            'userin' => '/auth/userin',
            'userout' => '/auth/userout'
        ),
        'courses' => array(
            'listing' => '/courses/listing',
            'history' => '/courses/history',
            'paid/:id' => '/courses/paid'
        ),
        'sign' => array(
            'up' => '/sign/up',
            'in' => '/sign/in',
            'out' => '/sign/out'
        ),
        'users' => array(
            'info' => '/users/info'
        ),
        'payment' => array(
            'pay' => '/payment/pay'
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

        self::$url = $url;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERPWD, self::$email . ":" . self::$apikey);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        if (isset($_COOKIE['__cbapi'])) {
            curl_setopt($ch, CURLOPT_COOKIE, '__cbapi=' . $_COOKIE['__cbapi']);
        }

        $response = curl_exec($ch);

        curl_close($ch);
        self::$response = $response;

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
    }

    public function insertCourseClasses()
    {
        if (!self::$response || isset(self::$response['code'])) return;

        foreach (self::$response as $course) {
            foreach ($course['classes'] as $class) {
                $title = $class['coursetypename'] . ' ' . date("F-d-Y", strtotime($class['coursedate'])) . ' ' . $class['location'] . ' Class ' . $class['scheduledcoursesid'];

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