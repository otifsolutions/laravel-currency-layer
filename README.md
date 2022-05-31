### Laravel Currency Layer

This Laravel package provides easy integration with Currency Layer Api along with complete list for countries with flag information. It also comes with command to auto sync rates for each currency 

__Requirements__

```PHP >= 7.4``` 

```Laravel >= 8.0```

__How to use the Library__

Install via  **[Composer](https://getcomposer.org/download)** (Recommended)

__Using Composer__

```
composer require otifsolutions/currency-layer
```

**Note :**

> This package works with database engine `myIsam`. If you are using `mysql database` then you have to change database engine to `myIsam` from <b>config/database.php</b> -> `'connection' => 'mysql'` and make this change `'engine' => 'myIsam'`

__Now, run the migrations__

```
php artisan migrate
```

__Then, run this command to populate all tables with countries, states, currencies etc data__

```
php artisan fill:tables
```

__Now follow the instructions below__

Grab the access key by registering on **[CurrencyLayer](https://currencylayer.com)**, give the key to the app using `artisan tinker` or by writing this line anywhere in the code, where `yourAccessKey` (string) is the key you've got from the API

```php
OTIFSolutions\Laravel\Settings\Models\Setting::set('crlKey', 'yourAccessKey');
```

Set the `numberOfDays` key (positive integer), where this is the data of how many days you want to keep:

```php
OTIFSolutions\Laravel\Settings\Models\Setting::set('daysRates', numberOfDays);
```

**Note:**

> To check what key you have set, try with `get` method like

```php
OTIFSolutions\Laravel\Settings\Models\Setting::get('daysRates');
```

and

```php
OTIFSolutions\Laravel\Settings\Models\Setting::get('crlKey');
```

If you have set the `crlKey` somewhere in the code, remove that line
after first time execution. To reset or re-assign the key, you can use the same line.
But, it is recommended to use tinker to set the access key.


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



__Relationships defined between Models__

Model <b>Country</b> has `OneToOne` relation and `Currency`, `ManyToMany` relation with `Timezone` and `OneToMany` relation with `State`.


<b>State</b> Model has `OneToMany` relation with `City`


<b>Currency</b> Model has `OneToMany` relation with `CurrencyRate`