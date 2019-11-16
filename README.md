# VarDumper

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/2d527bfe23e64501a659c7bff1ce00db)](https://www.codacy.com/app/awesomite/var-dumper?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=awesomite/var-dumper&amp;utm_campaign=Badge_Grade)
[![Coverage Status](https://coveralls.io/repos/github/awesomite/var-dumper/badge.svg?branch=master)](https://coveralls.io/github/awesomite/var-dumper?branch=master)
[![Build Status](https://travis-ci.org/awesomite/var-dumper.svg?branch=master)](https://travis-ci.org/awesomite/var-dumper)

## Why?

To set limit size of printed variable and produce more readable output than built-in [`var_dump`](http://php.net/manual/en/function.var-dump.php) function.

## Usage

```php
<?php

use Awesomite\VarDumper\LightVarDumper;

$loremIpsum = <<<'LOREM_IPSUM'
Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin nibh augue, suscipit a, scelerisque sed, lacinia in, mi. Cras vel lorem. Etiam pellentesque aliquet tellus. Phasellus pharetra nulla ac diam. Quisque semper justo at risus.
Donec venenatis, turpis vel hendrerit interdum, dui ligula ultricies purus, sed posuere libero dui id orci. Nam congue, pede vitae dapibus aliquet, elit magna vulputate arcu, vel tempus metus leo non est.
Etiam sit amet lectus quis est congue mollis. Phasellus congue lacus eget neque. Phasellus ornare, ante vitae consectetuer consequat, purus sapien ultricies dolor, et mollis pede metus eget nisi.
Praesent sodales velit quis augue. Cras suscipit, urna at aliquam rhoncus, urna quam viverra nisi, in interdum massa nibh nec erat.
LOREM_IPSUM;

$array = array(
    'a' => 'a',
    'ab' => 'ab',
    'abc' => 'abc',
    'abcd' => 'abcd',
    'abcde' => 'abcde',
    'abcdef' => 'abcdef',
    'abcdefg' => 'abcdefg',
    'abcdefgh' => 'abcdefgh',
);

$smallArray = array(1, 2.5, null, M_PI, INF);

$varDumper = new LightVarDumper();
$varDumper
    ->setMaxChildren(20)
    ->setMaxDepth(5)
    ->setMaxStringLength(400)
    ->setMaxLineLength(50)
    ->setIndent('    ');

$varDumper->dump(array($loremIpsum, $array, $smallArray));
```

Output:

```text
array(3) {
    [0] =>
        string(768)
            › Lorem ipsum dolor sit amet, consectetur adipiscing
            ›  elit. Proin nibh augue, suscipit a, scelerisque
            › sed, lacinia in, mi. Cras vel lorem. Etiam
            › pellentesque aliquet tellus. Phasellus pharetra
            › nulla ac diam. Quisque semper justo at risus.↵
            › Donec venenatis, turpis vel hendrerit interdum,
            › dui ligula ultricies purus, sed posuere libero dui
            ›  id orci. Nam congue, pede vitae dapibus aliquet,
            › elit magna vulpu...
    [1] =>
        array(8) {
            [a] =>        “a”
            [ab] =>       “ab”
            [abc] =>      “abc”
            [abcd] =>     “abcd”
            [abcde] =>    “abcde”
            [abcdef] =>   “abcdef”
            [abcdefg] =>  “abcdefg”
            [abcdefgh] => “abcdefgh”
        }
    [2] => array(5) {1, 2.5, NULL, M_PI, INF}
}
```

**Note**

Use method `dumpAsString()` instead of `dump()` for saving output as variable.

## Installation

`composer require awesomite/var-dumper`

## Examples

[See](examples) all examples.

### Exception with stack trace

[Source](examples/exception.php)

```
object(DivideByZeroException) #4 {[
    [message] =>  “Cannot divide by zero”
    [code] =>     0
    [file] =>     “(...)/examples/exception.php:31”
    [previous] => NULL
    [trace] =>
        1. (...)/examples/exception.php:48 Divider->divide(
            a: 5
            b: 0
        )
        2. (...)/examples/exception.php:55 Calculator::execute(
            action:  “divide”
            numberA: 5
            numberB: 0
        )
]}
```

### Simple array

[Source](examples/simple-array.php)

```php
<?php

use Awesomite\VarDumper\LightVarDumper;

$dumper = new LightVarDumper();
$dumper->dump(\range(1, 5));
```

```
array(5) {1, 2, 3, 4, 5}
```

### Closure

[Source](examples/closure.php)

```php
<?php

use Awesomite\VarDumper\LightVarDumper;

$firstName = 'Mary';
$lastName = 'Watson';
$function = function ($a, $b) use ($firstName, $lastName) {
};

$dumper = new LightVarDumper();
$dumper->dump($function);
```

```
object(Closure) #3 {[
    [name] =>      “{closure}”
    [filename] =>  “(...)/var-dumper/examples/closure.php”
    [startLine] => 7
    [endLine] =>   8
    [use] =>
        array(2) {
            [firstName] => “Mary”
            [lastName] =>  “Watson”
        }
]}
```

### Predefined constants

[Source](examples/predefined-constants.php)

```php
<?php

use Awesomite\VarDumper\LightVarDumper;

$dumper = new LightVarDumper();
$dumper->dump(array(
    \M_LOG2E,
    \PHP_INT_MAX,
    \M_PI,
));

```

```
array(3) {M_LOG2E, PHP_INT_MAX, M_PI}
```

## Versioning

The version numbers follow the [Semantic Versioning 2.0.0](http://semver.org/) scheme.

**Note**

Only source code is considered as backward compatible, result of `dump()` and `dumpAsString()` methods may change.
Classes, methods, functions and properties marked as `@internal` may change any time,
promise of backward compatibility excludes them, do not use them.

## License

This library is released under the [MIT license](LICENSE).
