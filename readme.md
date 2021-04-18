# Symfony Playground "one"

![screen-playground-one](https://user-images.githubusercontent.com/33978/103650387-edff8280-4f2d-11eb-84c8-486662e25bd5.png)

* Symfony 5.2.*
* Docker compose file for PostgreSQL
* `dev` login form <br/> `prod` google login
* JQuery, Bootstrap and [Bootswatch](https://bootswatch.com/)
* Prepared for Heroku
* Likes PHP 8 ;)

## Usage

0. Use the `bin/init` command **OR** execute the steps below

1. `symfony composer install`
1. `yarn`
1. `yarn dev`
1. `bin/start` - a custom startup script that runs `docker-compose up`, `symony server:start` and `symfony open:local`
1. `symfony console doctrine:migrations:migrate`
1. Use `symfony console user-admin` to create an admin user.

NOTE: ATM The symfony binary is used to ensure PHP 8!

### Testing

```shell
make tests
```

Happy coding `=;)`
