# Setup laravel #

## Clone source code from github ##

1. Clone source
   ```
    git clone git@github.com:BAP-PNV/sis-team-1-back-end.git laravel

    cd laravel
   ```
2. Install vender by compose
   ```
   docker run --rm -v $(pwd):/app composer install
   ```
3. Start docker-compose
    ```
    docker-compose up -d --build
    ```
4. Up date permission
   ```
   cd ../
   sudo chown -R $USER:$USER laravel
   cd laravel
   ```
5. Setup laravel
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
6. Access to app 
   ```
    docker exec -it app bash
   ```
   generate new key[README.md](README.md)
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
7. {{ route('cars.create') }}
