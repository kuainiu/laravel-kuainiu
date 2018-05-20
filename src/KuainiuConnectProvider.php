<?php

namespace Kuainiu;

use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\ProviderInterface;
use Laravel\Socialite\Two\User;

/**
 * Class KuainiuConnectProvider.
 */
class KuainiuConnectProvider extends AbstractProvider implements ProviderInterface
{

    protected $scopes = ['user_basic'];
    protected $domain = 'https://kuainiu.io';

    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase($this->domain . '/oauth/authorize', $state);
    }

    protected function getTokenUrl()
    {
        return $this->domain .'/oauth/token';
    }

    /**
     * Get the access token with a refresh token.
     *
     * @param string $refresh_token
     *
     * @return array
     */
    public function getRefreshTokenResponse($refresh_token)
    {
        $response = $this->getHttpClient()->post($this->getTokenUrl(), [
            'headers' => ['Accept' => 'application/json'],
            'form_params' => $this->getRefreshTokenFields($refresh_token),
        ]);
        return json_decode($response->getBody(), true);
    }

    /**
     * Get the refresh token fields with a refresh token.
     *
     * @param string $refresh_token
     *
     * @return array
     */
    protected function getRefreshTokenFields($refresh_token)
    {

        return [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'grant_type' => 'refresh_token',
            'refresh_token' => $refresh_token,
        ];
    }

    /**
     * Get the POST fields for the token request.
     *
     * @param string $code
     *
     * @return array
     */
    public function getTokenFields($code)
    {
        return array_add(parent::getTokenFields($code), 'grant_type', 'authorization_code');
    }

    /**
     * Get the raw user for the given access token.
     *
     * @param string $token
     *
     * @return array
     */
    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->get($this->domain .'/api/v1/users/me', [
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer '.$token,
            ],
        ]);

        return json_decode($response->getBody(), true);
    }

    /**
     * Map the raw user array to a Socialite User instance.
     *
     * @param array $user
     *
     * @return \Laravel\Socialite\Two\User
     */
    protected function mapUserToObject(array $user)
    {
        return (new User)->setRaw($user)->map([
            'id' => $user['data']['id'],
            'username' => $user['data']['name'],
            'employee_num' => $user['data']['employee_num'],
            'username' => $user['data']['name'],
            'chinese_name' => $user['data']['chinese_name'],
            'english_name' => $user['data']['english_name'],
            'email' => $user['data']['email'],
            'avatar' => $user['data']['avatar'],
            'departments' => $user['data']['departments'],
        ]);
    }
}
