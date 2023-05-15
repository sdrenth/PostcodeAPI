<?php

declare(strict_types=1);

namespace Metapixel\PostcodeAPI\Factory;

use Metapixel\PostcodeAPI\Provider\Provider;
use Metapixel\PostcodeAPI\Exception\InvalidArgumentException;

class ProviderFactory
{
    public static function create(string $provider): Provider
    {
        [$code, $className] = explode('.', $provider);

        $providerClass = "Metapixel\\PostcodeAPI\\Provider\\{$code}\\{$className}";
        if (!class_exists($providerClass)) {
            throw new InvalidArgumentException(sprintf('Unable to use the provider "%s"', $className));
        }

        /** @var Provider $class */
        $class = new $providerClass;

        return $class;
    }
}