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

```json
knpu_oauth2_client:
    clients:
        orcid_oauth:
            type: generic
            provider_class: MathieuDumoutier\OAuth2\Client\Provider\Orcid
            provider_options:
                "SANDBOX_MODE": '%env(ORCID_SANDBOX_MODE)%'
            client_id: '%env(ORCID_APP_ID)%'
            client_secret: '%env(ORCID_APP_SECRET)%'
            redirect_route: orcid_check
            redirect_params: {}
            use_state: false
```

You must define the 3 environment variables :
* ORCID_SANDBOX_MODE
* ORCID_APP_ID
* ORCID_APP_SECRET

You must create the route "orcid_check".

## Testing

``` bash
$ ./vendor/bin/phpunit
```
