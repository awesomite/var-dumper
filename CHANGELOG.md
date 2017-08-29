# Changelog

## 0.8.0 (????-??-??)

* Do not display values of private properties in parent class - it can cause unexpected error,
because it can call __call() function
* Use __debugInfo() whenever it is possible

## 0.7.2 (2017-07-20)

* Fixed bug - `ReflectionProperty::getValue` can throw an exception

## 0.7.1 (2017-06-18)

* Removed support for PHPUnit ^5.0 and ^6.0 - there are issue with HHVM - `Exception` does not implement `Throwable` in HHVM
* Fixed bug "Undefined property: ClassName::$propertyName" - property can be defined in code, but removed in runtime e.g.:

```php
class Foo
{
    private $property = 'value';

    public function removeProperty()
    {
        unset($this->property);
    }
}
```

## 0.7.0 (2017-06-06)

* Added pretty dump for `Closure`

## 0.6.3 (2017-07-20)

* Fixed bug - `ReflectionProperty::getValue` can trigger an error or throw an exception

## 0.6.2 (2017-05-05)

* Changed `.gitattributes` - do not remove whole `/bin` directory

## 0.6.1 (2017-05-04)

* Removed invalid doc comment

## 0.6.0 (2017-05-04)

* Support for PHPUnit ^5.7 and ^6.1

## 0.5.3 (2017-07-20)

* Fixed bug - `ReflectionProperty::getValue` can trigger an error or throw an exception

## 0.5.2 (2017-05-04)

* Fixed bug - bracket was placed in wrong place

## 0.5.1 (2017-04-11)

* Fixed bug - report could not be sent to [coveralls.io](https://coveralls.io/),
because there is not a git repository in project directory (https://travis-ci.org/awesomite/var-dumper/jobs/221119856)

## 0.5.0 (2017-04-11)

* Added `examples` directory
* Class `Awesomite\VarDumper\Dumper` is useless and has been removed
* Class `Awesomite\VarDumper\Objects\Hasher` has been added.
Human friendly id (based on [`spl_object_hash()`](http://php.net/manual/en/function.spl-object-hash.php))
will be added to dump of object, e.g.:
```
object(stdClass) #1 (0) {
}
```
instead of
```
object(stdClass) (0) {
}
```

## 0.4.1 (2017-07-20)

* Fixed bug - `ReflectionProperty::getValue` can trigger an error or throw an exception

## 0.4.0 (2017-04-04)

* Providers for `Awesomite\VarDumper\LightVarDumperTest` have been
moved to separated classes.
* `Awesomite\VarDumper\LightVarDumper` will not print space
after `=>` anymore (e.g. output `=> ` has been changed to `=>`)

## 0.3.2 (2017-07-20)

* Fixed bug - `ReflectionProperty::getValue` can trigger an error or throw an exception

## 0.3.1 (2017-02-02)

Fixed bug in line `src/InternalVarDumper.php:43` - should be `version_compare(PHP_VERSION, '5.3.6') >= 0`
instead of `version_compare(PHP_VERSION, '5.3.6') >= DEBUG_BACKTRACE_IGNORE_ARGS`

## 0.3.0 (2017-02-02)

* New way of displaying too deep arrays:
```
array(7) {
  ...
}
```

## 0.2.1 (2017-07-20)

* Fixed bug - `ReflectionProperty::getValue` can trigger an error or throw an exception

## 0.2.0 (2017-01-18)

* Added `\Awesomite\VarDumper\Dumper`
* Added arguments `$displayPlaceInCode` and `$stepShift` to
`\Awesomite\VarDumper\InternalVarDumper::__construct`
and `\Awesomite\VarDumper\LightVarDumper::__construct`

## 0.1.2 (2017-07-20)

* Fixed bug - `ReflectionProperty::getValue` can trigger an error or throw an exception

## 0.1.1 (2017-01-16)

* Missing **#intrnal** tags

## 0.1.0 (2017-01-16)

* Initial public release
