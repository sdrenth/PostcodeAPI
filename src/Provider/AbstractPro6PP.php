<?php

declare(strict_types=1);

namespace Metapixel\PostcodeAPI\Provider;

use Metapixel\PostcodeAPI\Entity\Coordinates;
use Metapixel\PostcodeAPI\Entity\Address;
use Metapixel\PostcodeAPI\Entity\SearchRequest;
use Metapixel\PostcodeAPI\Event\PostSearchRequestEvent;

abstract class AbstractPro6PP extends Provider
{
    CONST BASE_URL = 'https://api.pro6pp.nl/v2/autocomplete/';

    protected $languageEndpoint = 'nl';

    public function findBySearchRequest(SearchRequest $searchRequest): Address
    {
        $params = [
            'authKey' => $this->getApiKey()
        ];

        if (null !== $searchRequest->getZipcode()) {
            $params['postalCode'] = $searchRequest->getZipcode();
        }

        if (null !== $searchRequest->getCity()) {
            $params['settlement'] = $searchRequest->getCity();
        }

        if (null !== $searchRequest->getStreet()) {
            $params['street'] = $searchRequest->getStreet();
        }

        if (null !== $searchRequest->getHouseNumber()) {
            $houseNumberAndPremise = [$searchRequest->getHouseNumber()];

            if (null !== $searchRequest->getAddition()) {
                $houseNumberAndPremise[] = $searchRequest->getAddition();
            }

            $params['streetNumberAndPremise'] = implode('', $houseNumberAndPremise);
        }

        $this->setRequestUrl(
            sprintf(
                '%s%s?%s',
                self::BASE_URL,
                $this->getLanguageEndpoint(),
                http_build_query($params)
            )
        );

        return parent::findBySearchRequest($searchRequest);
    }

    public function getLanguageEndpoint(): string
    {
        return $this->languageEndpoint;
    }

    public function setLanguageEndpoint(string $languageEndpoint): void
    {
        $this->languageEndpoint = $languageEndpoint;
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
            ->setCountry($response['country'] ?? null)
            ->setCountryCode($response['countryCode'] ?? null)
            ->setZipcode($response['postalCode'] ?? null)
            ->setCity($response['settlement'] ?? null)
            ->setMunicipality($response['municipality'] ?? null)
            ->setProvince($response['province'] ?? null)
            ->setStreet($response['street'] ?? null)
            ->setHouseNumber($response['streetNumber'] ? (string) $response['streetNumber'] : null)
            ->setAddition($response['premise'] ?? null);

        if (!empty($latitude = $response['lat']) && !empty($longitude = $response['lng'])) {
            $coordinates = new Coordinates();

            $coordinates->setLatitude($latitude)
                ->setLongitude($longitude);

            $address->setCoordinates($coordinates);
        }

        return $address;
    }
}