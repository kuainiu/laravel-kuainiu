<?php

namespace Kuainiu;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;

class KuainiuApi
{
    const API_DOMAIN = "https://kuainiu.io";

    protected $httpClient;
    protected $guzzle = [];

    public function getUser($uid)
    {
        return $this->request('get','/users/'.$uid);
    }

    public function encrypt($uid, $data)
    {
        $options['form_params'] = [
            'uid' => $uid,
            'data' => $data
        ];
        return $this->request('post','/users/encrypt', $options);
    }

    public function decrypt($uid, $data)
    {
        $options['form_params'] = [
            'uid' => $uid,
            'data' => $data
        ];
        return $this->request('post','/users/decrypt', $options);
    }


    private function getAccessToken ()
    {
        if ($token = Cache::get('laravel-kuainiu-token')) {
            return $token;
        }
        $response  = $this->getHttpClient()->post($this->getTokenUrl(),[
            'form_params' => [
                "grant_type" => "client_credentials",
                "client_id" => config('services.kuainiu.client_id'),
                "client_secret" => config('services.kuainiu.client_secret')
            ]
        ]);
        $result = json_decode($response->getBody(), true);
        $token = $result['access_token'];
        Cache::put('laravel-kuainiu-token', $result['access_token'], $result['expires_in']  / 60);

        return $token;
    }

    private function request($method, $path, $options = [])
    {

        $options['headers'] = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '.$this->getAccessToken(),
        ];

        $response = $this->getHttpClient()->request($method, $this->getApiUrl($path), $options);

        return json_decode($response->getBody(), true);
    }

    /**
     * Get the token URL for the provider.
     *
     * @return string
     */
    private function getTokenUrl()
    {
        return static::API_DOMAIN.'/oauth/token';
    }

    /**
     * Get the token URL for the provider.
     *
     * @return string
     */
    private function getApiUrl($path)
    {
        return static::API_DOMAIN.'/api/v1'.$path;
    }

    /**
     * Get a instance of the Guzzle HTTP client.
     *
     * @return \GuzzleHttp\Client
     */
    private function getHttpClient()
    {
        if (is_null($this->httpClient)) {
            $this->httpClient = new Client($this->guzzle);
        }

        return $this->httpClient;
    }
}
