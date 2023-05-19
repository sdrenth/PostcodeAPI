<?php

declare(strict_types=1);

namespace Metapixel\PostcodeAPI\Provider\nl_NL;

use Metapixel\PostcodeAPI\Entity\Address;
use Metapixel\PostcodeAPI\Entity\Coordinates;
use Metapixel\PostcodeAPI\Entity\SearchRequest;
use Metapixel\PostcodeAPI\Exception\InvalidApiCredentialsException;
use Metapixel\PostcodeAPI\Provider\Provider;
use Metapixel\PostcodeAPI\Trait\ApiSubscriberIdTrait;
use Metapixel\PostcodeAPI\Trait\ApiUsernamePasswordTrait;

class PostcodesNU extends Provider
{
    use ApiUsernamePasswordTrait,
        ApiSubscriberIdTrait;

    public const BASE_URL = 'https://postcodes.nu/';

    protected ?string $bearerToken = null;

    public function find(string $zipcode): Address
    {
        $this->setRequestUrl(
            sprintf(
                '%s/api/v1/postcode/exists/%s',
                trim(self::BASE_URL, '/'),
                $zipcode,
            )
        );

        return parent::find($zipcode);
    }

    public function findBySearchRequest(SearchRequest $searchRequest): Address
    {
        if ('' === $this->getRequestUrl()) {
            $this->setRequestUrl(
                trim(
                    sprintf(
                        '%s/api/v1/postcode/%s/%s/%s',
                        trim(self::BASE_URL, '/'),
                        $searchRequest->getZipcode(),
                        $searchRequest->getHouseNumber(),
                        $searchRequest->getAddition() ?? ''
                    ),
                    '/'
                )
            );
        }

        return parent::findBySearchRequest($searchRequest);
    }

    public function request(): array
    {
        $client = $this->getHttpClient();

        if (null === $this->getApiSubscriberId()) {
            throw new InvalidApiCredentialsException();
        }

        $response = $client->request('GET', $this->getRequestUrl(), [
            'headers' => [
                'Authorization' => 'Bearer '.$this->getBearerToken(),
                'abonnement-id' => $this->apiSubscriberId,
            ],
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    public function toAddress(array $response): Address
    {
        $address = new Address();

        if (isset($response['exists'])) {
            $address->setZipcode($this->getSearchRequest()->getZipcode());

            return $address;
        }

        if (isset($response['hasResult'], $response['result'][0]) && true === $response['hasResult']) {
            $result = $response['result'][0];

            $addition = [];
            foreach (['huisnummer_toevoeging', 'huisletter'] as $key) {
                if (isset($result[$key]) && '' !== $result[$key]) {
                    $addition[] = $result[$key];
                }
            }

            $address
                ->setZipcode($result['postcode'] ?? null)
                ->setCity($result['plaats'] ?? null)
                ->setMunicipality($result['gemeente'] ?? null)
                ->setProvince($result['provincie'] ?? null)
                ->setStreet($result['straat'] ?? null)
                ->setHouseNumber($result['huisnummer'] ? (string) $result['huisnummer'] : null)
                ->setAddition(implode('', $addition))
            ;

            if (isset($result['latlon']) && '' !== $result['latlon']) {
                [$latitude, $longitude] = explode(' ', $result['latlon']);

                $coordinates = new Coordinates();

                $coordinates
                    ->setLatitude((float) $latitude)
                    ->setLongitude((float) $longitude)
                ;

                $address->setCoordinates($coordinates);
            }
        }

        return $address;
    }

    public function setBearerToken(string $token): self
    {
        $this->bearerToken = $token;

        return $this;
    }

    public function getBearerToken(): ?string
    {
        if (null === $this->bearerToken) {
            if (null === $this->getApiUsername() || null === $this->getApiPassword()) {
                throw new InvalidApiCredentialsException();
            }

            $response = $this->getHttpClient()->request('POST', self::BASE_URL.'api/auth/login', [
                'json' => [
                    'email' => $this->getApiUsername(),
                    'password' => $this->getApiPassword(),
                ],
            ]);

            $response = json_decode($response->getBody()->getContents(), true);

            $this->setBearerToken($response['access_token']);
        }

        return $this->bearerToken;
    }
}
