# VarDumper

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/2d527bfe23e64501a659c7bff1ce00db)](https://www.codacy.com/app/awesomite/var-dumper?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=awesomite/var-dumper&amp;utm_campaign=Badge_Grade)
[![Coverage Status](https://coveralls.io/repos/github/awesomite/var-dumper/badge.svg?branch=master)](https://coveralls.io/github/awesomite/var-dumper?branch=master)
[![Build Status](https://travis-ci.org/awesomite/var-dumper.svg?branch=master)](https://travis-ci.org/awesomite/var-dumper)

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/3a354f9a-85e6-47d6-aa9a-407ba293ad05/mini.png)](https://insight.sensiolabs.com/projects/3a354f9a-85e6-47d6-aa9a-407ba293ad05)

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

$range = range(1, 5);

$varDumper = new LightVarDumper();
$varDumper
    ->setMaxChildren(20)
    ->setMaxDepth(5)
    ->setMaxStringLength(400)
    ->setMaxLineLength(50);

$varDumper->dump(array($loremIpsum, $array, $range));
```

Output:

```text
array(3) {
    [0] =>
        string(768)
            › Lorem ipsum dolor sit amet, consectetur adipiscing
            ›  elit. Proin nibh augue, suscipit a, scelerisque s
            › ed, lacinia in, mi. Cras vel lorem. Etiam pellente
            › sque aliquet tellus. Phasellus pharetra nulla ac d
            › iam. Quisque semper justo at risus.
            › Donec venenatis, turpis vel hendrerit interdum, du
            › i ligula ultricies purus, sed posuere libero dui i
            › d orci. Nam congue, pede vitae dapibus aliquet, el
            › it magna vulpu...
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
    [2] => array(5) {1, 2, 3, 4, 5}
}
```

**Note**

Use method `getDump()` instead of `dump()` for saving output as variable.

## Installation

`composer require awesomite/var-dumper`

## Versioning

The version numbers follow the [Semantic Versioning 2.0.0](http://semver.org/) scheme.

## Examples

[Examples](examples)
