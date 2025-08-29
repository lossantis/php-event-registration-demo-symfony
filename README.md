# Randstand Symfony Assessment

## General info
### Introduction
The goal of this assignment is to develop a small Symfony application that collects event registrations for the annual company wide Randstad Digital family event.
Every year, Randstad Digital organizes a family event for all employees and their plus one and kids. 
The employees should be able to register for this event in a user friendly way.

### Optional / extra:
1. To prevent letting one person handle all registrations, every department has its own manager responsible for the registrations of his/her department.
   The department will have to be captured when submitting the registration form along with the preferences for the event.
2. Send a confirmation link to the provided email address to confirm their attendance. 

### Requirements
* [Coding standards](https://symfony.com/doc/current/contributing/code/standards.html)
* Organization of business logic
* [Security (Authentication, prevention of XSS and SQL injection etc)](https://symfony.com/bundles/NelmioSecurityBundle/current/index.html#xss-protection)
* Internationalization and localization (write code in English, provide translations for other languages if possible)

## Pre-requisites
- [Docker](https://docs.docker.com/get-docker/)
* [DDev](https://docs.ddev.com/en/stable/users/quickstart/#symfony)

## Starting/Stoping the project
* Run `ddev start` to spin up the project.
* Run `ddev launch` to open your project in a browser.
* Run `ddev stop` to stop the project.`

## Run migrations
```shell
  ddev php bin/console doctrine:migrations:migrate -n
```

### Project endpoint
(https://randstad.ddev.site)[https://randstad.ddev.site]

### Mailer endpoint
(https://randstad.ddev.site:8026/)[https://randstad.ddev.site:8026/]

Configure default sender address (required by some SMTP servers):
- Set MAIL_FROM_ADDRESS in your environment (e.g. in .env.local):

```
MAIL_FROM_ADDRESS=no-reply@randstad.ddev.site
MAILER_DSN="smtp://mailpit:1025"
```
## CI/CD
### Rector instantly upgrades and refactors the PHP code of your application
```shell
    ddev exec -s web ./vendor/bin/rector process
```

### PHP-CS-Fixer automatically fixes your code to follow the coding standards
```shell
    ddev exec -s web ./vendor/bin/php-cs-fixer fix src
```

## Utils
### Symfony commands
* Clear cache
```shell
  ddev php bin/console cache:clear
```

## Contributing
Pull requests are welcome. For major changes, please open an issue first
to discuss what you would like to change.

Please make sure to update tests as appropriate.

## License
[MIT](https://choosealicense.com/licenses/mit/)
