<?php

declare(strict_types=1);

namespace Metapixel\PostcodeAPI\Provider;

use GuzzleHttp\Client;
use Metapixel\PostcodeAPI\Entity\Address;
use Metapixel\PostcodeAPI\Entity\SearchRequest;
use Metapixel\PostcodeAPI\Event\PostSearchRequestEvent;
use Metapixel\PostcodeAPI\Event\PreSearchRequestEvent;
use Metapixel\PostcodeAPI\Factory\SearchRequestFactory;
use Psr\EventDispatcher\StoppableEventInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;

abstract class Provider implements ProviderInterface
{
    public EventDispatcher $dispatcher;

    protected string $requestUrl = '';

    protected array $options = [];

    protected Client $httpClient;

    protected SearchRequest $searchRequest;

    public function __construct()
    {
        $this->dispatcher = new EventDispatcher();
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
        $searchRequest = SearchRequestFactory::createByZipcode($zipcode);

        return $this->findBySearchRequest($searchRequest);
    }

    public function findByZipcode(string $zipcode): Address
    {
        return $this->find($zipcode);
    }

    public function findByZipcodeAndHouseNumber(string $zipcode, string $houseNumber): Address
    {
        $searchRequest = SearchRequestFactory::createByZipcodeAndHouseNumber($zipcode, $houseNumber);

        return $this->findBySearchRequest($searchRequest);
    }

    public function findBySearchRequest(SearchRequest $searchRequest): Address
    {
        $this->setSearchRequest($searchRequest);

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

    public function getSearchRequest(): SearchRequest
    {
        return $this->searchRequest;
    }

    public function setSearchRequest(SearchRequest $searchRequest): void
    {
        $this->searchRequest = $searchRequest;
    }
}
