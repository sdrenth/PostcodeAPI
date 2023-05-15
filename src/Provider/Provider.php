<?php

declare(strict_types=1);

namespace Metapixel\PostcodeAPI\Provider;

use GuzzleHttp\Client;
use Metapixel\PostcodeAPI\Entity\Address;
use Metapixel\PostcodeAPI\Entity\SearchRequest;
use Metapixel\PostcodeAPI\Event\PostSearchRequestEvent;
use Metapixel\PostcodeAPI\Event\PreSearchRequestEvent;
use Psr\EventDispatcher\StoppableEventInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;

abstract class Provider implements ProviderInterface
{
    public EventDispatcher $dispatcher;

    protected ?string $apiKey;

    protected ?string $apiSecret;

    protected string $requestUrl = '';

    protected array $options = [];

    protected Client $httpClient;

    public function __construct()
    {
        $this->dispatcher = new EventDispatcher();
    }

    public function getApiKey(): ?string
    {
        return $this->apiKey;
    }

    public function setApiKey(?string $apiKey): self
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    public function getApiSecret(): ?string
    {
        return $this->apiSecret;
    }

    public function setApiSecret(?string $apiSecret): self
    {
        $this->apiSecret = $apiSecret;

        return $this;
    }

    public function getRequestUrl(): string
    {
        return $this->requestUrl;
    }

    public function setRequestUrl(string $requestUrl): void
    {
        $this->requestUrl = $requestUrl;
    }

    public function getHttpClient(): Client
    {
        if (!isset($this->httpClient)) {
            $this->setHttpClient(new Client());
        }

        return $this->httpClient;
    }

    public function setHttpClient(Client $client): self
    {
        $this->httpClient = $client;

        return $this;
    }

    public function find(string $zipcode): Address
    {
        /** @var SearchRequest $searchRequest */
        $searchRequest = (new SearchRequest())
            ->setZipcode($zipcode)
        ;

        return $this->findBySearchRequest($searchRequest);
    }

    public function findByZipcode(string $zipcode): Address
    {
        return $this->find($zipcode);
    }

    public function findByZipcodeAndHouseNumber(string $zipcode, string $houseNumber): Address
    {
        /** @var SearchRequest $searchRequest */
        $searchRequest = (new SearchRequest())
            ->setZipcode($zipcode)
            ->setHouseNumber($houseNumber)
        ;

        return $this->findBySearchRequest($searchRequest);
    }

    public function findBySearchRequest(SearchRequest $searchRequest): Address
    {
        /** @var PostSearchRequestEvent $event */
        $event = $this->dispatchPreEvent($searchRequest);

        if ($event->getAddress() instanceof Address) {
            return $event->getAddress();
        }

        $response = $this->request();
        $address = $this->toAddress($response);

        $this->dispatchPostEvent($searchRequest, $address);

        return $address;
    }

    public function dispatchPreEvent(SearchRequest $searchRequest): StoppableEventInterface
    {
        $event = new PostSearchRequestEvent($searchRequest);

        return $this->dispatcher->dispatch($event, PostSearchRequestEvent::NAME);
    }

    public function dispatchPostEvent(SearchRequest $searchRequest, Address $address): StoppableEventInterface
    {
        $event = new PreSearchRequestEvent($searchRequest, $address);

        return $this->dispatcher->dispatch($event, PreSearchRequestEvent::NAME);
    }
}
