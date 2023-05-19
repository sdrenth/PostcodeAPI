<?php

declare(strict_types=1);

namespace Metapixel\PostcodeAPI\Tests\unit\Provider\de_DE;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use Metapixel\PostcodeAPI\Entity\Address;
use Metapixel\PostcodeAPI\Entity\Coordinates;
use Metapixel\PostcodeAPI\Entity\SearchRequest;
use Metapixel\PostcodeAPI\Exception\MethodNotSupportedException;
use Metapixel\PostcodeAPI\Factory\ProviderFactory;
use Metapixel\PostcodeAPI\Provider\de_DE\Pro6PP;
use PHPUnit\Framework\TestCase;

class Pro6PPTest extends TestCase
{
    protected Pro6PP $provider;

    public const ZIPCODE = '80935';
    public const HOUSE_NUMBER = '22';

    public const CITY = 'München';

    public const STREET = 'Freudstraße';

    public function setUp(): void
    {
        $this->provider = (new Pro6PP())
            ->setApiKey('MOCK_API_KEY')
        ;
    }

    public function testIfProviderCanBeCreatedByProviderFactory(): void
    {
        $provider = ProviderFactory::create('de_DE.Pro6PP');

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
        self::expectException(MethodNotSupportedException::class);

        $this->provider->findByZipcodeAndHouseNumber(self::ZIPCODE, self::HOUSE_NUMBER);
    }

    public function testItCanGetCorrectValuesForFindBySearchRequest()
    {

        $this->provider->setHttpClient(new Client([
            'handler' => new MockHandler([
                new Response(200, [], '{"country":"Deutschland","countryCode":"DE","district":"Landkreis Tuttlingen","lat":48.2045561,"lng":11.5604941,"postalCode":"80935","premise":"A","settlement":"München","state":"Oberbayern","street":"Freudstraße","streetNumber":22}')
            ])
        ]));

        $address = $this->provider->findBySearchRequest(
            (new SearchRequest())
                ->setZipcode(self::ZIPCODE)
                ->setCity(self::CITY)
                ->setStreet(self::STREET)
                ->setHouseNumber(self::HOUSE_NUMBER)
        );

        self::assertInstanceOf(Address::class, $address);

        $expectedAddress =  (new Address())
            ->setCountry('Deutschland')
            ->setCountryCode('DE')
            ->setZipcode(self::ZIPCODE)
            ->setCity(self::CITY)
            ->setAddition('A')
            ->setStreet(self::STREET)
            ->setHouseNumber(self::HOUSE_NUMBER)
            ->setCoordinates(
                (new Coordinates())
                    ->setLatitude( 48.2045561)
                    ->setLongitude(11.5604941)
            )
        ;

        self::assertEquals($expectedAddress, $address);
    }
}