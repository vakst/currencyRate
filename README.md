Currency rate viewer
======================

Import currency rate from external resources.

Installation
---------

1) If you don't have Composer yet, just run the following command:

```bash
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer 
```

2) Checkout project from GitHub

```bash
git clone https://github.com/vakst/currencyRate.git ./
```

3) Use the "composer install" command to install dependencies

```bash
composer install
```

4) Check DB settings in

```
app/config/parameters.yml
```

5) Run migration

```bash
php bin/console doctrine:migrations:migrate 1
```

Launch Daemon
---------

```bash
bin/console currencyRate:update EUR/RUB --no-debug
bin/console currencyRate:update USD/RUB --no-debug
```

Memory usage and leaks
---------
To detect memory leaks run command

```bash
bin/console currencyRate:update EUR/RUB -e prod --no-debug --detect-leaks
```