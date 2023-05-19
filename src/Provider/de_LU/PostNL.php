<?php

declare(strict_types=1);

namespace Metapixel\PostcodeAPI\Provider\de_LU;

use Metapixel\PostcodeAPI\Entity\Address;
use Metapixel\PostcodeAPI\Exception\MethodNotSupportedException;
use Metapixel\PostcodeAPI\Provider\AbstractPostNL;

class PostNL extends AbstractPostNL
{
    public const ISO_CODE = 'LU';

    public function find(string $zipcode): Address
    {
        throw new MethodNotSupportedException();
    }

    public function findByZipcode(string $zipcode): Address
    {
        throw new MethodNotSupportedException();
    }
}
