# Dolumar
Play now at http://www.dolumar.com/

## Requirements
- PHP 8.1 or higher
- MySQL/MariaDB database
- Composer 2.x (see UPGRADE_PHP8.md for details)
- Memcached extension
- Required PHP extensions: bcmath, gd, mbstring, memcached

## Quick start
First fork the project (so that you can make changes), then click the button below:

[![Deploy](https://www.herokucdn.com/deploy/button.svg)](https://heroku.com/deploy)

This will setup a Heroku project with all required extensions installed.

## Setup
Dolumar has a few requirements, listed in composer.json. It requires PHP 8.1+ and Composer 2.x.

**Note**: Due to Composer 2.x no longer supporting PEAR repositories, special care must be taken during installation. Please see [UPGRADE_PHP8.md](UPGRADE_PHP8.md) for detailed instructions.

We have optimized Dolumar so that it can easily run on a free heroku daemon, as long as you don't have too much players. It is, however, a php web application, so you can easily run it on any other server.

## Local setup
For a local (development) setup, make sure you have composer installed.

**Important**: See [UPGRADE_PHP8.md](UPGRADE_PHP8.md) for installation instructions due to PEAR package compatibility issues with Composer 2.x.

Simply run `composer install` to install all dependencies (may require Composer 1.x workaround).

## Set up SMTP server
You need to set SMTP credentials in order to get the email validation working. Following instructions will get it up and 
running with mandrill, but you can use any SMTP server. 

In heroku, set:
* EMAIL_SMTP_SERVER: smtp.mandrillapp.com
* EMAIL_SMTP_SECURE: tls
* EMAIL_SMTP_PORT: 587
* EMAIL_SMTP_USERNAME: abc
* EMAIL_SMTP_PASSWORD: abc

Optionally, you can also set:
* AIRBRAKE_TOKEN (airbrake api token, to gather errors)
* SERVERLIST_URL (api that keeps track of all your servers)
* CREDITS_GAME_TOKEN (your game token on the catlab credits framework)
* CREDITS_PRIVATE_KEY (your private key to access catlab credits framework)

## CatLab Credits
If you want to offer paid features on your server, you will need to setup an account on the CatLab credits framework. 
Contact us at support@catlab.be in order to get you up and running. Or you can write your own payment gateway :-)