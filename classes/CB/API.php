<?php
namespace CB;

if (!defined("ABSPATH")) exit;

class API extends Abstract_ClassByte
{
    private $email = "";
    private $apikey = "";

    public $response = array();

    public $apiurls = array(
        'auth' => array(
            'verify' => '/auth/verify'
        ),
        'courses' => array(
            'listing' => '/courses/listing'
        )
    );

    public function __construct()
    {
        $email = get_option('cb_cb_username');
        $apikey = get_option('cb_cb_api');

        if (!$email || !$apikey) {
            // error message
        } else {
            $this->email = $email;
            $this->apikey = $apikey;
        }
    }

    public function post($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->site_url($url));
        curl_setopt($ch, CURLOPT_USERPWD, "{$this->email}:{$this->apikey}");
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, array());
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $this->response = curl_exec($ch);
        curl_close($ch);

        return $this;
    }

    public function jsonDecode()
    {
        if (!$this->response) {
            return false;
        }

        return json_decode($this->response, true);
    }

    public function site_url($param = "")
    {
        return 'http://dev.classbyte.net/api' . $param;
        #return site_url('/api' . $param);
    }
}