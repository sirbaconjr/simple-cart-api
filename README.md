## Cart API

### Description

This is a simple cart api application, the following operations are implemented:

* None

The following operations are pending:

* Add Items to Cart
* Get Cart information
* Update cart status
* Update cart items
* Create User
* Login User

### Schematics

The details of how the application works are provided in the [schematics](docs/schematics) folder.
All these can be opened on [Diagrams.net](https://diagrams.net). 

Here is a list of the available schemas: 

* [Actions](/docs/schematics/actions.drawio.xml): Detailed explanation of the actions that can be done on the system
* [Endpoints](/docs/schematics/endpoints.drawio.xml): Detailed explanation of how the actions are used to represent the API behaviour 

### Prerequisites

* php (v8.1)

### Setup

After cloning the application copy the .env.sample file

```shell
cp .env.sample .env
```

### Installing dependencies

```shell
composer install
```

### Running the application

```shell
composer start
```

With this the application will be available at [localhost:8080](http://localhost:8080)

### Running tests

```shell
composer test
```
