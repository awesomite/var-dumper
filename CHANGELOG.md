# Changelog

## 0.6.3

* Fixed bug - `ReflectionProperty::getValue` can trigger error or throw an exception

## 0.6.2

* Changed `.gitattributes` - do not remove whole `/bin` directory

## 0.6.1

* Removed invalid doc comment

## 0.6.0

* Support for PHPUnit ^5.7 and ^6.1

## 0.5.2

* Fixed bug - bracket was placed in wrong place

## 0.5.1

* Fixed bug - report could not be sent to [coveralls.io](https://coveralls.io/),
because there is not a git repository in project directory (https://travis-ci.org/awesomite/var-dumper/jobs/221119856)

## 0.5.0

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

## 0.4.0

* Providers for `Awesomite\VarDumper\LightVarDumperTest` have been
moved to separated classes.
* `Awesomite\VarDumper\LightVarDumper` will not print space
after `=>` anymore (e.g. output `=> ` has been changed to `=>`)

## 0.3.1

Fixed bug in line `src/InternalVarDumper.php:43` - should be `version_compare(PHP_VERSION, '5.3.6') >= 0`
instead of `version_compare(PHP_VERSION, '5.3.6') >= DEBUG_BACKTRACE_IGNORE_ARGS`

## 0.3.0

* New way of displaying too deep arrays:
```
array(7) {
  ...
}
```

## 0.2.0

* Added `\Awesomite\VarDumper\Dumper`
* Added arguments `$displayPlaceInCode` and `$stepShift` to
`\Awesomite\VarDumper\InternalVarDumper::__construct`
and `\Awesomite\VarDumper\LightVarDumper::__construct`

## 0.1.0

* Initial public release
