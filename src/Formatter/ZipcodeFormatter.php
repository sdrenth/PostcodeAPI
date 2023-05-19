<?php

declare(strict_types=1);

namespace Metapixel\PostcodeAPI\Formatter;

class ZipcodeFormatter
{
    public static function format(string $zipcode): string
    {
        return strtoupper((string) preg_replace('/\s+/', '', $zipcode));
    }
}
