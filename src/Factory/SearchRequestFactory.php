<?php

declare(strict_types=1);

namespace Metapixel\PostcodeAPI\Factory;

use Metapixel\PostcodeAPI\Entity\SearchRequest;

class SearchRequestFactory
{
    public static function create(): SearchRequest
    {
        return new SearchRequest();
    }

    public static function createByZipcode(string $zipcode): SearchRequest
    {
        $searchRequest = self::create();
        $searchRequest->setZipcode($zipcode);

        return $searchRequest;
    }

    public static function createByZipcodeAndHouseNumber(string $zipcode, string $houseNumber): SearchRequest
    {
        return self::createByZipcode($zipcode) /* @phpstan-ignore-line */
            ->setHouseNumber($houseNumber)
        ;
    }
}
