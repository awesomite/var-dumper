# VarDumper

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/2d527bfe23e64501a659c7bff1ce00db)](https://www.codacy.com/app/awesomite/var-dumper?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=awesomite/var-dumper&amp;utm_campaign=Badge_Grade)
[![Coverage Status](https://coveralls.io/repos/github/awesomite/var-dumper/badge.svg?branch=master)](https://coveralls.io/github/awesomite/var-dumper?branch=master)
[![Build Status](https://travis-ci.org/awesomite/var-dumper.svg?branch=master)](https://travis-ci.org/awesomite/var-dumper)

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/3a354f9a-85e6-47d6-aa9a-407ba293ad05/mini.png)](https://insight.sensiolabs.com/projects/3a354f9a-85e6-47d6-aa9a-407ba293ad05)

## Why?

To set limit size of output of printed variable.

## Usage

```php
<?php

use Awesomite\VarDumper\LightVarDumper;
use Awesomite\VarDumper\Dumper;

$varDumper = new LightVarDumper();
$varDumper
    ->setMaxChildren(20)
    ->setMaxDepth(5)
    ->setMaxStringLength(200);

$varDumper->dump($GLOBALS);

// or easier way
Dumper::dump($GLOBALS);
```

## Installation

`composer require awesomite/var-dumper`

## Versioning

The version numbers follow the [Semantic Versioning 2.0.0](http://semver.org/) scheme.
