<?php

declare(strict_types=1);

namespace Metapixel\PostcodeAPI\Event;

use Metapixel\PostcodeAPI\Entity\Address;
use Metapixel\PostcodeAPI\Entity\SearchRequest;
use Symfony\Contracts\EventDispatcher\Event;

class PostSearchRequestEvent extends Event
{
    public const NAME = 'post.search_request';

    protected ?Address $address = null;

    public function __construct(
        private SearchRequest $searchRequest,
    ) {
    }

    public function getSearchRequest(): SearchRequest
    {
        return $this->searchRequest;
    }

    public function setAddress(Address $address): void
    {
        $this->address = $address;
    }

    public function getAddress(): ?Address
    {
        return $this->address;
    }
}