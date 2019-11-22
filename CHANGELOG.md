# Changelog

## [1.5.0] - 2019-11-22

Symfony 5.0 has been supported.

## [1.4.0] - 2019-11-21

Output format of printed `Closure` has been changed. New format:

```
object(Closure) #3 {[
    [name] =>      “{closure}”
    [filename] =>  “(...)/var-dumper/examples/closure.php”
    [startLine] => 18
    [endLine] =>   19
    [use] =>       array(2) {[x] => 5, [y] => 6}
]}

```

## [1.3.0] - 2019-11-16

* Internal refactor, do not call `ob_*` functions
* Added dedicated dumpers for `Closure` and `Throwable`
* Added the following methods (default value for both of them is equal to `3`):
  * `Awesomite\VarDumper\InternalVarDumper::setMaxFileNameDepth`
  * `Awesomite\VarDumper\LightVarDumper::setMaxFileNameDepth`

## [1.2.1] - 2018-10-01

Function [`ini_set`](http://php.net/manual/en/function.ini-set.php) expects second argument as string,
changed `ini_set('xdebug.overload_var_dump', 0)` to `ini_set('xdebug.overload_var_dump', '0')`.

## [1.2.0] - 2018-09-29

Added support for `use` keyword in anonymous functions, e.g.:

```php
<?php

use Awesomite\VarDumper\LightVarDumper;

$x = 5;
$y = 6;

$fn = function () use ($x, $y) {
};

$dumper = new LightVarDumper();
$dumper->dump($fn);
```

```
object(Closure) #1 (5) {
    $name =>      “{closure}”
    $filename =>  “(...)/file.php”
    $startLine => 8
    $endLine =>   9
    $use =>       array(2) {[x] => 5, [y] => 6}
}
```

## [1.1.0] - 2018-09-14

* Added [`Awesomite\VarDumper\SymfonyVarDumper`](./src/SymfonyVarDumper.php)
* Refactor - all `@internal` classes have been marked as `final` whenever it was possible

## [1.0.3] - 2018-09-13

Fixed bug for HHVM - [`spl_object_id`](http://php.net/manual/en/function.spl-object-id.php)
returns the same value for two different objects,
use [`spl_object_hash`](http://php.net/manual/en/function.spl-object-hash.php) instead of.

## [1.0.2] - 2018-09-10

Refactor, external API has not changed.

## [1.0.1] - 2018-04-21

* Use [`get_object_vars`](http://php.net/manual/en/function.get-object-vars.php)
instead of [`ReflectionObject::getProperties`](http://php.net/manual/en/reflectionclass.getproperties.php)
whenever object contains method [`__get`](http://php.net/manual/en/language.oop5.overloading.php#object.get)
* In some cases method `LightVarDumper::dump` didn't work properly, because the following code triggers
error `Notice: Undefined property: Foo::$a` in PHP ^7.0, fixed issue:

```php
<?php

class Foo
{
    private $a = 'a';

    public function __construct()
    {
        unset($this->a);
    }
}

$obj = new Foo();
$reflectionObj = new ReflectionObject($obj);
$reflectionProp = $reflectionObj->getProperty('a');
$reflectionProp->setAccessible(true);
$reflectionProp->getValue($obj);
```

## [1.0.0] - 2018-01-10

This version contains the same source code as [0.12.0].

[1.4.0]: https://github.com/awesomite/var-dumper/compare/v1.3.0...v1.4.0
[1.3.0]: https://github.com/awesomite/var-dumper/compare/v1.2.1...v1.3.0
[1.2.1]: https://github.com/awesomite/var-dumper/compare/v1.2.0...v1.2.1
[1.2.0]: https://github.com/awesomite/var-dumper/compare/v1.1.0...v1.2.0
[1.1.0]: https://github.com/awesomite/var-dumper/compare/v1.0.3...v1.1.0
[1.0.3]: https://github.com/awesomite/var-dumper/compare/v1.0.2...v1.0.3
[1.0.2]: https://github.com/awesomite/var-dumper/compare/v1.0.1...v1.0.2
[1.0.1]: https://github.com/awesomite/var-dumper/compare/v1.0.0...v1.0.1
[1.0.0]: https://github.com/awesomite/var-dumper/tree/v1.0.0
[0.12.0]: https://github.com/awesomite/var-dumper/tree/v0.12.0
