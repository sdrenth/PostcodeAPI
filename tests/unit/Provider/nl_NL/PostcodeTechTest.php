<?php

declare(strict_types=1);

namespace Metapixel\PostcodeAPI\Tests\unit\Provider\nl_NL;

use Metapixel\PostcodeAPI\Entity\Coordinates;
use Metapixel\PostcodeAPI\Exception\MethodNotSupportedException;
use Metapixel\PostcodeAPI\Provider\nl_NL\PostcodeTech;
use PHPUnit\Framework\TestCase;
use Metapixel\PostcodeAPI\Factory\ProviderFactory;
use Metapixel\PostcodeAPI\Entity\Address;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;

class PostcodeTechTest extends TestCase
{
    protected PostcodeTech $provider;

    public const ZIPCODE = '6545CA';
    public const HOUSE_NUMBER = '29';

    public function setUp(): void
    {
        $this->provider = (new PostcodeTech())
            ->setApiKey('MOCK_API_USERNAME');
    }

    public function testIfProviderCanBeCreatedByProviderFactory(): void
    {
        $provider = ProviderFactory::create('nl_NL.PostcodeTech');

        self::assertInstanceof(PostcodeTech::class, $provider);
    }

    public function testItCanGetCorrectValuesForFind(): void
    {
        self::expectException(MethodNotSupportedException::class);

        $this->provider->find(self::ZIPCODE);
    }

    public function testItCanGetCorrectValuesForFindByZipcodeAndHouseNumber(): void
    {
        $this->provider->setHttpClient(new Client([
            'handler' => new MockHandler([
                new Response(200, [], '{"postcode":"6545CA","number":29,"street":"Binderskampweg","city":"Nijmegen","municipality":"Nijmegen","province":"Gelderland","geo":{"lat":51.845410073677,"lon":5.8038868078501}}')
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
                    ->setLatitude(51.845410073677)
                    ->setLongitude(5.8038868078501)
            )
        ;

        self::assertEquals($expectedAddress, $address);
    }
}
