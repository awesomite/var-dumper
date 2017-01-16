# VarDumper

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/2d527bfe23e64501a659c7bff1ce00db)](https://www.codacy.com/app/awesomite/var-dumper?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=awesomite/var-dumper&amp;utm_campaign=Badge_Grade)
[![Coverage Status](https://coveralls.io/repos/github/awesomite/var-dumper/badge.svg?branch=master)](https://coveralls.io/github/awesomite/var-dumper?branch=master)
[![Build Status](https://travis-ci.org/awesomite/var-dumper.svg?branch=master)](https://travis-ci.org/awesomite/var-dumper)

## Usage

```php
<?php

use Awesomite\VarDumper\LightVarDumper;

$varDumper = new LightVarDumper();
$varDumper
    ->setMaxChildren(20)
    ->setMaxDepth(5)
    ->setMaxStringLength(200);

$varDumper->dump($GLOBALS);
```

## Installation

`composer require awesomite/var-dumper`

## Versioning

The version numbers follow the [Semantic Versioning 2.0.0](http://semver.org/) scheme.
[Read more](DOCUMENTATION.md#backward-compatibility) about backward compatibility.sts.