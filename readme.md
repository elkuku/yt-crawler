# Symfony Playground "one"
lalala
![screen-playground-one](https://user-images.githubusercontent.com/33978/103650387-edff8280-4f2d-11eb-84c8-486662e25bd5.png)

* Symfony 5.2.*
* Docker compose file for PostgreSQL
* `dev` login form <br/> `prod` google login
* JQuery, Bootstrap and [Bootswatch](https://bootswatch.com/)
* Prepared for Heroku

## Usage

1. `composer install`
1. `yarn`
1. `yarn dev`
1. `symfony console doctrine:migrations:migrate`
1. `bin/start` - a custom startup script that runs `docker-compose up`, `symony server:start` and `symfony open:local`

Use `symfony console user-admin` to create an admin user.

Happy coding `=;)`
