<?php

declare(strict_types=1);

namespace Metapixel\PostcodeAPI\Provider\nl_BE;

use Metapixel\PostcodeAPI\Entity\Address;
use Metapixel\PostcodeAPI\Exception\MethodNotSupportedException;
use Metapixel\PostcodeAPI\Provider\AbstractPostNL;

class PostNL extends AbstractPostNL
{
    public const ISO_CODE = 'BE';

    public function find(string $zipcode): Address
    {
        throw new MethodNotSupportedException();
    }

    public function findByZipcode(string $zipcode): Address
    {
        throw new MethodNotSupportedException();
    }
}
