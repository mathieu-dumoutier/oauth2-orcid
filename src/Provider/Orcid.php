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
    public string $scopes;
    public string $baseUrl;
    public string $apiBaseUrl;

    public const OAUTH_BASE_SANDBOX = 'https://sandbox.orcid.org';
    public const API_PUBLIC_BASE_URL_SANDBOX = 'https://api.sandbox.orcid.org/';
    public const API_MEMBER_BASE_URL_SANDBOX = 'https://api.sandbox.orcid.org/';
    public const OAUTH_BASE_PRODUCTION = 'https://orcid.org';
    public const API_PUBLIC_BASE_URL_PRODUCTION = 'https://pub.orcid.org/';
    public const API_MEMBER_BASE_URL_PRODUCTION = 'https://api.orcid.org/';

    public function __construct(array $options = [], array $collaborators = [])
    {
        parent::__construct($options, $collaborators);
        $this->scopes = $options['scopes'];

        if (true === (bool) $options['sandbox_mode']) {
            $this->baseUrl = self::OAUTH_BASE_SANDBOX;
            $this->apiBaseUrl = self::API_PUBLIC_BASE_URL_SANDBOX.$options['api_version'];
            if (true === (bool) $options['use_member_api']) {
                $this->apiBaseUrl = self::API_MEMBER_BASE_URL_SANDBOX.$options['api_version'];
            }
        } else {
            $this->baseUrl = self::OAUTH_BASE_PRODUCTION;
            $this->apiBaseUrl = self::API_PUBLIC_BASE_URL_PRODUCTION.$options['api_version'];
            if (true === (bool) $options['use_member_api']) {
                $this->apiBaseUrl = self::API_MEMBER_BASE_URL_PRODUCTION.$options['api_version'];

            }
        }
    }

    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    public function getBaseAuthorizationUrl(): string
    {
        return $this->getBaseUrl().'/oauth/authorize';
    }

    public function getBaseAccessTokenUrl(array $params): string
    {
        return $this->getBaseUrl().'/oauth/token';
    }

    private function getApiBaseUrl():string
    {
        return $this->apiBaseUrl;
    }

    public function getResourceOwnerDetailsUrl(AccessToken $token): string
    {
        return $this->getApiBaseUrl().'/'.$token->getValues()['orcid'].'/record';
    }

    protected function getDefaultScopes(): array
    {
        return [
            $this->scopes,
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

    protected function getAuthorizationHeaders($token = null): array
    {
        return [
            'Authorization' => "Bearer $token",
            'Accept' => 'application/vnd.orcid+json',
        ];
    }
}
