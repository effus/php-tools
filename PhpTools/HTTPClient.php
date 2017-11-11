<?php

namespace PhpTools;

use PhpTools\HTTPResponse;

/**
 * Class HttpClient
 */
class HttpClient
{
    /**
     * @param $url
     * @param null $body
     * @param array $headers
     * @param string $method
     * @param int $timeout
     * @param string $agent
     * @return array
     */
    static function request(
        $url,
        $body = null,
        $headers = array(),
        $method = 'GET',
        $timeout = 30,
        $agent = 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:47.0) Gecko/20100101 Firefox/47.0'
    ) {
        try {
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_USERAGENT, $agent);
            if ($headers) {
                curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            }
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 1);
            curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
            if ($body) {
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
            }
            if ($method!='GET' || $method!='POST') {
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
            }
            $body = curl_exec($curl);
            if ($err = curl_error($ch)) {
                throw new \Exception($err);
            }
            $out = new HTTPResponse($curl, $body);
            curl_close($curl);
        } catch (\Exception $e) {
            $out->exception = $e->getMessage();
        }
        return $out;
    }

    /**
     * @param $url
     * @param $destination
     * @return mixed
     */
    static function download($url, $destination)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($httpCode==200) {
            $ch = curl_init($url);
            $fp = fopen($destination, 'wb');
            curl_setopt($ch, CURLOPT_FILE, $fp);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_exec($ch);
            curl_close($ch);
            fclose($fp);
        }
        return $httpCode;
    }

    /**
     * @param $source
     * @param $url
     * @param $postfields
     * @return mixed
     */
    static function upload($source, $url, $postfields)
    {
        $curl = curl_init();
        $fn = basename($source);
        $source = realpath($source);
        $postfields['file']="@$source;filename=$fn";
        //print_r($postfields);
        $opts = array(
            CURLOPT_URL => $url,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => $postfields,
            CURLOPT_INFILESIZE => filesize($source),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
        );
        curl_setopt_array($curl, $opts);
        $res = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        return [$httpCode,$res];
    }
}
