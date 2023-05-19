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
use Metapixel\PostcodeAPI\Provider\nl_NL\PostcodeAPINu;
use PHPUnit\Framework\TestCase;

class PostcodeAPINuTest extends TestCase
{
    protected PostcodeAPINu $provider;

    public const ZIPCODE = '6545CA';
    public const HOUSE_NUMBER = '29';

    public function setUp(): void
    {
        $this->provider = (new PostcodeAPINu())
            ->setApiKey('MOCK_API_KEY')
        ;
    }

    public function testIfProviderCanBeCreatedByProviderFactory(): void
    {
        $provider = ProviderFactory::create('nl_NL.PostcodeAPINu');

        self::assertInstanceof(PostcodeAPINu::class, $provider);
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
                new Response(200, [], '{"postcode":"6545CA","number":29,"street":"Binderskampweg","city":"Nijmegen","municipality":"Nijmegen","province":"Gelderland","location":{"type":"Point","coordinates":[5.858910083770752,51.84376540294041]}}')
            ])
        ]));

        $address = $this->provider->findByZipcodeAndHouseNumber(self::ZIPCODE, self::HOUSE_NUMBER);

        self::assertInstanceOf(Address::class, $address);

        $expectedAddress =  (new Address())
            ->setZipcode(self::ZIPCODE)
            ->setMunicipality('Nijmegen')
            ->setProvince('Gelderland')
            ->setCity('Nijmegen')
            ->setStreet('Binderskampweg')
            ->setHouseNumber(self::HOUSE_NUMBER)
            ->setCoordinates(
                (new Coordinates())
                    ->setLatitude( 51.84376540294041)
                    ->setLongitude(5.858910083770752)
            )
        ;

        self::assertEquals($expectedAddress, $address);
    }
}