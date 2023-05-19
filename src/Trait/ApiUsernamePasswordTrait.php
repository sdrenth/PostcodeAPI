<?php

declare(strict_types=1);

namespace Metapixel\PostcodeAPI\Trait;

trait ApiUsernamePasswordTrait
{
    protected ?string $apiUsername = null;

    protected ?string $apiPassword = null;

    public function getApiUsername(): ?string
    {
        return $this->apiUsername;
    }

    public function setApiUsername(?string $apiUsername): self
    {
        $this->apiUsername = $apiUsername;

        return $this;
    }

    public function getApiPassword(): ?string
    {
        return $this->apiPassword;
    }

    public function setApiPassword(?string $apiPassword): self
    {
        $this->apiPassword = $apiPassword;

        return $this;
    }
}
