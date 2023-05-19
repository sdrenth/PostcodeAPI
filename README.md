# PostcodeAPI
![Tests](https://github.com/sdrenth/PostcodeAPI/actions/workflows/tests.yml/badge.svg) 

This package implements a generic approach for retrieving address details based on zipcode and housenumber to perform address validation. 
The goal of this package is to have a single package which supports your favorite Postcode API providers so you can easily switch between providers without having to rewrite all of your code or need to have extensive knowledge about all the providers, because this package handles that for you.

This package is platform independent and can be used in any modern PHP application.

## Providers
The following providers are currently supported:
- [Apicheck.nl](https://apicheck.nl/)
- [Postcodeapi.nu](Postcodeapi.nu)
- [Postcode.nl](https://www.postcode.nl/)
- [Postcodes.nu](https://postcodes.nu/)
- [Pro6PP](https://www.pro6pp.nl/)
- [Spikkl](https://www.spikkl.nl/)

### Events
Pre and post search events are dispatched using the Symfony Event Dispatcher component.

The `PreSearchRequestEvent` and `PostSearchRequestEvent` allows you to add some additional custom logic, for example adding your own caching mechanism.

If you set the address in the `PreSearchRequestEvent`, the search methods will return the address object which will prevent doing an API call.
```php
class PostcodeAPISubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            PreSearchRequestEvent::NAME => 'onPreSearchRequestEvent',
        ];
    }

    public function onPreSearchRequestEvent(PreSearchRequestEvent $event): void
    {
        $cachedAddress = new Address();
        $cachedAddress->setCountry('Nederland');
        $cachedAddress->setCountryCode('nl');
        $cachedAddress->setCity('Amsterdam');

        $event->setAddress($cachedAddress);
    }
}
```

Additional features to be developed:
* Adding an optional caching layer to cache address responses (perhaps database)?
* Adding events before or after retrieving the response.

## Usage example
See the example below on how to use this package. Please note to always provide the provider as `{locale}.{Provider class}`.

```php
use Metapixel\PostcodeAPI\Provider\nl_NL\Pro6PP;

/** @var Pro6PP $provider */
$provider = ProviderFactory::create('nl_NL.Pro6PP');
$provider->setApiKey('YOUR_API_KEY');

// Optionally add an event subscriber or event listener.
$subscriber = new PostcodeAPISubscriber();
$provider->dispatcher->addSubscriber($subscriber);

$address = $provider->findByZipcodeAndHouseNumber('1068NM', '461');
```

### Search methods
```php
$provider = ProviderFactory::create('nl_NL.Pro6PP');
$provider->setApiKey('YOUR_API_KEY');

$provider->find('1068NM');
$provider->findByZipcode('1068NM');
$provider->findByZipcodeAndHousenumber('1068NM', '461');

// Using the more dynamic SearchRequest entity
$searchRequest = (new SearchRequest())
            ->setZipcode($zipcode)
            ->setHouseNumber($houseNumber);
            
$provider->findBySearchRequest($searchRequest);
```

If a search method is not supported, the `MethodNotSupportedException` is thrown, for example for Dutch addresses it is common that both zipcode and housenumber fields are required, so searching without housenumbers can throw a `MethodNotSupportedException` exception.

The `SearchRequest` entity is used for more dynamic Postcode API implementations which require more flexibility on which data is passed to the API.

## Donate

<a href="https://paypal.me/sndrenth/"><img src="https://raw.githubusercontent.com/andreostrovsky/donate-with-paypal/master/blue.svg" height="40"></a>  
If you enjoyed this project — or just feeling generous, consider buying me a beer. Cheers! :beers:
