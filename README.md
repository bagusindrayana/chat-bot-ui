
## Chat Bot (AI) UI
build with [Laravel](https://laravel.com) and [NativePHP](https://nativephp.com) for desktop, compatible with service like Open AI Api or another custom/proxy Api that have same response format.

## Design
- tailwind css
- https://taildashboards.com/get/tailchat
- fontawesome
- tom select
- alpine js

## Development
- `composer install`
- `npm install`
- copy `.env.example` to `.env` and fill `APP_NAME`
- `php artisan key:generate`
- `php artisan native:install`
- `php artisan native:migrate --seed`
- `php artisan native:serve`