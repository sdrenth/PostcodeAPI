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
use Metapixel\PostcodeAPI\Provider\nl_NL\PostcodeNL;
use PHPUnit\Framework\TestCase;

class PostcodeNLTest extends TestCase
{
    protected PostcodeNL $provider;

    public const ZIPCODE = '2012ES';
    public const HOUSE_NUMBER = '30';

    public function setUp(): void
    {
        $this->provider = (new PostcodeNL())
            ->setApiKey('MOCK_API_KEY')
            ->setApiSecret('MOCK_API_SECRET')
        ;
    }

    public function testIfProviderCanBeCreatedByProviderFactory(): void
    {
        $provider = ProviderFactory::create('nl_NL.PostcodeNL');

        self::assertInstanceof(PostcodeNL::class, $provider);
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
                new Response(200, [], '{"street":"Julianastraat","streetNen":"Julianastraat","houseNumber":30,"houseNumberAddition":"","postcode":"2012ES","city":"Haarlem","cityShort":"Haarlem","cityId":"2907","municipality":"Haarlem","municipalityShort":"Haarlem","municipalityId":"0392","province":"Noord-Holland","rdX":103242,"rdY":487716,"latitude":52.37487801,"longitude":4.62714526,"bagNumberDesignationId":"0392200000029398","bagAddressableObjectId":"0392010000029398","addressType":"building","purposes":["office"],"surfaceArea":643,"houseNumberAdditions":[""]}')
            ])
        ]));

        $address = $this->provider->findByZipcodeAndHouseNumber(self::ZIPCODE, self::HOUSE_NUMBER);

        self::assertInstanceOf(Address::class, $address);

        $expectedAddress =  (new Address())
            ->setZipcode(self::ZIPCODE)
            ->setMunicipality('Haarlem')
            ->setProvince('Noord-Holland')
            ->setCity('Haarlem')
            ->setAddition('')
            ->setStreet('Julianastraat')
            ->setHouseNumber(self::HOUSE_NUMBER)
            ->setCoordinates(
                (new Coordinates())
                    ->setLatitude( 52.37487801)
                    ->setLongitude(4.62714526)
            )
        ;

        self::assertEquals($expectedAddress, $address);
    }
}