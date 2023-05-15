<?php

declare(strict_types=1);

namespace Metapixel\PostcodeAPI\Provider\nl_NL;

use Metapixel\PostcodeAPI\Exception\MethodNotSupportedException;
use Metapixel\PostcodeAPI\Provider\AbstractPro6PP;
use Metapixel\PostcodeAPI\Entity\Address;

class Pro6PP extends AbstractPro6PP
{
    public function find(string $zipcode): Address
    {
        throw new MethodNotSupportedException();
    }

    public function findByZipcode(string $zipcode): Address
    {
        throw new MethodNotSupportedException();
    }
}