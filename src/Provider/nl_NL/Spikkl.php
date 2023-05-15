<?php

declare(strict_types=1);

namespace Metapixel\PostcodeAPI\Provider\nl_NL;

use Metapixel\PostcodeAPI\Entity\Address;
use Metapixel\PostcodeAPI\Entity\Coordinates;
use Metapixel\PostcodeAPI\Entity\SearchRequest;
use Metapixel\PostcodeAPI\Exception\MethodNotSupportedException;
use Metapixel\PostcodeAPI\Provider\Provider;

class Spikkl extends Provider
{
    public const BASE_URL = 'https://api.spikkl.nl/geo/nld/lookup.json';

    public function find(string $zipcode): Address
    {
        throw new MethodNotSupportedException();
    }

    public function findByZipcode(string $zipcode): Address
    {
        throw new MethodNotSupportedException();
    }

    public function findBySearchRequest(SearchRequest $searchRequest): Address
    {
        $this->setRequestUrl(
            sprintf(
                '%s?%s',
                self::BASE_URL,
                http_build_query([
                    'key' => $this->getApiKey(),
                    'postal_code' => $searchRequest->getZipcode(),
                    'street_number' => $searchRequest->getHouseNumberInclAddition(),
                ])
            )
        );

        return parent::findBySearchRequest($searchRequest);
    }

    public function request(): array
    {
        $client = $this->getHttpClient();
        $response = $client->request('GET', $this->getRequestUrl());

        return json_decode($response->getBody()->getContents(), true);
    }

    public function toAddress(array $response): Address
    {
        $address = new Address();

        $address
            ->setCountry($response['results'][0]['country']['name'] ?? null)
            ->setCountryCode($response['results'][0]['country']['iso3_code'] ?? null)
            ->setZipcode($response['results'][0]['postal_code'] ?? null)
            ->setCity($response['results'][0]['city'] ?? null)
            ->setMunicipality($response['results'][0]['municipality'] ?? null)
            ->setStreet($response['results'][0]['street_name'] ?? null)
            ->setHouseNumber($response['results'][0]['street_number'] ? (string) $response['results'][0]['street_number'] : null)
        ;

        if (isset($response['results'][0]['centroid']) && is_array($response['results'][0]['centroid'])) {
            $coordinates = new Coordinates();

            $coordinates
                ->setLatitude($response['results'][0]['centroid']['latitude'])
                ->setLongitude($response['results'][0]['centroid']['longitude'])
            ;

            $address->setCoordinates($coordinates);
        }

        return $address;
    }
}
