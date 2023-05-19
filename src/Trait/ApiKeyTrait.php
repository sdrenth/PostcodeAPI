<?php

declare(strict_types=1);

namespace Metapixel\PostcodeAPI\Trait;

trait ApiKeyTrait
{
    protected ?string $apiKey = null;

    public function getApiKey(): ?string
    {
        return $this->apiKey;
    }

    public function setApiKey(?string $apiKey): self
    {
        $this->apiKey = $apiKey;

        return $this;
    }
}
