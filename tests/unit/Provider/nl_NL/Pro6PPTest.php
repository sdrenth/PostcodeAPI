<?php

declare(strict_types=1);

namespace Metapixel\PostcodeAPI\Tests\unit\Provider\nl_NL;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use Metapixel\PostcodeAPI\Entity\Address;
use Metapixel\PostcodeAPI\Entity\Coordinates;
use Metapixel\PostcodeAPI\Exception\MethodNotSupportedException;
use Metapixel\PostcodeAPI\Factory\ProviderFactory;
use Metapixel\PostcodeAPI\Provider\nl_NL\Pro6PP;
use PHPUnit\Framework\TestCase;

class Pro6PPTest extends TestCase
{
    protected Pro6PP $provider;

    public const ZIPCODE = '1068NM';
    public const HOUSE_NUMBER = '461';

    public function setUp(): void
    {
        $this->provider = (new Pro6PP())
            ->setApiKey('MOCK_API_KEY')
        ;
    }

    public function testIfProviderCanBeCreatedByProviderFactory(): void
    {
        $provider = ProviderFactory::create('nl_NL.Pro6PP');

        self::assertInstanceof(Pro6PP::class, $provider);
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
                new Response(200, [], '{"country":"Nederland","countryCode":"NL","postalCode":"1068NM","settlement":"Amsterdam","street":"Pieter Calandlaan","streetNumber":461,"premise":"A","surfaceArea":117,"purposes":["woonfunctie"],"constructionYear":1969,"lat":52.3534158,"lng":4.805014,"neighbourhood":"Oosterdokseiland","district":"Nieuwmarkt/Lastage","BAGNumberId":"0363200000411387 ","BAGPublicSpaceId":"036330000000437 ","BAGPremiseId":"036310001209252 ","BAGResidenceId":"036301000077479 ","BAGType":"vbo"}')
            ])
        ]));

        $address = $this->provider->findByZipcodeAndHouseNumber(self::ZIPCODE, self::HOUSE_NUMBER);

        self::assertInstanceOf(Address::class, $address);

        $expectedAddress =  (new Address())
            ->setCountry('Nederland')
            ->setCountryCode('NL')
            ->setZipcode(self::ZIPCODE)
            ->setCity('Amsterdam')
            ->setAddition('A')
            ->setStreet('Pieter Calandlaan')
            ->setHouseNumber(self::HOUSE_NUMBER)
            ->setCoordinates(
                (new Coordinates())
                    ->setLatitude( 52.3534158)
                    ->setLongitude(4.805014)
            )
        ;

        self::assertEquals($expectedAddress, $address);
    }
}