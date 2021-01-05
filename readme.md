# Symfony Playground "one"

![screen-playground-one](https://user-images.githubusercontent.com/33978/103650052-70d40d80-4f2d-11eb-9fe8-9f72e8ded087.png)

* Symfony 5.2.*
* Docker compose file for PostgreSQL
* dev login form - prod google login
* JQuery, Bootstrap and Bootswatch
* Prepared for Heroku

## Usage

1. `composer install`
1. `yarn`
1. `yarn dev`
1. `symfony console doctrine:migrations:migrate`
1. `bin/start` - a custom startup script that runs `docker-compose up`, `symony server:start` and `symfony open:local`

Use `symfony console user-admin` to create an admin user.

Happy coding `=;)`
