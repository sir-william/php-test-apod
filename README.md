Requirements
------------

* PHP 7.2.5 or higher;
* and the [usual Symfony application requirements][1].

Installation
------------

Run this command:

```bash
cd php-test-apod
$ composer install
```
* set NASA_API_KEY in your .env file
* set these Google API Keys (OAUTH_GOOGLE_CLIENT_ID & OAUTH_GOOGLE_CLIENT_SECRET) in your .env file
* set authorized redirect_uri in your console.developers.google to http://127.0.0.1:8000/connect/google/check



Usage
-----

Executed each day to fetch the picture of the day ,run command:

```bash
$ php bin/console app:run:picture
```

There's no need to configure anything to run the application. If you have
Symfony binary, run this command:

```bash
$ symfony serve
```

Then access the application in your browser at the given URL (<http://127.0.0.1:8000> by default).

If you don't have the Symfony binary installed, run:

```bash
$ php -S localhost:8000 -t public/
```

[1]: https://symfony.com/doc/current/reference/requirements.html
# php-test-apod
