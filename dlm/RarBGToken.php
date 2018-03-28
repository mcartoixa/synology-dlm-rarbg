<?php
require "vendor/autoload.php";

class RarBGToken
{

    public function __construct()
    { }

    public function __toString()
    {
        if (empty($this->token))
        {
            return '';
        } else
        {
            return $this->token;
        }
    }

    public function refresh($force = false)
    {
        $now = time();
        if (empty($this->token) || $force || ($this->expiration <= time()))
        {
            $this->expiration = $now;
            $this->token = NULL;

            $curl = curl_init();
            $url = SynoDLMSearchRarBG::API_URL.http_build_query(array(
                'get_token' => 'get_token',
                'app_id' => SynoDLMSearchRarBG::APP_ID
            ));
            curl_setopt_array($curl, array(
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_URL => $url,
                CURLOPT_USERAGENT => RARBG_USER_AGENT,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_SSL_VERIFYPEER => false
            ));
            $response = curl_exec($curl);

            $this->expiration = time() + (14 * 60);
            $this->token = json_decode($response)->token;

            sleep(2); // API is rate limited
        }
    }

    private $token;
    private $expiration;
}
?>