<?php

declare(strict_types=1);

namespace Metapixel\PostcodeAPI\Provider\de_LU;

use Metapixel\PostcodeAPI\Entity\Address;
use Metapixel\PostcodeAPI\Exception\MethodNotSupportedException;
use Metapixel\PostcodeAPI\Provider\AbstractApiCheck;

class ApiCheck extends AbstractApiCheck
{
    public function __construct()
    {
        $this->setLanguageEndpoint('lu');

        parent::__construct();
    }

    public function find(string $zipcode): Address
    {
        throw new MethodNotSupportedException();
    }

    public function findByZipcode(string $zipcode): Address
    {
        throw new MethodNotSupportedException();
    }
}
