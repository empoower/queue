# Queue test task
Create a small service which does the following:

Connects to AMQP (RabbitMQ) server (details below)
Listens on 'interest-queue' queue in default exchange for messages
For each message it calculates the "interest" and total sum by formula given below
Broadcast the new messages to 'solved-interest-queue' in the same exchange

### Installation
```sh
$ composer install
```
### launch the application
```sh
php main.php
```
