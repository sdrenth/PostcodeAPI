<?php

declare(strict_types=1);

namespace Metapixel\PostcodeAPI\Provider\nl_NL;

use Metapixel\PostcodeAPI\Entity\Address;
use Metapixel\PostcodeAPI\Entity\Coordinates;
use Metapixel\PostcodeAPI\Entity\SearchRequest;
use Metapixel\PostcodeAPI\Exception\MethodNotSupportedException;
use Metapixel\PostcodeAPI\Provider\Provider;
use Metapixel\PostcodeAPI\Trait\ApiKeyTrait;

class PostcodeTech extends Provider
{
    use ApiKeyTrait;

    public const BASE_URL = 'https://postcode.tech/api/v1/postcode/full';

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
                    'postcode' => $searchRequest->getZipcode(),
                    'number' => $searchRequest->getHouseNumberInclAddition(),
                ]),
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
                    'Authorization' => 'Bearer '.$this->getApiKey(),
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

        if (isset($response['geo']['lat'], $response['geo']['lon'])) {
            $coordinates = new Coordinates();

            $coordinates
                ->setLatitude($response['geo']['lat'])
                ->setLongitude($response['geo']['lon'])
            ;

            $address->setCoordinates($coordinates);
        }

        return $address;
    }
}
