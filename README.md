### Laravel Currency Layer

This Laravel package provides easy integration with Currency Layer Api along with complete list for countries with flag information. It also comes with command to auto sync rates for each currency 

__Requirements__

```PHP >= 7.4``` 

```Laravel >= 8.0```

__How to use the Library__

Install via Composer **[Composer](https://getcomposer.org/download)** (Recommended)

__Using Composer (Recommended)__

```
composer require otifsolutions/currency-layer
```

**Note :**

> This package works with database engine `myIsam`, if you are using `mysql` you have to change your database engine to `myIsam` from <b>config/database.php</b> -> `'connection' => 'mysql'` and make change `'engine' => 'myIsam'`

__Now, run the migrations__

```
php artisan migrate
```

__Then, run this command to populate all tables__

```
php artisan fill:tables
```

__Now follow the instructions__

Grab the access key by registering on **[CurrencyLayer](https://currencylayer.com)**, give the key to the app by tinker or writing this line anywhere in the code, where `yourAccessKey` is the key you've got from API

```
OTIFSolutions\Laravel\Settings\Models\Setting::set('crlKey', 'yourAccessKey');
```

Set the `numberOfDays` key, where this is the data of how many days you want to keep :

```
OTIFSolutions\Laravel\Settings\Models\Setting::set('daysRates', numberOfDays);
```

**Note:**

> To check what key you have set, try with `get` method like

```
OTIFSolutions\Laravel\Settings\Models\Setting::get('daysRates');
```

and

```
OTIFSolutions\Laravel\Settings\Models\Setting::get('crlKey');
```

If you have set the `crlKey` in the code, remove that line after first time execution.

__After setting all the things, you can now synchronize currency exchange rates data__

> Hit this command to fetch the exchange rates

```
php artisan rates:get
```

> Hit this command to remove the exchange rates
```
php artisan rates:delete
```

> Now publish countries flags from package to your project
```
php artisan publish:flags
```

**Note :**

> Commands `rates:delete` and `rates:get` can only be executed when you set the keys `crlKey` and `daysRates`