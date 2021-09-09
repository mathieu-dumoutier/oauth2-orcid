<?php

declare(strict_types=1);

namespace MathieuDumoutier\OAuth2\Client;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;

class OrcidRessourceOwner implements ResourceOwnerInterface
{
    public function __construct(protected array $response = [])
    {
    }

    public function getOrcId(): string
    {
        return $this->response['id'];
    }

    public function toArray(): array
    {
        return 'toto';
    }
}
