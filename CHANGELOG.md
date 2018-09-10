# Changelog

## [1.0.2] - 2018-09-10

* Refactor, external API has not changed

## [1.0.1] - 2018-04-21

* Use [`get_object_vars`](http://php.net/manual/en/function.get-object-vars.php)
instead of [`ReflectionObject::getProperties`](http://php.net/manual/en/reflectionclass.getproperties.php)
whenever object contains method [`__get`](http://php.net/manual/en/language.oop5.overloading.php#object.get)
* In some cases method `LightVarDumper::dump` didn't work properly, because the following code triggers
error `Notice: Undefined property: Foo::$a` in PHP ^7.0, fixed issue:

```
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

[1.0.2]: https://github.com/awesomite/var-dumper/compare/v1.0.1...v1.0.2
[1.0.1]: https://github.com/awesomite/var-dumper/compare/v1.0.0...v1.0.1
[1.0.0]: https://github.com/awesomite/var-dumper/tree/v1.0.0
[0.12.0]: https://github.com/awesomite/var-dumper/tree/v0.12.0
