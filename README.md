# Webshippy refactoring exercise

## Install

1. Open a terminal
2. Clone the repository: `git clone git@github.com:jakabj16/webshippy.git`
3. Go to the project folder: `cd webshippy`
4. Copy the .env.example to the .env file: `cp .env.example .env`
5. Go to the .docker folder: `cd .docker`
6. Start docker: `docker-compose up --build -d` 
7. Go inside the docker: `docker exec -ti webshippy_app bash`
8. Run composer: `composer install`
9. Generate a key: `php artisan key:generate` 

## Usage

1. Open a terminal
2. Run the command: `docker exec -ti webshippy_app php artisan webshippy:get-fulfillable-orders '{"1":1,"2":2,"3":3}'`

The orders.csv is inside the project folder in storage/app 

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

## Run test

1. Open a terminal
2. Run the command: `docker exec -ti webshippy_app php artisan test --coverage`
