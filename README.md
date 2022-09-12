# OAuth2 ORCID Client

This package provides ORCID OAuth 2.0 support for the PHP League's [OAuth 2.0 Client](https://github.com/thephpleague/oauth2-client).

## Installation

To install, use composer:

```
composer require mathieu-dumoutier/oauth2-orcid
```

## Usage

Usage is the same as The League's OAuth client, using `\MathieuDumoutier\OAuth2\Client\Provider\Orcid` as the provider.

## knpuniversity/oauth2-client-bundle configuration example

```yaml
knpu_oauth2_client:
    clients:
        orcid_oauth:
            type: generic
            provider_class: MathieuDumoutier\OAuth2\Client\Provider\Orcid
            provider_options:
                "scopes": '%env(ORCID_OAUTH_SCOPES)%'
                "use_member_api": '%env(ORCID_OAUTH_SANDBOX_MODE)%'
                "sandbox_mode": '%env(ORCID_OAUTH_MEMBER_API)%'
                "api_version": '%env(ORCID_OAUTH_API_VERSION)%'        
            client_id: '%env(ORCID_APP_ID)%'
            client_secret: '%env(ORCID_APP_SECRET)%'
            redirect_route: orcid_check
            redirect_params: {}
            use_state: false
```

You must define the 6 environment variables :
* ORCID_APP_ID 
* ORCID_APP_SECRET
* ORCID_OAUTH_SCOPES (see https://info.orcid.org/documentation/integration-guide/getting-started-with-your-orcid-integration/#easy-faq-2569)
* ORCID_OAUTH_SANDBOX_MODE (0 or 1)
* ORCID_OAUTH_MEMBER_API (0 or 1)
* ORCID_OAUTH_API_VERSION (v2.0, v2.1 or v3.0)

You must create the route "orcid_check".

## Testing

``` bash
$ ./vendor/bin/phpunit
```
