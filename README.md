## mth.blog
mth.blog is a simple blog app with nested,unlimited categories module, tinymce rich text editor and postviews(pageviews and unique visitors) etc.

### warning: there is no role/permission system in this app. anyone can register and reach administrator panel. So after installation, you may want to disable registration or use a role/permission module.

## Installation

- Install composer dependencies

```composer install```

- Install npm dependencies

```npm install```

- Copy .env.example and set up .env file (DB Settings etc.)

```cp .env.example .env```

- Generate laravel app encription key

```php artisan key:generate```

- Migrate the database

```php artisan migrate```

- Set up storage links

```php artisan storage:link```

After these steps, you can serve your app with following command;

```php artisan serve```

## License

This software is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
