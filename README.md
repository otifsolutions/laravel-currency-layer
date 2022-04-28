### Laravel Currency Layer

This Laravel package provides easy integration with Currency Layer Api along with complete list for countries with flag information. It also comes with command to auto sync rates for each currency 

__Requirements__

```PHP >= 7.4``` 

```Laravel >= 8.0```

__How to use the Library__


Install via Composer

__Using Composer (Recommended)__


```
composer require otifsolutions/laravel-currency-layer 
```

__And then run the migrations directly after installing package__

```
php artisan migrate
```

__if database tables are not yet populated with data, run the seeders first__

```
php artisan db:seed
```

Grab the access_key by registering on `https://currencylayer.com` here, give the key to the app by tinker or writing this line anywhere in the code

```
OTIFSolutions\Laravel\Settings\Models\Setting::set('crkey', 'yourAccessKey');
```


Set the number of days where data of how many days you want to keep :

```
OTIFSolutions\Laravel\Settings\Models\Setting::set('days_rates', numDays);
```
