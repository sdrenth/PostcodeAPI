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
use Metapixel\PostcodeAPI\Provider\nl_NL\Spikkl;
use PHPUnit\Framework\TestCase;

class SpikklTest extends TestCase
{
    protected Spikkl $provider;

    public const ZIPCODE = '2611KL';
    public const HOUSE_NUMBER = '15';

    public function setUp(): void
    {
        $this->provider = (new Spikkl())
            ->setApiKey('MOCK_API_KEY')
        ;
    }

    public function testIfProviderCanBeCreatedByProviderFactory(): void
    {
        $provider = ProviderFactory::create('nl_NL.Spikkl');

        self::assertInstanceof(Spikkl::class, $provider);
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
                new Response(200, [], '{"results":[{"location_id":"5e405237ec42128617dfb57f","postal_code":"2611KL","street_number":15,"street_number_suffix":null,"street_name":"Trompetstraat","city":"Delft","municipality":"Delft","administrative_areas":[{"type":"province","name":"Zuid-Holland","abbreviation":"ZH"}],"country":{"iso3_code":"NLD","iso2_code":"NL","name":"Nederland"},"centroid":{"latitude":52.01293,"longitude":4.362023},"formatted_address":"Trompetstraat 15, 2611KL Delft, Nederland","match":"exact"}],"status":"ok","meta":{"timestamp":1569305003595,"trace_id":"c8e52fa510d4b47d8f16e682"}}')
            ])
        ]));

        $address = $this->provider->findByZipcodeAndHouseNumber(self::ZIPCODE, self::HOUSE_NUMBER);

        self::assertInstanceOf(Address::class, $address);

        $expectedAddress =  (new Address())
            ->setCountry('Nederland')
            ->setCountryCode('NL')
            ->setZipcode(self::ZIPCODE)
            ->setProvince('Zuid-Holland')
            ->setMunicipality('Delft')
            ->setCity('Delft')
            ->setStreet('Trompetstraat')
            ->setHouseNumber(self::HOUSE_NUMBER)
            ->setCoordinates(
                (new Coordinates())
                    ->setLatitude( 52.01293)
                    ->setLongitude(4.362023)
            )
        ;

        self::assertEquals($expectedAddress, $address);
    }
}