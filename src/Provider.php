<?php

namespace PcrMapping\SocialiteOsu;

use GuzzleHttp\RequestOptions;
use SocialiteProviders\Manager\Oauth2\AbstractProvider;
use SocialiteProviders\Manager\Oauth2\User;

class Provider extends AbstractProvider
{
    public const IDENTIFIER = 'osu';

    /**
     * {@inheritdoc}
     */
    protected $scopes = [
        'identify',
        'public'
    ];

    /**
     * {@inheritdoc}
     */
    protected $consent = false;

    /**
     * {@inheritdoc}
     */
    protected $scopeSeperator = ' ';

    /**
     * {@inheritdoc}
     */
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase(
            'https://osu.ppy.sh/oauth/authorize',
            $state
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function getCodeFields($state = null)
    {
        $fields = parent::getCodeFields($state);

        if (!$this->consent) {
            $fields['prompt'] = 'none';

        return $fields;
        }
    }

    /**
     * Whether to prompt the user for consent every time or not.
     *
     * @return $this
     */
    public function withConsent()
    {
        $this->consent = true;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenUrl()
    {
        return 'https://osu.ppy.sh/oauth/token';
    }

    /**
     * {@inheritdoc}
     */
    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->get(
            'https://osu.ppy.sh/me',
            [
                RequestOptions::HEADERS => [
                    'Authorization' => 'Bearer ' . $token
                ],
            ]
        );

        return json_decode((string) $response->getBody(), true);
    }

    /**
     * {@inheritdoc}
     */
    protected function mapUserToObject(array $user)
    {
        return (new User())->setRaw($user)->map([
            'avatar' => $user['avatar_url'],
            'id' => $user['id'],
            'is_bot' => $user['is_bot'],
            'is_restricted' => $user['is_restricted'],
            'is_supporter' => $user['is_supporter'],
            'username' => $user['username'],
        ]);
    }
}
