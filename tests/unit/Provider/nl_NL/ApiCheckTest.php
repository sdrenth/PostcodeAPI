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
use Metapixel\PostcodeAPI\Provider\nl_NL\ApiCheck;
use PHPUnit\Framework\TestCase;

class ApiCheckTest extends TestCase
{
    protected ApiCheck $provider;

    public const ZIPCODE = '7315JA';
    public const HOUSE_NUMBER = '1';

    public function setUp(): void
    {
        $this->provider = (new ApiCheck())
            ->setApiKey('MOCK_API_KEY')
        ;
    }

    public function testIfProviderCanBeCreatedByProviderFactory(): void
    {
        $provider = ProviderFactory::create('nl_NL.ApiCheck');

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
                new Response(200, [], '{"error":false,"data":{"street":"Koninklijk Park","number":"1","numberAddition":null,"postalcode":"7315JA","city":"Apeldoorn","municipality":"Apeldoorn","Location":{"Coordinates":{"latitude":"52.234103248484","longitude":"5.9460115775604"}},"Country":{"name":"Nederland","code":"NL","nameInt":"The Netherlands"}}}')
            ])
        ]));

        $address = $this->provider->findByZipcodeAndHouseNumber(self::ZIPCODE, self::HOUSE_NUMBER);

        self::assertInstanceOf(Address::class, $address);

        $expectedAddress =  (new Address())
            ->setCountry('Nederland')
            ->setCountryCode('NL')
            ->setZipcode(self::ZIPCODE)
            ->setMunicipality('Apeldoorn')
            ->setCity('Apeldoorn')
            ->setStreet('Koninklijk Park')
            ->setHouseNumber(self::HOUSE_NUMBER)
            ->setCoordinates(
                (new Coordinates())
                    ->setLatitude( 52.234103248484)
                    ->setLongitude(5.9460115775604)
            )
        ;

        self::assertEquals($expectedAddress, $address);
    }
}