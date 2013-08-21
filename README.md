Monetary
========

Useful tool for formatting and converting currencies in PHP

[![Build Status](https://travis-ci.org/OpenBuildings/monetary.png?branch=master)](https://travis-ci.org/OpenBuildings/monetary)
[![Coverage Status](https://coveralls.io/repos/OpenBuildings/monetary/badge.png?branch=master)](https://coveralls.io/r/OpenBuildings/monetary?branch=master)
[![Latest Stable Version](https://poser.pugx.org/openbuildings/monetary/v/stable.png)](https://packagist.org/packages/openbuildings/monetary)

---

Installation
------------

Install via [Composer](http://getcomposer.org)

``` bash
composer require openbuildings/monetary
```

Usage
-----

Use the namespace:

``` php
use OpenBuildings\Monetary\Monetary;
```

then just convert and format currencies:

``` php
echo Monetary::convert(10, 'USD', 'GBP');
// 7.5091987684914

echo Monetary::format(15.3, 'GBP');
// £15.30
```

