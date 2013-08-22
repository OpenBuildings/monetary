Monetary
========

Useful tool for formatting and converting currencies in PHP

[![Build Status](https://travis-ci.org/OpenBuildings/monetary.png?branch=master)](https://travis-ci.org/OpenBuildings/monetary)
[![Coverage Status](https://coveralls.io/repos/OpenBuildings/monetary/badge.png?branch=master)](https://coveralls.io/r/OpenBuildings/monetary?branch=master)
[![Latest Stable Version](https://poser.pugx.org/openbuildings/monetary/v/stable.png)](https://packagist.org/packages/openbuildings/monetary)

---

The main features are:
 - formatting an amount in a certain currency;
 - converting an amount from one currency to another.

The Monetary library takes care of fetching exchange rates from remote services and caching them.

You can easily use your own exchange rates source or cache solution.

Installation
------------

Install via [Composer](http://getcomposer.org)

``` bash
composer require openbuildings/monetary
```

Basic Usage
-----------

Use the namespace:

``` php
use OpenBuildings\Monetary\Monetary;
```

then just convert and format currencies:

``` php
echo Monetary::instance()->convert(10, 'USD', 'GBP');
// 7.5091987684914

echo Monetary::instance()->format(15.3, 'GBP');
// £15.30
```

