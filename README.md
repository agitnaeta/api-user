# API Users 

## Pre-request
- PHP 8.2 

## How To run 
- set database on .env 
- run `composer install`
- run migration `php artisan:migrate`
- run seed `php artisan:db seed`
- `Important`: Email service using queue so you can also run `php artisan queue:work`
- To run the service, run `php artisan:serve`

## Note* Configure Email 
If you want to change sender, and sender name you can change on .env

MAIL_FROM_ADDRESS="hello@example.com"

MAIL_FROM_NAME="${APP_NAME}"


## Test Result 
- For testing query `http://localhost:8000/api/users?&page=2&sortBy=name&search=example`
- For input user
```
  curl --location 'http://localhost:8000/api/users' \
  --header 'Content-Type: application/x-www-form-urlencoded' \
  --data-urlencode 'email=agitnaeta@gmail.com' \
  --data-urlencode 'password=adminadmin' \
  --data-urlencode 'name=agi naeta'
