<?php

declare(strict_types=1);

namespace Metapixel\PostcodeAPI\Provider;

use Metapixel\PostcodeAPI\Entity\Address;
use Metapixel\PostcodeAPI\Entity\Coordinates;
use Metapixel\PostcodeAPI\Entity\SearchRequest;
use Metapixel\PostcodeAPI\Trait\ApiKeyTrait;

abstract class AbstractPostNL extends Provider
{
    use ApiKeyTrait;

    public const BASE_URL = 'https://api.postnl.nl/v2/address/benelux';

    public const ISO_CODE = 'NL';

    public function findBySearchRequest(SearchRequest $searchRequest): Address
    {
        $params = [];

        if (null !== $searchRequest->getZipcode()) {
            $params['postalCode'] = $searchRequest->getZipcode();
        }

        if (null !== $searchRequest->getCity()) {
            $params['cityName'] = $searchRequest->getCity();
        }

        if (null !== $searchRequest->getStreet()) {
            $params['streetName'] = $searchRequest->getStreet();
        }

        if (null !== $searchRequest->getHouseNumber()) {
            $params['houseNumber'] = $searchRequest->getHouseNumber();
        }

        if (null !== $searchRequest->getAddition()) {
            $params['houseNumberAddition'] = $searchRequest->getAddition();
        }

        $params['countryIso'] = $this::ISO_CODE;

        $this->setRequestUrl(
            sprintf(
                '%s?%s',
                self::BASE_URL,
                http_build_query($params)
            )
        );

        return parent::findBySearchRequest($searchRequest);
    }

    public function request(): array
    {
        $client = $this->getHttpClient();
        $response = $client->request('GET', $this->getRequestUrl(), [
            'headers' => [
                'apikey' => $this->getApiKey(),
            ],
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    public function toAddress(array $response): Address
    {
        $address = new Address();

        $result = $response[0];

        $address
            ->setCountry($result['countryName'] ?? null)
            ->setCountryCode($result['countryIso2'] ?? null)
            ->setZipcode($result['postalCode'] ?? null)
            ->setCity($result['cityName'] ?? null)
            ->setProvince($result['stateName'] ?? null)
            ->setStreet($result['streetName'] ?? null)
            ->setHouseNumber($result['houseNumber'] ? (string) $result['houseNumber'] : null)
            ->setAddition($result['houseNumberAddition'] && '' !== $result['houseNumberAddition'] ? $result['houseNumberAddition'] : null)
        ;

        if (isset($result['latitude'], $result['longitude'])) {
            $coordinates = new Coordinates();

            $coordinates
                ->setLatitude($result['latitude'])
                ->setLongitude($result['longitude'])
            ;

            $address->setCoordinates($coordinates);
        }

        return $address;
    }
}
