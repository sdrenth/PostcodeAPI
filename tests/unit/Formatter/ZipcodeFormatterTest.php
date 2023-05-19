<?php

declare(strict_types=1);

namespace Metapixel\PostcodeAPI\Tests\unit\Formatter;

use Metapixel\PostcodeAPI\Formatter\ZipcodeFormatter;
use PHPUnit\Framework\TestCase;

class ZipcodeFormatterTest extends TestCase
{
    public function testFormat(): void
    {
        $zipcode = ' 1234 AB ';
        $formattedZipcode = ZipcodeFormatter::format($zipcode);

        $this->assertEquals('1234AB', $formattedZipcode);
    }
}
