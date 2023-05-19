<?php

declare(strict_types=1);

namespace Metapixel\PostcodeAPI\Tests\unit\Provider\de_LU;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use Metapixel\PostcodeAPI\Entity\Address;
use Metapixel\PostcodeAPI\Entity\Coordinates;
use Metapixel\PostcodeAPI\Exception\MethodNotSupportedException;
use Metapixel\PostcodeAPI\Factory\ProviderFactory;
use Metapixel\PostcodeAPI\Provider\de_LU\ApiCheck;
use PHPUnit\Framework\TestCase;

class ApiCheckTest extends TestCase
{
    protected ApiCheck $provider;

    public const ZIPCODE = '9209';
    public const HOUSE_NUMBER = '10';

    public function setUp(): void
    {
        $this->provider = (new ApiCheck())
            ->setApiKey('MOCK_API_KEY')
        ;
    }

    public function testIfProviderCanBeCreatedByProviderFactory(): void
    {
        $provider = ProviderFactory::create('de_LU.ApiCheck');

        self::assertInstanceof(ApiCheck::class, $provider);
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
                new Response(200, [], '{"error":false,"data":{"street":"D\'Baach aus","number":"10","numberAddition":"","postalcode":"9209","city":"Diekirch","municipality":"Diekirch","formattedAddress":"10 D\'Baach aus 9209 Diekirch, Luxembourg","Location":{"Coordinates":{"latitude":"49.871738231366","longitude":"6.1593103489451"}},"Country":{"name":"Luxemburg","code":"LU","nameInt":"Luxembourg"}}}')
            ])
        ]));

        $address = $this->provider->findByZipcodeAndHouseNumber(self::ZIPCODE, self::HOUSE_NUMBER);

        self::assertInstanceOf(Address::class, $address);

        $expectedAddress =  (new Address())
            ->setCountry('Luxemburg')
            ->setCountryCode('LU')
            ->setZipcode(self::ZIPCODE)
            ->setMunicipality('Diekirch')
            ->setCity('Diekirch')
            ->setStreet('D\'Baach aus')
            ->setHouseNumber(self::HOUSE_NUMBER)
            ->setCoordinates(
                (new Coordinates())
                    ->setLatitude( 49.871738231366)
                    ->setLongitude(6.1593103489451)
            )
        ;

        self::assertEquals($expectedAddress, $address);
    }
}