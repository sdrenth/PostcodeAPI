<?php

declare(strict_types=1);

namespace Metapixel\PostcodeAPI\Trait;

trait ApiSecretTrait
{
    protected ?string $apiSecret = null;

    public function getApiSecret(): ?string
    {
        return $this->apiSecret;
    }

    public function setApiSecret(?string $apiSecret): self
    {
        $this->apiSecret = $apiSecret;

        return $this;
    }
}
