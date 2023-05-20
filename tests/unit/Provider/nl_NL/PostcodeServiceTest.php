<?php

declare(strict_types=1);

namespace Metapixel\PostcodeAPI\Tests\unit\Provider\nl_NL;

use Metapixel\PostcodeAPI\Entity\Coordinates;
use Metapixel\PostcodeAPI\Exception\MethodNotSupportedException;
use Metapixel\PostcodeAPI\Provider\nl_NL\PostcodeService;
use PHPUnit\Framework\TestCase;
use Metapixel\PostcodeAPI\Factory\ProviderFactory;
use Metapixel\PostcodeAPI\Entity\Address;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;

class PostcodeServiceTest extends TestCase
{
    protected PostcodeService $provider;

    public const ZIPCODE = '6545CA';
    public const HOUSE_NUMBER = '29';

    public function setUp(): void
    {
        $this->provider = (new PostcodeService())
            ->setApiUsername('MOCK_USERNAME')
            ->setApiPassword('MOCK_PASSWORD')
            ->setApiDomain('MOCK_DOMAIN');
    }

    public function testIfProviderCanBeCreatedByProviderFactory(): void
    {
        $provider = ProviderFactory::create('nl_NL.PostcodeService');

        self::assertInstanceof(PostcodeService::class, $provider);
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
                new Response(200, [], '{"street":"Binderskampweg","city":"Nijmegen","region":"Gelderland","residential":"no","latitude":51.845143276491,"longitude":5.8033391546493}')
            ])
        ]));

        $address = $this->provider->findByZipcodeAndHouseNumber(self::ZIPCODE, self::HOUSE_NUMBER);

        self::assertInstanceOf(Address::class, $address);

        $expectedAddress =  (new Address())
            ->setZipcode(self::ZIPCODE)
            ->setProvince('Gelderland')
            ->setCity('Nijmegen')
            ->setStreet('Binderskampweg')
            ->setHouseNumber(self::HOUSE_NUMBER)
            ->setCoordinates(
                (new Coordinates())
                    ->setLatitude(51.845143276491)
                    ->setLongitude(5.8033391546493)
            )
        ;

        self::assertEquals($expectedAddress, $address);
    }
}
