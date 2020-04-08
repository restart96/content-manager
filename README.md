# content-manager

If it is installed for the first time, run the following:
```bash
php artisan vendor:publish --provider="Restart\ContentManager\App\Providers\ContentManagerServiceProvider" --force
```
```bash
php artisan migrate
```
If you have an update, run the following:
```bash
php artisan vendor:publish --provider="Restart\ContentManager\App\Providers\ContentManagerServiceProvider" --tag=assets --force
```
