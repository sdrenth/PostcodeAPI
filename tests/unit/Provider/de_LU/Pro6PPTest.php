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
use Metapixel\PostcodeAPI\Provider\de_LU\Pro6PP;
use PHPUnit\Framework\TestCase;

class Pro6PPTest extends TestCase
{
    protected Pro6PP $provider;

    public const ZIPCODE = '5499';
    public const HOUSE_NUMBER = '5';

    public const STREET = 'Am Gaa';

    public function setUp(): void
    {
        $this->provider = (new Pro6PP())
            ->setApiKey('MOCK_API_KEY')
        ;
    }

    public function testIfProviderCanBeCreatedByProviderFactory(): void
    {
        $provider = ProviderFactory::create('de_LU.Pro6PP');

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
                new Response(200, [], '{"country":"Luxembourg","countryCode":"LU","lat":49.61997222490228,"lng":6.39620909139201,"postalCode":"5499","settlement":"Dreiborn","street":"Am Gaa","streetNumber":5}')
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
            ->setCountry('Luxembourg')
            ->setCountryCode('LU')
            ->setZipcode(self::ZIPCODE)
            ->setCity('Dreiborn')
            ->setStreet(self::STREET)
            ->setHouseNumber(self::HOUSE_NUMBER)
            ->setCoordinates(
                (new Coordinates())
                    ->setLatitude( 49.61997222490228)
                    ->setLongitude(6.39620909139201)
            )
        ;

        self::assertEquals($expectedAddress, $address);
    }
}