<?php

declare(strict_types=1);

namespace MathieuDumoutier\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;

class OrcidResourceOwner implements ResourceOwnerInterface
{
    public function __construct(protected array $response = [])
    {
    }

    public function getId(): string
    {
        return $this->response['orcid-identifier']['path'];
    }

    public function toArray(): array
    {
        return $this->response;
    }
}
