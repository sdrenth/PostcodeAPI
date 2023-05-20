<?php

declare(strict_types=1);

namespace Metapixel\PostcodeAPI\Provider\nl_NL;

use Metapixel\PostcodeAPI\Entity\Address;
use Metapixel\PostcodeAPI\Entity\Coordinates;
use Metapixel\PostcodeAPI\Entity\SearchRequest;
use Metapixel\PostcodeAPI\Exception\MethodNotSupportedException;
use Metapixel\PostcodeAPI\Provider\Provider;
use Metapixel\PostcodeAPI\Trait\ApiDomainTrait;
use Metapixel\PostcodeAPI\Trait\ApiUsernamePasswordTrait;

class PostcodeService extends Provider
{
    use ApiUsernamePasswordTrait,
        ApiDomainTrait;

    public const BASE_URL = 'https://api.postcodeservice.com/nl/v5/find';

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
                    'zipcode' => $searchRequest->getZipcode(),
                    'houseno' => $searchRequest->getHouseNumberInclAddition(),
                ])
            )
        );

        return parent::findBySearchRequest($searchRequest);
    }

    public function request(): array
    {
        $options = [
            'headers' => [
                'X-ClientId' => $this->getApiUsername(),
                'X-SecureCode' => $this->getApiPassword(),
            ],
        ];

        if (null !== $this->getApiDomain()) {
            $options['Headers'][] = $this->getApiDomain();
        }

        $client = $this->getHttpClient();
        $response = $client->request('GET', $this->getRequestUrl(), $options);

        return json_decode($response->getBody()->getContents(), true);
    }

    public function toAddress(array $response): Address
    {
        $address = new Address();

        $address
            ->setZipcode($this->getSearchRequest()->getZipcode())
            ->setCity($response['city'] ?? null)
            ->setProvince($response['region'] ?? null)
            ->setStreet($response['street'] ?? null)
            ->setHouseNumber($this->getSearchRequest()->getHouseNumber())
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
