<?php

declare(strict_types=1);

namespace Metapixel\PostcodeAPI\Tests\unit\Provider\fr_FR;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use Metapixel\PostcodeAPI\Entity\Address;
use Metapixel\PostcodeAPI\Entity\Coordinates;
use Metapixel\PostcodeAPI\Entity\SearchRequest;
use Metapixel\PostcodeAPI\Exception\MethodNotSupportedException;
use Metapixel\PostcodeAPI\Factory\ProviderFactory;
use Metapixel\PostcodeAPI\Provider\fr_FR\Pro6PP;
use PHPUnit\Framework\TestCase;

class Pro6PPTest extends TestCase
{
    protected Pro6PP $provider;

    public const ZIPCODE = '68110';

    public const CITY = 'Illzach';

    public const HOUSE_NUMBER = '0';

    public const STREET = 'Rue de la Doller';

    public function setUp(): void
    {
        $this->provider = (new Pro6PP())
            ->setApiKey('MOCK_API_KEY')
        ;
    }

    public function testIfProviderCanBeCreatedByProviderFactory(): void
    {
        $provider = ProviderFactory::create('fr_FR.Pro6PP');

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
                new Response(200, [], '{"commune":"Illzach","country":"France","countryCode":"FR","department":"Haut-Rhin","lat":47.77337,"lng":7.34162,"postalCode":"68110","region":"Grand Est","settlement":"Illzach","street":"Rue de la Doller","streetNumber":0}')
            ])
        ]));

        $address = $this->provider->findBySearchRequest(
            (new SearchRequest())
                ->setZipcode(self::ZIPCODE)
                ->setStreet(self::STREET)
                ->setHouseNumber(self::HOUSE_NUMBER)
        );

        self::assertInstanceOf(Address::class, $address);

        $expectedAddress =  (new Address())
            ->setCountry('France')
            ->setCountryCode('FR')
            ->setZipcode(self::ZIPCODE)
            ->setCity(self::CITY)
            ->setStreet(self::STREET)
            ->setHouseNumber(self::HOUSE_NUMBER)
            ->setCoordinates(
                (new Coordinates())
                    ->setLatitude( 47.77337)
                    ->setLongitude(7.34162)
            )
        ;

        self::assertEquals($expectedAddress, $address);
    }
}