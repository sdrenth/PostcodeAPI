<?php

declare(strict_types=1);

namespace Metapixel\PostcodeAPI\Tests\unit\Entity;

use Metapixel\PostcodeAPI\Entity\Coordinates;
use PHPUnit\Framework\TestCase;

class CoordinatesTest extends TestCase
{
    public function testGetLatitude(): void
    {
        $latitude = 52.374030;
        $coordinates = new Coordinates();
        $coordinates->setLatitude($latitude);

        $result = $coordinates->getLatitude();

        $this->assertEquals($latitude, $result);
    }

    public function testGetLongitude(): void
    {
        $longitude = 4.889690;
        $coordinates = new Coordinates();
        $coordinates->setLongitude($longitude);

        $result = $coordinates->getLongitude();

        $this->assertEquals($longitude, $result);
    }
}
