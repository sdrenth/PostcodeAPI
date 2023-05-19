<?php

declare(strict_types=1);

namespace Metapixel\PostcodeAPI\Tests\unit\Entity;

use Metapixel\PostcodeAPI\Entity\Address;
use Metapixel\PostcodeAPI\Entity\Coordinates;
use PHPUnit\Framework\TestCase;

class SearchRequestTest extends TestCase
{
    public function testSetCountry(): void
    {
        $address = new Address();
        $country = 'Netherlands';

        $address->setCountry($country);

        $this->assertEquals($country, $address->getCountry());
    }

    public function testSetCountryCode(): void
    {
        $address = new Address();
        $countryCode = 'NL';

        $address->setCountryCode($countryCode);

        $this->assertEquals($countryCode, $address->getCountryCode());
    }

    public function testSetStreet(): void
    {
        $address = new Address();
        $street = 'Main Street';

        $address->setStreet($street);

        $this->assertEquals($street, $address->getStreet());
    }

    public function testSetCity(): void
    {
        $address = new Address();
        $city = 'Amsterdam';

        $address->setCity($city);

        $this->assertEquals($city, $address->getCity());
    }

    public function testSetMunicipality(): void
    {
        $address = new Address();
        $municipality = 'Amsterdam';

        $address->setMunicipality($municipality);

        $this->assertEquals($municipality, $address->getMunicipality());
    }

    public function testSetProvince(): void
    {
        $address = new Address();
        $province = 'North Holland';

        $address->setProvince($province);

        $this->assertEquals($province, $address->getProvince());
    }

    public function testSetZipcode(): void
    {
        $address = new Address();
        $zipcode = ' 1234 AB ';

        $address->setZipcode($zipcode);

        $this->assertEquals('1234AB', $address->getZipcode());
    }

    public function testSetHouseNumber(): void
    {
        $address = new Address();
        $houseNumber = '123';

        $address->setHouseNumber($houseNumber);

        $this->assertEquals($houseNumber, $address->getHouseNumber());
    }

    public function testSetAddition(): void
    {
        $address = new Address();
        $addition = 'A';

        $address->setAddition($addition);

        $this->assertEquals($addition, $address->getAddition());
    }

    public function testSetCoordinates(): void
    {
        $address = new Address();
        $coordinates = new Coordinates();
        $latitude = 52.374030;
        $longitude = 4.889690;
        $coordinates->setLatitude($latitude);
        $coordinates->setLongitude($longitude);

        $address->setCoordinates($coordinates);

        $result = $address->getCoordinates();

        $this->assertInstanceOf(Coordinates::class, $result);
        $this->assertEquals($latitude, $result->getLatitude());
        $this->assertEquals($longitude, $result->getLongitude());
    }
}