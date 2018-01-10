# Changelog

## 0.12.0 (2018-01-10)

* Added support for constants `PHP_FLOAT_EPSILON`, `PHP_FLOAT_MIN`, `PHP_FLOAT_MAX`
* Removed deprecated method `Awesomite\VarDumper\VarDumperInterface::dumpAsString`
* Added - print symbol `↵` at the end of the line, e.g.:
```
string(11)
    › hello↵
    › world
```

## 0.11.1 (2018-01-10)

* Fixed bug - `Awesomite\VarDumper\InternalVarDumper::dumpAsString`
and `Awesomite\VarDumper\InternalVarDumper::getDump` was displaying invalid file and line
* Fixed bug introduced in 0.9.0 - displaying short array with enabled $displayPlaceInCode
flag was causing invalid output, e.g.:
```
(...)/test.php:6:
array(5) {(...)/src/InternalVarDumper.php:47:
1, (...)/src/InternalVarDumper.php:47:
2, (...)/src/InternalVarDumper.php:47:
3, (...)/src/InternalVarDumper.php:47:
4, (...)/src/InternalVarDumper.php:47:
5}
```
instead of
```
(...)/test.php:6:
array(5) {1, 2, 3, 4, 5}
```

## 0.11.0 (2018-01-09)

* Method `Awesomite\VarDumper\VarDumperInterface::getDump` has been marked as `deprecated`
and will be removed in next version,
use `Awesomite\VarDumper\VarDumperInterface::dumpAsString` instead
* Added support for `\e`, `\f` and binary strings
* Added method `Awesomite\VarDumper\LightVarDumper::setIndent`
and constant `Awesomite\VarDumper\LightVarDumper::DEFAULT_INDENT`
* Added validators for `Awesomite\VarDumper\LightVarDumper::setMax*` methods
* Fixed - printed line should not be longer than limit set by
`Awesomite\VarDumper\LightVarDumper::setMaxLineLength` method
* More readable format of objects with method
[`__debugInfo()`](http://php.net/manual/en/language.oop5.magic.php#object.debuginfo), e.g.:
```
object(Foo) #1 (3) {[
    [0] => 1
    [1] => RECURSIVE object(Foo) #1
    [2] => M_PI
]}
```

## 0.10.0 (2018-01-02)

* Convert `\t`, `\r`, `\0`, `\x0B` to visible characters
* Split multiline text by white character whenever it is possible, e.g.:
```
string(768)
    › Lorem ipsum dolor sit amet, consectetur adipiscing
    ›  elit. Proin nibh augue, suscipit a, scelerisque
    › sed, lacinia in, mi. Cras vel lorem. Etiam
    › pellentesque aliquet tellus. Phasellus pharetra
    › nulla ac diam. Quisque semper justo at risus.
    › Donec venenatis, turpis vel hendrerit interdum,
    › dui ligula ultricies purus, sed posuere libero dui
    ›  id orci. Nam congue, pede vitae dapibus aliquet,
    › elit magna vulpu...
```

## 0.9.0 (2017-12-21)

* Class `Awesomite\VarDumper\LightVarDumper` becomes `final`
* Excluded `phpunit.xml.dist` from `git archive`
* Use `spl_object_id` whenever it is possible
* Do not add `...` to the end of text whenever text is not shortened
* Added constants `Awesomite\VarDumper\LightVarDumper::DEFAULT_*`
* Display constant's name instead of value for the following constants: `M_*`, `PHP_INT_MIN`, `PHP_INT_MAX`
* Display resource's id, e.g. `resource #1 of type stream`
* Allow displaying array in single line whenever array contains only 1 element,
element has index 0, elementy is type of string, element does not contain `\n`
and output is not too long e.g.
```
array(2) {
    [0] => array(1) {“hello”}
    [1] => array(1) {“world”}
}
```
* Allow displaying array in single line whenever array contains no more than 5 elements,
keys are no longer than 20 characters each element is null, empty array or scalar (except string), e.g.:
```
array(5) {0, 1, 2, 3, 4}
array(4) {1, 2, 3, [key] => NULL}
```

## 0.8.0 (2017-08-30)

* Do not display values of private properties in parent class - it can cause unexpected error,
because it can call [`__get()`](http://php.net/manual/en/language.oop5.overloading.php#object.get) function
* Use [`__debugInfo()`](http://php.net/manual/en/language.oop5.magic.php#object.debuginfo) whenever it is possible
* Excluded useless files from Github dists and `git archive`
* Added method `Awesomite\VarDumper\LightVarDumper::setMaxLineLength`
* Changed format of printed data:
  * Display value in one line whenever it is possible:
  ```
  array(0) {}
  ```
  * Single line text:
  ```
  “Hello world!”
  ```
  * Multi line text:
  ```
  string(12)
      › Hello
      › world!
  ```
  * Increased indent to 4 spaces for objects and arrays
  ```
  array(1) {
      [0] => array(0) {}
  }
  ```
  * Added indent for values of arrays and properties of objects
  ```
  array(1) {
      [0] =>
          array(2) {
              [0] => array(0) {}
              [1] =>
                  array(1) {
                      [0] => array(0) {}
                  }
          }
  }
  ```
  * Display adjacent lines in tabular format
  ```
  array(9) {
      [a] =>            “Hello world”
      [ab] =>           “Hello world”
      [abc] =>          “Hello world”
      [abcd] =>         “Hello world”
      [abcdefghijkl] => “Hello world”
      [array] =>
          array(1) {
              [0] => “Hello world”
          }
      [x] =>   “Hello world”
      [xy] =>  “Hello world”
      [xyz] => “Hello world”
  }
  ```
  * Convert new-line character to visible form in keys/properties
  ```
  array(1) {
      [multi↵line↵key] => “Hello world”
  }
  ```

## 0.7.2 (2017-07-20)

* Fixed bug - `ReflectionProperty::getValue` can throw an exception

## 0.7.1 (2017-06-18)

* Removed support for PHPUnit ^5.0 and ^6.0 - there are issue with HHVM - `Exception` does not implement `Throwable` in HHVM
* Fixed bug "Undefined property: ClassName::$propertyName" - property can be defined in code, but removed in runtime, e.g.:

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
