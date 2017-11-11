<?php

namespace PhpTools;

/**
 * HTTPResponse for HTTPClient
 */
class HTTPResponse
{
    public $body;
    public $contentType;
    public $httpCode;
    public $totalTime;
    public $connectTime;
    public $sizeUpload;
    public $sizeDownload;
    public $primaryIp;
    public $primaryPort;
    public $localIp;
    public $localPort;
    public $redirectUrl;
    public $exception;

    /**
     * @param [type] $curl
     * @param [type] $result
     */
    public function __construct($curl, $result)
    {
        if (!\is_resource($curl)) {
            throw new \Exception('Bad CURL resource');
        }
        $info = curl_getinfo($curl);
        $this->contentType = $info['content_type'];
        $this->httpCode = $info['http_code'];
        $this->totalTime = $info['total_time'];
        $this->connectTime = $info['connect_time'];
        $this->sizeUpload = $info['size_upload'];
        $this->sizeDownload = $info['size_download'];
        $this->primaryIp = $info['primary_ip'];
        $this->primaryPort = $info['primary_port'];
        $this->localIp = $info['local_ip'];
        $this->localPort = $info['local_port'];
        $this->redirectUrl = $info['redirect_url'];
        $this->body = $result;
    }

    public function parseJson()
    {
        return json_decode($this->body, true);
    }
}
