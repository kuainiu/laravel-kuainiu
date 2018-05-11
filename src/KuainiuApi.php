<?php

namespace Kuainiu;

use GuzzleHttp\Client;

class KuainiuApi
{
    const API_ENDPOINT = "https://kuainiu.io/api";

    protected $httpClient;
    protected $guzzle = [];
    protected $access_token;


    public function setAccessToken ($access_token)
    {
        $this->access_token = $access_token;
    }

    public function getUser()
    {
        $response = $this->getHttpClient()->get('https://kuainiu.io/api/user', [
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer '.$this->access_token,
            ],
        ]);

        return json_decode($response->getBody(), true);
    }



    /**
     * Get a instance of the Guzzle HTTP client.
     *
     * @return \GuzzleHttp\Client
     */
    protected function getHttpClient()
    {
        if (is_null($this->httpClient)) {
            $this->httpClient = new Client($this->guzzle);
        }

        return $this->httpClient;
    }
}
