<?php
require_once 'FakePlugin.php';
require_once '../search.php';
define('DOWNLOAD_STATION_USER_AGENT', "Mozilla/4.0 (compatible; MSIE 6.1; Windows XP)");

$plugin = new FakePlugin;
$search = new SynoDLMSearchRarBG;

$fhandle = fopen(dirname(__FILE__).'/../../tmp/curl.log', 'w');
$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_SSL_VERIFYHOST => false,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_VERBOSE => 1,
    CURLOPT_STDERR => $fhandle
));
$search->prepare($curl, 'test');
$response = curl_exec($curl);
if ($response===false)
{
    echo 'Error: ' . curl_error($curl)."\n";
} else
{
    $search->parse($plugin, $response);
    foreach ($plugin->results as $result)
    {
        printf("\t%s %s %d %d %s\n", $result->title, $result->datetime, $result->seeds, $result->leechs, $result->category);
    }
}
curl_close($curl);
fclose($fhandle);

?>