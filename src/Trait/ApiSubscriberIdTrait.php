<?php

declare(strict_types=1);

namespace Metapixel\PostcodeAPI\Trait;

trait ApiSubscriberIdTrait
{
    protected ?string $apiSubscriberId = null;

    public function getApiSubscriberId(): ?string
    {
        return $this->apiSubscriberId;
    }

    public function setApiSubscriberId(?string $apiSubscriberId): self
    {
        $this->apiSubscriberId = $apiSubscriberId;

        return $this;
    }
}
