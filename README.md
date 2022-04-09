# Webshippy refactoring exercise

## Install

1. Open a terminal
2. Go to the project folder: `cd /var/www/webshippy`
3. Copy the .env.example to the .env file: `cp .env.example .env`
4. Go to the .docker folder: `cd .docker`
5. Start docker: `docker-compose up --build -d` 
6. Go inside the docker: `docker exec -ti webshippy_app bash`
7. Run composer: `composer install`
8. Generate a key: `php artisan key:generate` 

## Usage

1. Open a terminal
2. Run the command: `docker exec -ti webshippy_app php artisan webshippy:get-fulfillable-orders`

## Examples

```
docker exec -ti webshippy_app php artisan webshippy:get-fulfillable-orders '{"1":2,"2":3,"3":1}'
product_id          quantity            priority            created_at          
================================================================================
1                   2                   high                2021-03-25 14:51:47 
2                   1                   medium              2021-03-21 14:00:26 
3                   1                   medium              2021-03-22 12:31:54 
2                   2                   low                 2021-03-24 11:02:06 
1                   1                   low                 2021-03-25 19:08:22 
```
