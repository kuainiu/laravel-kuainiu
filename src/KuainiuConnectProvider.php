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

    protected $scopes            = ['user_basic'];
    protected $oauthServerDomain = 'https://kuainiu.io';
    protected $profileMap        = [ // Optional, but if defined, all must be declared
        'id_field'           => 'id',
        'name_field'         => 'name',
        'nickname_field'     => 'nickname',
        'chinese_name_field' => 'chinese_name',
        'email_field'        => 'email',
        'avatar_field'       => 'avatar',
        'position_field'     => 'position',
        'english_name_field' => 'english_name',
        'departments_field'  => 'departments',
        'certificate_field'  => 'certificate',
    ];

    public function getDomain()
    {
        if (!empty(config('services.kuainiu.oauthServerDomain'))) {
            $this->oauthServerDomain = config('services.kuainiu.oauthServerDomain');
        }

        return $this->oauthServerDomain;
    }

    public function getProfileMap()
    {
        if (!empty(config('kuainiu.profileMap'))) {
            $this->profileMap = config('kuainiu.profileMap');
        }

        return $this->profileMap;
    }

    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase($this->getDomain() . '/oauth/authorize', $state);
    }

    protected function getTokenUrl()
    {
        return $this->getDomain() . '/oauth/token';
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
            'headers'     => ['Accept' => 'application/json'],
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
            'client_id'     => $this->clientId,
            'client_secret' => $this->clientSecret,
            'grant_type'    => 'refresh_token',
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
        $response = $this->getHttpClient()->get($this->getDomain() . '/api/v1/users/me', [
            'headers' => [
                'Accept'        => 'application/json',
                'Authorization' => 'Bearer ' . $token,
            ],
        ]);

        return json_decode($response->getBody(), true);
    }

    /**
     * Map the raw user array to a Socialite User instance.
     *
     * @param array $resp
     *
     * @return \Laravel\Socialite\Two\User
     */
    protected function mapUserToObject(array $resp)
    {
        $user = $resp['data'];

        // get user profile response fields map
        $profileMap = $this->getProfileMap();
        $attributes = [
            'name'         => $user[$profileMap['name_field']],
            'nickname'     => $user[$profileMap['nickname_field']],
            'chinese_name' => $user[$profileMap['chinese_name_field']],
            'position'     => $user[$profileMap['position_field']],
            'avatar'       => $user[$profileMap['avatar_field']],
            'email'        => $user[$profileMap['email_field']],
        ];

        if (!empty($user[$profileMap['id_field']])) {// get user info contains id field
            $attributes['id'] = $user[$profileMap['id_field']];
        }
        if (!empty($user[$profileMap['english_name_field']])) {// get user info contains english_name field
            $attributes['english_name'] = $user[$profileMap['english_name_field']];
        }
        if (!empty($user[$profileMap['departments_field']])) {// get user info contains departments field
            $attributes['departments'] = $user[$profileMap['departments_field']];
        }
        if (!empty($user[$profileMap['certificate_field']])) {// get user info contains certificate field
            $attributes['certificate'] = $user[$profileMap['certificate_field']];
        }

        return (new User)->setRaw($user)->map($attributes);
    }
}
