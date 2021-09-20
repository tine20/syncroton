[![Build Status](https://github.com/tine20/zendframework1/actions/workflows/php-unit-test.yml/badge.svg)]

# Syncroton

Syncroton is a PHP based implementation of the [Exchange ActiveSync](http://en.wikipedia.org/wiki/Exchange_ActiveSync) protocol, which is licensed under the LGPL V3.

## Supported devices
* iOS based devices
* Android based devices which use the stock Android Exchange client
* Samsung Android based devices
* HTC Android based devices
* Windows Mobile phones
* Nokia Mail for Exchange
* Microsoft Outlook (>= Outlook 2013)

## Which projects are using Syncroton?
Following projects are using Syncroton to provide synchronisation of contacts, events, tasks and emails to their users.

* [Tine 2.0](http://www.tine20.org)
* [Kolab](http://www.kolab.org/)

## Getting Syncroton
Syncroton is available via Composer. Install Composer and create a file called composer.json in the main directory of your project:

    {
       "require": {
           "syncroton/syncroton": "1.*"
       }
    }

Next execute following command in the main directory of your project to download Syncroton:

/path/to/composer update
This command will download all needed files and places them in the vendor directory.

Now you just have to include the Composer autoloader in your code and your are ready to use Syncroton.

require 'vendor/autoload.php';
