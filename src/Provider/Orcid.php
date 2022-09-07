<?php

declare(strict_types=1);

namespace MathieuDumoutier\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Token\AccessToken;
use MathieuDumoutier\OAuth2\Client\Exception\OrcidIdentityProviderException;
use MathieuDumoutier\OAuth2\Client\Provider\OrcidResourceOwner;
use Psr\Http\Message\ResponseInterface;

class Orcid extends AbstractProvider
{
    protected bool $sandbox;

    const OAUTH_BASE_PRODUCTION = 'https://orcid.org';
    const OAUTH_BASE_SANDBOX = 'https://sandbox.orcid.org';
    const API_BASE_PRODUCTION = 'https://api.orcid.org/v3.0';
    const API_BASE_SANDBOX = 'https://api.sandbox.orcid.org/v3.0';

    public function __construct(array $options = [], array $collaborators = [])
    {
        parent::__construct($options, $collaborators);
        $this->sandbox = (bool) $options['SANDBOX_MODE'];
    }

    public function getBaseUrl(): string
    {
        return true === $this->sandbox ? self::OAUTH_BASE_SANDBOX : self::OAUTH_BASE_PRODUCTION;
    }

    public function getBaseAuthorizationUrl()
    {
        return $this->getBaseUrl().'/oauth/authorize';
    }

    public function getBaseAccessTokenUrl(array $params)
    {
        return $this->getBaseUrl().'/oauth/token';
    }

    private function getApiBaseUrl():string
    {
        return true === $this->sandbox ? self::API_BASE_SANDBOX : self::API_BASE_PRODUCTION;
    }

    public function getResourceOwnerDetailsUrl(AccessToken $token)
    {
        return $this->getApiBaseUrl().'/'.$token->getValues()['orcid'].'/record';
    }

    protected function getDefaultScopes()
    {
        return [
            '/authenticate',
        ];
    }

    protected function checkResponse(ResponseInterface $response, $data)
    {
        if (!empty($data['error'])) {
            throw new OrcidIdentityProviderException(
                $data['error'] ?: $response->getReasonPhrase(),
                $response->getStatusCode(),
                $response
            );
        }
    }

    protected function createResourceOwner(array $response, AccessToken $token): ResourceOwnerInterface
    {
        return new OrcidResourceOwner($response);
    }

    protected function getAuthorizationHeaders($token = null)
    {
        return [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/vnd.orcid+json',
        ];
    }
}
