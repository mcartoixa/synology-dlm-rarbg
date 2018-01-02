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
            curl_setopt_array($curl, array(
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_URL => SynoDLMSearchRarBG::API_URL.'get_token=get_token',
                CURLOPT_USERAGENT => RARBG_USER_AGENT,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_SSL_VERIFYPEER => false
            ));
            $response = curl_exec($curl);
            $this->expiration = time() + (14 * 60);
            $this->token = json_decode($response)->token;
        }
    }

    private $token;
    private $expiration;
}
?>