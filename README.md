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

__And then run the migrations__

```
php artisan migrate
```

__if database tables are not yet populated with data, run this command__

```
php artisan db:seed
```

__Then Run the migrations__

Grab the access_key by registering on `https://currencylayer.com`, and anywhere in the code write this line and enter your access key

```
OTIFSolutions\Laravel\Settings\Models\Setting::set('currency_layer_access_key', 'Your key goes here');
```


We won't be storing data in bulk of thousands of records (exchange rates). Set the number of days where data of how many days you want to keep  

```
OTIFSolutions\Laravel\Settings\Models\Setting::set('rates_save_days', numDays);
```
