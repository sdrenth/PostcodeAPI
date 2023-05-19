<?php

declare(strict_types=1);

namespace Metapixel\PostcodeAPI\Tests\unit\Provider\de_LU;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use Metapixel\PostcodeAPI\Entity\Address;
use Metapixel\PostcodeAPI\Entity\Coordinates;
use Metapixel\PostcodeAPI\Entity\SearchRequest;
use Metapixel\PostcodeAPI\Exception\MethodNotSupportedException;
use Metapixel\PostcodeAPI\Factory\ProviderFactory;
use Metapixel\PostcodeAPI\Provider\de_LU\PostNL;
use PHPUnit\Framework\TestCase;

class PostNLTest extends TestCase
{
    protected PostNL $provider;

    public const ZIPCODE = '2600';
    public const HOUSE_NUMBER = '3';

    public function setUp(): void
    {
        $this->provider = (new PostNL())
            ->setApiKey('MOCK_API_KEY')
        ;
    }

    public function testIfProviderCanBeCreatedByProviderFactory(): void
    {
        $provider = ProviderFactory::create('de_LU.PostNL');

        self::assertInstanceof(PostNL::class, $provider);
    }

    public function testItCanGetCorrectValuesForFind(): void
    {
        self::expectException(MethodNotSupportedException::class);

        $this->provider->find(self::ZIPCODE);
    }

    public function testItCanGetCorrectValuesForFindByZipcode(): void
    {
        self::expectException(MethodNotSupportedException::class);

        $this->provider->findByZipcode(self::ZIPCODE);
    }

    public function testItCanGetCorrectValuesForFindByZipcodeAndHouseNumber(): void
    {
        $this->provider->setHttpClient(new Client([
            'handler' => new MockHandler([
                new Response(200, [], '[{"buildingName":null,"bus":"1","cityName":"Antwerpen","companyName":null,"countryIso2":"BE","countryIso3":"BEL","countryName":"Belgium","door":null,"flat":null,"floor":null,"formattedAddress":["Bus 1","Grotesteenweg 3","2600 Antwerpen","Belgium"],"houseNumber":3,"houseNumberAddition":null,"latitude":51.19872787864764,"localityName":"Berchem","longitude":4.415131884851253,"mailabilityScore":100,"postalCode":"2600","resultNumber":1,"resultPercentage":92,"stairs":null,"stateName":"Antwerpen","streetName":"Grotesteenweg"}]')
            ])
        ]));

        $address = $this->provider->findByZipcodeAndHouseNumber(self::ZIPCODE, self::HOUSE_NUMBER);

        self::assertInstanceOf(Address::class, $address);

        $expectedAddress =  (new Address())
            ->setCountry('Belgium')
            ->setCountryCode('BE')
            ->setZipcode(self::ZIPCODE)
            ->setProvince('Antwerpen')
            ->setCity('Antwerpen')
            ->setStreet('Grotesteenweg')
            ->setHouseNumber(self::HOUSE_NUMBER)
            ->setCoordinates(
                (new Coordinates())
                    ->setLatitude( 51.19872787864764)
                    ->setLongitude(4.415131884851253)
            )
        ;

        self::assertEquals($expectedAddress, $address);
    }

    public function testRequestContainsCorrectIsoCode(): void
    {
        $this->provider->setHttpClient(new Client([
            'handler' => new MockHandler([
                new Response(200, [], '[{"buildingName":null,"bus":"1","cityName":"Antwerpen","companyName":null,"countryIso2":"BE","countryIso3":"BEL","countryName":"Belgium","door":null,"flat":null,"floor":null,"formattedAddress":["Bus 1","Grotesteenweg 3","2600 Antwerpen","Belgium"],"houseNumber":3,"houseNumberAddition":null,"latitude":51.19872787864764,"localityName":"Berchem","longitude":4.415131884851253,"mailabilityScore":100,"postalCode":"2600","resultNumber":1,"resultPercentage":92,"stairs":null,"stateName":"Antwerpen","streetName":"Grotesteenweg"}]')
            ])
        ]));

        $this->provider->findBySearchRequest((new SearchRequest()));

        $parsedUrl = parse_url($this->provider->getRequestUrl());

        self::assertArrayHasKey('query', $parsedUrl);

        parse_str($parsedUrl['query'], $urlParams);

        self::assertArrayHasKey('countryIso', $urlParams);
        self::assertEquals(PostNL::ISO_CODE, $urlParams['countryIso']);
    }
}