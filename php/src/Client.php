<?php

namespace Yoti\DocScan;

use Yoti\Http\RequestBuilder;
use Yoti\Http\Payload;

class Client
{
    const SDK_KEY = 'sdkId';

    /**
     * @var string
     */
    private $sdkId;

    /**
     * @var string
     */
    private $pem;

    /**
     * @var string
     */
    private $baseUrl;

    /**
     * @param string $sdk_id
     * @param string $pem
     */
    public function __construct($sdk_id, $pem, $base_url)
    {
        $this->sdkId = $sdk_id;
        $this->pem = $pem;
        $this->baseUrl = $base_url;
    }

    /**
     * @return RequestBuilder
     */
    private function createRequestBuilder()
    {
        return (new RequestBuilder())
            ->withPemString($this->pem)
            ->withBaseUrl($this->baseUrl)
            ->withQueryParam(self::SDK_KEY, $this->sdkId);
    }

    /**
     * Creates a session.
     *
     * @param array $payload_data
     *
     * @return \Yoti\Http\Response
     */
    public function createSession($payload_data = [])
    {
        return $request = (new RequestBuilder())
        ->withBaseUrl($this->baseUrl)
        ->withPemString($this->pem)
        ->withEndpoint('/sessions')
        ->withPayload(new Payload($payload_data))
        ->withMethod('POST')
        ->withQueryParam(self::SDK_KEY, $this->sdkId)
        ->build()
        ->execute();
    }
    /**
     * Retrieves a session.
     *
     * @param string $session_id
     *
     * @return \Yoti\Http\Response
     */
    public function getSession($session_id)
    {
        return $request = (new RequestBuilder())
        ->withBaseUrl($this->baseUrl)
        ->withPemString($this->pem)
        ->withEndpoint("/sessions/$session_id")
        ->withMethod('GET')
        ->withQueryParam(self::SDK_KEY, $this->sdkId)
        ->build()
        ->execute();
    }

    /**
     * Retrieves session media.
     *
     * @param string $session_id
     * @param string $media_id
     *
     * @return \Yoti\Http\Response
     */
    public function getSessionMedia($session_id, $media_id)
    {
        return $request = (new RequestBuilder())
        ->withBaseUrl($this->baseUrl)
        ->withPemString($this->pem)
        ->withEndpoint("/sessions/$session_id/media/$media_id/content")
        ->withMethod('GET')
        ->withQueryParam(self::SDK_KEY, $this->sdkId)
        ->build()
        ->execute();
    }

}
