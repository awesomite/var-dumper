# Changelog

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