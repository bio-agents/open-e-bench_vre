<?php

namespace League\OAuth2\Client\Provider;

use League\OAuth2\Client\Exception\HostedDomainException;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Agent\BearerAuthorizationTrait;
use Psr\Http\Message\ResponseInterface;

class Google extends AbstractProvider
{
    use BearerAuthorizationTrait;

    const ACCESS_TOKEN_RESOURCE_OWNER_ID = 'id';

    /**
     * @var string If set, this will be sent to google as the "access_type" parameter.
     * @link https://developers.google.com/accounts/docs/OAuth2WebServer#offline
     */
    protected $accessType;

    /**
     * @var string If set, this will be sent to google as the "hd" parameter.
     * @link https://developers.google.com/accounts/docs/OAuth2Login#hd-param
     */
    protected $hostedDomain;

    /**
     * @var array Default fields to be requested from the user profile.
     * @link https://developers.google.com/+/web/api/rest/latest/people
     */
    protected $defaultUserFields = [
        'id',
        'name(familyName,givenName)',
        'displayName',
        'emails/value',
        'image/url',
    ];
    /**
     * @var array Additional fields to be requested from the user profile.
     *            If set, these values will be included with the defaults.
     */
    protected $userFields = [];

    /**
     * Use OpenID Connect endpoints for getting the user info/resource owner
     * @var bool
     */
    protected $useOidcMode = false;

    public function getBaseAuthorizationUrl()
    {
        return 'https://accounts.google.com/o/oauth2/auth';
    }

    public function getBaseAccessTokenUrl(array $params)
    {
        return 'https://www.googleapis.com/oauth2/v4/token';
    }

    public function getResourceOwnerDetailsUrl(AccessToken $token)
    {
        if ($this->useOidcMode) {
            // OIDC endpoints can be found https://accounts.google.com/.well-known/openid-configuration
            return 'https://www.googleapis.com/oauth2/v3/userinfo';
        }
        // fields that are required based on other configuration options
        $configurationUserFields = [];
        if (isset($this->hostedDomain)) {
            $configurationUserFields[] = 'domain';
        }
        $fields = array_merge($this->defaultUserFields, $this->userFields, $configurationUserFields);
        return 'https://www.googleapis.com/plus/v1/people/me?' . http_build_query([
            'fields' => implode(',', $fields),
            'alt'    => 'json',
        ]);
    }

    protected function getAuthorizationParameters(array $options)
    {
        $params = array_merge(
            parent::getAuthorizationParameters($options),
            array_filter([
                'hd'          => $this->hostedDomain,
                'access_type' => $this->accessType,
                // if the user is logged in with more than one account ask which one to use for the login!
                'authuser'    => '-1'
            ])
        );

        return $params;
    }

    protected function getDefaultScopes()
    {
        return [
            'email',
            'openid',
            'profile',
        ];
    }

    protected function getScopeSeparator()
    {
        return ' ';
    }

    protected function checkResponse(ResponseInterface $response, $data)
    {
        if (!empty($data['error'])) {
            $code  = 0;
            $error = $data['error'];

            if (is_array($error)) {
                $code  = $error['code'];
                $error = $error['message'];
            }

            throw new IdentityProviderException($error, $code, $data);
        }
    }

    protected function createResourceOwner(array $response, AccessToken $token)
    {
        $user = new GoogleUser($response);
        // Validate hosted domain incase the user edited the initial authorization code grant request
        if ($this->hostedDomain === '*') {
            if (empty($user->getHostedDomain())) {
                throw HostedDomainException::notMatchingDomain($this->hostedDomain);
            }
        } elseif (!empty($this->hostedDomain) && $this->hostedDomain !== $user->getHostedDomain()) {
            throw HostedDomainException::notMatchingDomain($this->hostedDomain);
        }

        return $user;
    }
}
