# Setup laravel #

## Clone source code from github ##

1. Clone source
   ```
    git clone git@github.com:BAP-PNV/sis-team-1-back-end.git laravel

    cd laravel
   ```
2. Start docker-compose
    ```
    docker-compose up -d --build
    ```
3. Setup laravel
   ```
   cp .env.example .env
   ```
   add this content
   ```
    DB_CONNECTION=mysql
    DB_HOST=db
    DB_PORT=3306
    DB_DATABASE=laravel_web
    DB_USERNAME=laraveldocker
    DB_PASSWORD=laraveldocker123
   ```
4. Access to app 
   ```
    docker exec -it app bash
   ```
   generate new key
   ```
    php artisan key:generate
   ```
   open browser and test
   ![alt text](assets/Screenshot%20from%202022-12-17%2010-20-27.png)
   If this error
   ```
    docker exec -it app bash
    php artisan config:cache
   ```
   open browser at:
   ```
    http://localhost/
   ```
   It work
   ![alt text](assets/Screenshot%20from%202022-12-17%2010-24-58.png)
