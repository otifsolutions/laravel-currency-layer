### Laravel Currency Layer

This Laravel package provides easy integration with Currency Layer Api along with complete list for countries with flag information. It also comes with command to auto sync rates for each currency 

__Requirements__

```PHP >= 7.4``` 

```Laravel >= 8.0```

__How to use the Library__


Install via Composer

__Using Composer (Recommended)__


```
composer require otifsolutions/currency-layer 
```

__And then run the migrations directly after installing package__

```
php artisan migrate
```

__Then, run this command to seed/populate all tables__

```
php artisan run:seeders
```

Grab the access key by registering on `https://currencylayer.com`, give the key to the app by tinker or writing this line anywhere in the code

```
OTIFSolutions\Laravel\Settings\Models\Setting::set('crkey', 'yourAccessKey');
```

Set the number of days where data of how many days you want to keep :

```
OTIFSolutions\Laravel\Settings\Models\Setting::set('days_rates', numDays);
```

__After setting everything and using API key with the package, you can synchronize currency data__

Hit this command to fetch the currency rates
```
php artisan rates:get
```

Hit this command to remove the currency rates data
```
php artisan rates:delete
```
Note :
    Command `rates:delete` will only be fired when you already given the `days_rates` key