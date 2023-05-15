<?php

declare(strict_types=1);

namespace Metapixel\PostcodeAPI\Provider\fr_FR;

use Metapixel\PostcodeAPI\Event\PostSearchRequestEvent;
use Metapixel\PostcodeAPI\Exception\MethodNotSupportedException;
use Metapixel\PostcodeAPI\Provider\AbstractPro6PP;
use Metapixel\PostcodeAPI\Entity\Address;

class Pro6PP extends AbstractPro6PP
{
    public function __construct()
    {
        $this->setLanguageEndpoint('fr');

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

    public function findByZipcodeAndHouseNumber(string $zipcode, string $houseNumber): Address
    {
        throw new MethodNotSupportedException();
    }
}