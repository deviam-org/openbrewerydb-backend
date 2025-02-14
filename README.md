## How to run the app

1. git clone https://github.com/deviam-org/openbrewerydb-backend.git

2. composer install

3. cp .env.example .env

4. sail up -d

5. sail artisan key:generate

6. sail artisan migrate --seed

