<?php

declare(strict_types=1);

namespace Metapixel\PostcodeAPI\Trait;

trait ApiDomainTrait
{
    protected ?string $apiDomain = null;

    public function getApiDomain(): ?string
    {
        return $this->apiDomain;
    }

    public function setApiDomain(?string $apiDomain): self
    {
        $this->apiDomain = $apiDomain;

        return $this;
    }
}
