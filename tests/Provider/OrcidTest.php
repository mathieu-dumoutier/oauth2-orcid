<?php

declare(strict_types=1);

namespace MathieuDumoutier\OAuth2\Client\Provider;

use League\OAuth2\Client\Token\AccessToken;
use PHPUnit\Framework\TestCase;

final class OrcidTest extends TestCase
{
    const APP_ID = '00000000000001';
    const APP_SECRET = 'XXXXXXXXXXXX';
    const REDIRECT_URI = 'https://example.com/orcid_check';

    protected Orcid $provider;

    public function setUp(): void
    {
        $this->provider = new Orcid([
            'clientId' => self::APP_ID,
            'clientSecret' => self::APP_SECRET,
            'redirectUri' => self::REDIRECT_URI,
            'SANDBOX_MODE' => 0
        ]);
    }

    public function testGetBaseUrl(): void
    {
        $url = $this->provider->getBaseUrl();
        $resource = parse_url($url);

        $this->assertEquals('orcid.org', $resource['host']);
    }

    public function testGetBaseAuthorizationUrl(): void
    {
        $url = $this->provider->getBaseAuthorizationUrl();
        $resource = parse_url($url);

        $this->assertEquals('/oauth/authorize', $resource['path']);
    }

    public function testGetBaseAccessTokenUrl(): void
    {
        $url = $this->provider->getBaseAccessTokenUrl([]);
        $resource = parse_url($url);

        $this->assertEquals('/oauth/token', $resource['path']);
    }

    public function testGetResourceOwnerDetailsUrl(): void
    {
        $accessToken = $this->createMock(AccessToken::class);
        $accessToken->method('getValues')
            ->willReturn(['orcid' => '000-000-0003']);

        $url = $this->provider->getResourceOwnerDetailsUrl($accessToken);
        $resource = parse_url($url);

        $this->assertEquals('/v2.1/000-000-0003/record', $resource['path']);
    }
}
