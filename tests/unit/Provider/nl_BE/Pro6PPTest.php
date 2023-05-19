<?php

declare(strict_types=1);

namespace Metapixel\PostcodeAPI\Tests\unit\Provider\nl_BE;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use Metapixel\PostcodeAPI\Entity\Address;
use Metapixel\PostcodeAPI\Entity\Coordinates;
use Metapixel\PostcodeAPI\Entity\SearchRequest;
use Metapixel\PostcodeAPI\Exception\MethodNotSupportedException;
use Metapixel\PostcodeAPI\Factory\ProviderFactory;
use Metapixel\PostcodeAPI\Provider\nl_BE\Pro6PP;
use PHPUnit\Framework\TestCase;

class Pro6PPTest extends TestCase
{
    protected Pro6PP $provider;

    public const ZIPCODE = '9000';
    public const HOUSE_NUMBER = '5';

    public const STREET = 'Lavendelstraat';

    public function setUp(): void
    {
        $this->provider = (new Pro6PP())
            ->setApiKey('MOCK_API_KEY')
        ;
    }

    public function testIfProviderCanBeCreatedByProviderFactory(): void
    {
        $provider = ProviderFactory::create('nl_BE.Pro6PP');

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
                new Response(200, [], '{"country":"Belgium","countryCode":"BE","countryTranslations":[{"lang":"en","name":"Belgium"},{"lang":"nl","name":"België"},{"lang":"fr","name":"Belgique"},{"lang":"de","name":"Belgien"}],"lat":51.0699725870489,"lng":3.70971706948922,"postalCode":"9000","premise":"A","province":"Oost-Vlaanderen","provinceTranslations":[{"lang":"nl","name":"Oost-Vlaanderen"},{"lang":"fr","name":"Flandre orientale"},{"lang":"de","name":"Ostflandern"}],"region":"Vlaanderen","regionTranslations":[{"lang":"nl","name":"Vlaanderen"},{"lang":"fr","name":"Flandre"},{"lang":"de","name":"Flandern"}],"settlement":"Ghent","settlementTranslations":[{"lang":"nl","name":"Gent"},{"lang":"fr","name":"Gand"},{"lang":"de","name":"Gent"}],"street":"Lavendelstraat","streetNumber":5}')
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
            ->setCountry('Belgium')
            ->setCountryCode('BE')
            ->setZipcode(self::ZIPCODE)
            ->setProvince('Oost-Vlaanderen')
            ->setCity('Ghent')
            ->setStreet(self::STREET)
            ->setHouseNumber(self::HOUSE_NUMBER)
            ->setAddition('A')
            ->setCoordinates(
                (new Coordinates())
                    ->setLatitude( 51.0699725870489)
                    ->setLongitude(3.70971706948922)
            )
        ;

        self::assertEquals($expectedAddress, $address);
    }
}