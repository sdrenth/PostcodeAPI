<?php

declare(strict_types=1);

namespace Metapixel\PostcodeAPI\Tests\unit\Factory;

use Metapixel\PostcodeAPI\Exception\InvalidArgumentException;
use Metapixel\PostcodeAPI\Factory\ProviderFactory;
use Metapixel\PostcodeAPI\Provider\Provider;
use PHPUnit\Framework\TestCase;

class ProviderFactoryTest extends TestCase
{
    public function testCreate(): void
    {
        $providerObject = ProviderFactory::create('nl_NL.PostcodeNL');

        $this->assertInstanceOf(Provider::class, $providerObject);
    }

    public function testCreateWithInvalidProvider(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $invalidProvider = 'invalid.provider';

        ProviderFactory::create($invalidProvider);
    }
}
