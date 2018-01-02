<?php
require "vendor/autoload.php";
require "RarBGToken.php";

define('RARBG_USER_AGENT', 'Mozilla/5.0 (Windows NT 6.3; Trident/7.0; rv:11.0) like Gecko');

class SynoDLMSearchRarBG
{
    // cf. https://torrentapi.org/apidocs_v2.txt
    const API_URL = 'https://torrentapi.org/pubapi_v2.php?';

    public function __construct()
    {
        $this->token = new RarBGToken;
    }

    public function prepare($curl, $query)
    {
        $this->token->refresh();
        $url = SynoDLMSearchRarBG::API_URL.http_build_query(array(
              'mode' => 'search',
              'search_string' => $query,
              'sort' => 'seeders',
              'ranked' => 0,
              'format' => 'json_extended',
              'token' => strval($this->token)
        ));
        Echo 'URL: '.$url."\n";
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_URL => $url,
            CURLOPT_USERAGENT => RARBG_USER_AGENT
        ));
    }

    public function parse($plugin, $response)
    {
        $results = json_decode($response);
        foreach ($results->torrent_results as $result)
        {
            $title = $result->title;
            $download = $result->download;
            $size = floatval($result->size);
            $datetime = substr($result->pubdate, 0, 19);
            $page = $result->info_page;
            $hash = '';
            $seeds = $result->seeders;
            $leechs = $result->leechers;
            $category = $result->category;

            $plugin->addResult($title, $download, $size, $datetime, $page, $hash, $seeds, $leechs, $category);
        }
    }

    private $token;
}
?>