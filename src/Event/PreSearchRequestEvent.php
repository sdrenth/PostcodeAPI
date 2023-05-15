<?php

declare(strict_types=1);

namespace Metapixel\PostcodeAPI\Event;

use Metapixel\PostcodeAPI\Entity\Address;
use Metapixel\PostcodeAPI\Entity\SearchRequest;
use Symfony\Contracts\EventDispatcher\Event;

class PreSearchRequestEvent extends Event
{
    public const NAME = 'pre.search_request';

    public function __construct(
        private SearchRequest $searchRequest,
        private Address $address,
    ) {
    }

    public function getSearchRequest(): SearchRequest
    {
        return $this->searchRequest;
    }

    public function getAddress(): Address
    {
        return $this->address;
    }
}
