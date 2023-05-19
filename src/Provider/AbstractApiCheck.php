<?php

declare(strict_types=1);

namespace Metapixel\PostcodeAPI\Provider;

use Metapixel\PostcodeAPI\Entity\Address;
use Metapixel\PostcodeAPI\Entity\Coordinates;
use Metapixel\PostcodeAPI\Entity\SearchRequest;
use Metapixel\PostcodeAPI\Trait\ApiKeyTrait;

abstract class AbstractApiCheck extends Provider
{
    use ApiKeyTrait;

    public const BASE_URL = 'https://api.apicheck.nl/lookup/v1/postalcode';

    protected string $languageEndpoint = 'nl';

    public function findBySearchRequest(SearchRequest $searchRequest): Address
    {
        $params = [];

        if (null !== $searchRequest->getZipcode()) {
            $params['postalcode'] = $searchRequest->getZipcode();
        }

        if (null !== $searchRequest->getHouseNumber()) {
            $params['number'] = $searchRequest->getHouseNumber();
        }

        if (null !== $searchRequest->getAddition()) {
            $params['numberAddition'] = $searchRequest->getAddition();
        }

        $this->setRequestUrl(
            sprintf(
                '%s/%s?%s',
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
        $response = $client->request('GET', $this->getRequestUrl(), [
            'headers' => [
                'X-API-KEY' => $this->getApiKey(),
            ],
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    public function toAddress(array $response): Address
    {
        $address = new Address();

        if (!isset($response['data'], $response['error']) || true === $response['error']) {
            return $address;
        }

        $address
            ->setCountry($response['data']['Country']['name'] ?? null)
            ->setCountryCode($response['data']['Country']['code'] ?? null)
            ->setZipcode($response['data']['postalcode'] ?? null)
            ->setCity($response['data']['city'] ?? null)
            ->setMunicipality($response['data']['municipality'] ?? null)
            ->setProvince($response['data']['province'] ?? null)
            ->setStreet($response['data']['street'] ?? null)
            ->setHouseNumber($response['data']['number'] ? (string) $response['data']['number'] : null)
            ->setAddition($response['data']['numberAddition'] ?? null)
        ;

        if (isset($response['data']['Location']['Coordinates']['latitude'], $response['data']['Location']['Coordinates']['longitude'])) {
            $coordinates = new Coordinates();

            $coordinates
                ->setLatitude((float) $response['data']['Location']['Coordinates']['latitude'])
                ->setLongitude((float) $response['data']['Location']['Coordinates']['longitude'])
            ;

            $address->setCoordinates($coordinates);
        }

        return $address;
    }
}
