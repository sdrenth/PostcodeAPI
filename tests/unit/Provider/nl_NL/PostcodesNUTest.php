<?php

declare(strict_types=1);

namespace Metapixel\PostcodeAPI\Tests\unit\Provider\nl_NL;

use PHPUnit\Framework\TestCase;
use Metapixel\PostcodeAPI\Factory\ProviderFactory;
use Metapixel\PostcodeAPI\Provider\nl_NL\PostcodesNU;
use Metapixel\PostcodeAPI\Entity\Address;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;

class PostcodesNUTest extends TestCase
{
    protected PostcodesNU $provider;

    public const ZIPCODE = '9999AA';
    public const HOUSE_NUMBER = '998';

    public function setUp(): void
    {
        $this->provider = (new PostcodesNU())
            ->setApiUsername('MOCK_API_USERNAME')
            ->setApiPassword('MOCK_API_PASSWORD')
            ->setApiSubscriberId('MOCK_API_SUBSCRIBER_ID')
            ->setBearerToken('MOCK_BEARER_TOKEN');
    }
    
    public function testIfProviderCanBeCreatedByProviderFactory(): void
    {
        $provider = ProviderFactory::create('nl_NL.PostcodesNU');

        self::assertInstanceof(PostcodesNU::class, $provider);
    }

    public function testItCanGetCorrectValuesForFind(): void
    {
        $this->provider->setHttpClient(new Client([
            'handler' => new MockHandler([
                new Response(200, [], '{"exists":true,"valid_format":true}')
            ])
        ]));

        $address = $this->provider->find(self::ZIPCODE);

        self::assertInstanceOf(Address::class, $address);

        $expectedAddress =  (new Address())
            ->setZipcode(self::ZIPCODE);

        self::assertEquals($expectedAddress, $address);
    }

    public function testItCanGetCorrectValuesForFindByZipcode(): void
    {
        $this->provider->setHttpClient(new Client([
            'handler' => new MockHandler([
                new Response(200, [], '{"exists":true,"valid_format":true}')
            ])
        ]));

        $address = $this->provider->findByZipcode(self::ZIPCODE);

        self::assertInstanceOf(Address::class, $address);

        $expectedAddress =  (new Address())
            ->setZipcode(self::ZIPCODE);

        self::assertEquals($expectedAddress, $address);
    }

    public function testItCanGetCorrectValuesForFindByZipcodeAndHouseNumber(): void
    {
        $this->provider->setHttpClient(new Client([
            'handler' => new MockHandler([
                new Response(200, [], '{"result":[{"identificatie":"0847200000376896","postcode":"9999AA","huisnummer":"998","huisnummer_toevoeging":"1","huisletter":"T","straat":"Bosrandweg","straat_nen5825":"","plaats":"Someren","provincie":"Groningen","gebruiksdoelVerblijfsobject":"overige gebruiksfunctie","oppervlakteVerblijfsobject":"20"}],"hasResult":true}')
            ])
        ]));

        $address = $this->provider->findByZipcodeAndHouseNumber(self::ZIPCODE, self::HOUSE_NUMBER);

        self::assertInstanceOf(Address::class, $address);

        $expectedAddress =  (new Address())
            ->setZipcode(self::ZIPCODE)
            ->setProvince('Groningen')
            ->setCity('Someren')
            ->setStreet('Bosrandweg')
            ->setHouseNumber(self::HOUSE_NUMBER)
            ->setAddition('1T')
        ;

        self::assertEquals($expectedAddress, $address);
    }
}