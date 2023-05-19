<?php

declare(strict_types=1);

namespace Metapixel\PostcodeAPI\Tests\unit\Event;

use Metapixel\PostcodeAPI\Entity\Address;
use Metapixel\PostcodeAPI\Entity\SearchRequest;
use Metapixel\PostcodeAPI\Event\PreSearchRequestEvent;
use PHPUnit\Framework\TestCase;

class PreSearchRequestEventTest extends TestCase
{
    public function testGetSearchRequest(): void
    {
        $searchRequest = new SearchRequest();
        $address = new Address();
        $event = new PreSearchRequestEvent($searchRequest, $address);

        $result = $event->getSearchRequest();

        $this->assertSame($searchRequest, $result);
    }

    public function testGetAddress(): void
    {
        $searchRequest = new SearchRequest();
        $address = new Address();
        $event = new PreSearchRequestEvent($searchRequest, $address);

        $result = $event->getAddress();

        $this->assertSame($address, $result);
    }
}
