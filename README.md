## Cart API

### Description

This is a simple cart api application, the following operations are implemented:

* Add Items to Cart
* Get Cart information
* Checkout cart
* CRUD for Product
* Create User
* Login User

### Schematics

The details of how the application works are provided in the [schematics](docs/schematics) folder.

Here is a list of the available schemas: 

* [Actions](/docs/schematics/actions.drawio.xml): Detailed explanation of the actions that can be done on the system. Can be opened on [Diagrams.net](https://diagrams.net).
* [Endpoints](/docs/api/cart-api.yaml): OpenAPI 3 specification of the endpoints 

### Prerequisites

* docker-compose (or simillar)

### Setup

After cloning the application copy the .env.sample file

```shell
cp .env.sample .env
```

The database and rabbitmq envs are already set for the docker-compose services.
Please remember to update the MAILER_DSN, so you can properly test the mailer service.

### Bringing up the application

```shell
docker-compose up -d
```

With this the application will be available at [localhost:8080](http://localhost:8080)

### Installing dependencies

Dependencies should already be installed when bringing up the container,
but if needed, they can be installed through the following command.

```shell
docker-compose exec app composer install
```

### Running email schedule listener

```shell
docker-compose exec app php bin/console.php app:listener:checkout-email-schedules
```

### Running tests

```shell
docker-compose exec app composer test
```
