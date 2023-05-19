<?php

declare(strict_types=1);

namespace Metapixel\PostcodeAPI\Tests\unit\Event;

use Metapixel\PostcodeAPI\Entity\Address;
use Metapixel\PostcodeAPI\Entity\SearchRequest;
use Metapixel\PostcodeAPI\Event\PostSearchRequestEvent;
use PHPUnit\Framework\TestCase;

class PostSearchRequestEventTest extends TestCase
{
    public function testGetSearchRequest(): void
    {
        $searchRequest = new SearchRequest();
        $event = new PostSearchRequestEvent($searchRequest);

        $result = $event->getSearchRequest();

        $this->assertSame($searchRequest, $result);
    }

    public function testSetAddress(): void
    {
        $address = new Address();
        $event = new PostSearchRequestEvent(new SearchRequest());

        $event->setAddress($address);

        $result = $event->getAddress();

        $this->assertSame($address, $result);
    }

    public function testGetAddress(): void
    {
        $address = new Address();
        $event = new PostSearchRequestEvent(new SearchRequest());
        $event->setAddress($address);

        $result = $event->getAddress();

        $this->assertSame($address, $result);
    }
}
