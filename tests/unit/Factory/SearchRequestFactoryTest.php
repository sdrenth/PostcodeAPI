<?php

declare(strict_types=1);

namespace Metapixel\PostcodeAPI\Tests\unit\Factory;

use Metapixel\PostcodeAPI\Entity\SearchRequest;
use Metapixel\PostcodeAPI\Factory\SearchRequestFactory;
use PHPUnit\Framework\TestCase;

class SearchRequestFactoryTest extends TestCase
{
    public function testCreate(): void
    {
        $searchRequest = SearchRequestFactory::create();

        $this->assertInstanceOf(SearchRequest::class, $searchRequest);
    }

    public function testCreateByZipcode(): void
    {
        $zipcode = '12345';

        $searchRequest = SearchRequestFactory::createByZipcode($zipcode);

        $this->assertInstanceOf(SearchRequest::class, $searchRequest);
        $this->assertSame($zipcode, $searchRequest->getZipcode());
    }

    public function testCreateByZipcodeAndHouseNumber(): void
    {
        $zipcode = '12345';
        $houseNumber = '10';

        $searchRequest = SearchRequestFactory::createByZipcodeAndHouseNumber($zipcode, $houseNumber);

        $this->assertInstanceOf(SearchRequest::class, $searchRequest);
        $this->assertSame($zipcode, $searchRequest->getZipcode());
        $this->assertSame($houseNumber, $searchRequest->getHouseNumber());
    }
}
