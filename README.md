# Dolumar
Play now at http://www.dolumar.com/

## Requirements
- PHP 8.1 or higher
- MySQL/MariaDB database
- Memcached extension (optional, for improved caching)
- Required PHP extensions: bcmath, gd, mbstring, pdo, pdo_mysql

## Setup
Dolumar is a standalone PHP web application that can be run on any server with the required dependencies. No external package manager (composer) is required - all dependencies are included in the repository.

## Local setup
For a local (development) setup, ensure you have PHP 8.1+ and a MySQL/MariaDB database.

Simply clone the repository and configure your web server to point to the `public/` directory.

## Set up SMTP server
You need to set SMTP credentials in order to get the email validation working. You can use any SMTP server by setting the following environment variables in a `.env` file:

* EMAIL_SMTP_SERVER: your SMTP server (e.g., smtp.example.com)
* EMAIL_SMTP_SECURE: tls
* EMAIL_SMTP_PORT: 587
* EMAIL_SMTP_USERNAME: your username
* EMAIL_SMTP_PASSWORD: your password

Optionally, you can also set:
* SERVERLIST_URL (api that keeps track of all your servers)
* CREDITS_GAME_TOKEN (your game token on the catlab credits framework)
* CREDITS_PRIVATE_KEY (your private key to access catlab credits framework)

## CatLab Credits
If you want to offer paid features on your server, you will need to setup an account on the CatLab credits framework. 
Contact us at support@catlab.be in order to get you up and running. Or you can write your own payment gateway :-)