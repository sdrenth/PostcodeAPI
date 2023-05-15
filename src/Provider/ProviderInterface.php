<?php

declare(strict_types=1);

namespace Metapixel\PostcodeAPI\Provider;

use GuzzleHttp\Client;
use Metapixel\PostcodeAPI\Entity\Address;
use Metapixel\PostcodeAPI\Entity\SearchRequest;

interface ProviderInterface
{
    public function getApiKey(): ?string;

    public function setApiKey(?string $apiKey): self;

    public function getApiSecret(): ?string;

    public function setApiSecret(?string $apiSecret): self;

    public function getRequestUrl(): string;

    public function setRequestUrl(string $requestUrl): void;

    public function getHttpClient(): Client;

    public function setHttpClient(Client $client): Provider;

    public function find(string $zipcode): Address;

    public function findByZipcode(string $zipcode): Address;

    public function findByZipcodeAndHouseNumber(string $zipcode, string $houseNumber): Address;

    public function findBySearchRequest(SearchRequest $searchRequest): Address;

    public function request(): array;

    public function toAddress(array $response): Address;
}
