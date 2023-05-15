<?php

declare(strict_types=1);

namespace Metapixel\PostcodeAPI\Provider\nl_NL;

use Metapixel\PostcodeAPI\Entity\Address;
use Metapixel\PostcodeAPI\Entity\Coordinates;
use Metapixel\PostcodeAPI\Entity\SearchRequest;
use Metapixel\PostcodeAPI\Exception\MethodNotSupportedException;
use Metapixel\PostcodeAPI\Provider\Provider;

class PostcodeAPINu extends Provider
{
    public const BASE_URL = 'https://api.postcodeapi.nu/v3/lookup';

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
                '%s/%s/%s',
                self::BASE_URL,
                $searchRequest->getZipcode(),
                $searchRequest->getHouseNumber()
            )
        );

        return parent::findBySearchRequest($searchRequest);
    }

    public function request(): array
    {
        $client = $this->getHttpClient();
        $response = $client
            ->request('GET', $this->getRequestUrl(), [
                'headers' => [
                    'X-Api-Key' => $this->getApiKey(),
                ],
            ])
        ;

        return json_decode($response->getBody()->getContents(), true);
    }

    public function toAddress(array $response): Address
    {
        $address = new Address();

        $address
            ->setZipcode($response['postcode'] ?? null)
            ->setCity($response['city'] ?? null)
            ->setMunicipality($response['municipality'] ?? null)
            ->setProvince($response['province'] ?? null)
            ->setStreet($response['street'] ?? null)
            ->setHouseNumber($response['number'] ? (string) $response['number'] : null)
        ;

        if (isset($response['location']['coordinates']) && is_array($response['location']['coordinates'])) {
            $coordinates = new Coordinates();

            [$latitude, $longitude] = $response['location']['coordinates'];

            $coordinates
                ->setLatitude($latitude)
                ->setLongitude($longitude)
            ;

            $address->setCoordinates($coordinates);
        }

        return $address;
    }
}
