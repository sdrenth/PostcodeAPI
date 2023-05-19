<?php

declare(strict_types=1);

namespace Metapixel\PostcodeAPI\Factory;

use Metapixel\PostcodeAPI\Exception\InvalidArgumentException;
use Metapixel\PostcodeAPI\Provider\Provider;

class ProviderFactory
{
    /**
     * @param string $provider
     *
     * @return Provider
     */
    public static function create(string $provider): Provider
    {
        [$code, $className] = explode('.', $provider);

        $providerClass = "Metapixel\\PostcodeAPI\\Provider\\{$code}\\{$className}";
        if (!class_exists($providerClass)) {
            throw new InvalidArgumentException(sprintf('Unable to use the provider "%s"', $className));
        }

        $providerObject = new $providerClass();
        if (!$providerObject instanceof Provider) {
            throw new InvalidArgumentException(sprintf('Unable to use the provider "%s"', $className));
        }

        return $providerObject;
    }
}
