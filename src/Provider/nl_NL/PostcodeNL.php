<?php

declare(strict_types=1);

namespace Metapixel\PostcodeAPI\Provider\nl_NL;

use Metapixel\PostcodeAPI\Entity\Address;
use Metapixel\PostcodeAPI\Entity\Coordinates;
use Metapixel\PostcodeAPI\Entity\SearchRequest;
use Metapixel\PostcodeAPI\Exception\MethodNotSupportedException;
use Metapixel\PostcodeAPI\Provider\Provider;
use Metapixel\PostcodeAPI\Trait\ApiKeyTrait;
use Metapixel\PostcodeAPI\Trait\ApiSecretTrait;

class PostcodeNL extends Provider
{
    use ApiKeyTrait,
        ApiSecretTrait;

    public const BASE_URL = 'https://api.postcode.eu/nl/v1/addresses/postcode';

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
                '%s/%s/%s/%s',
                self::BASE_URL,
                $searchRequest->getZipcode(),
                $searchRequest->getHouseNumber(),
                $searchRequest->getAddition() ?? ''
            )
        );

        return parent::findBySearchRequest($searchRequest);
    }

    public function request(): array
    {
        $client = $this->getHttpClient();
        $response = $client->request('GET', $this->getRequestUrl(), [
            'auth' => [
                $this->getApiKey(),
                $this->getApiSecret(),
            ],
        ]);

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
            ->setHouseNumber($response['houseNumber'] ? (string) $response['houseNumber'] : null)
            ->setAddition($response['houseNumberAddition'] ?? null)
        ;

        if (isset($response['latitude'], $response['longitude'])) {
            $coordinates = new Coordinates();

            $coordinates
                ->setLatitude($response['latitude'])
                ->setLongitude($response['longitude'])
            ;

            $address->setCoordinates($coordinates);
        }

        return $address;
    }
}
