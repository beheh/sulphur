# Sulphur changelog

## Unreleased

* Support PHP 7 and HHVM
* Switch to X.X.X releases (instead of vX.X.X)
* Various small cleanups

## v2.0.1

* Various miscellaneous fixes

## v2.0.0

This is a major release due to breaking changes.

* __Breaking change:__ Replaced ResponseFactory with a more generic parser:
```php
$parser = new Sulphur\Parser();
$response = $parser->parse(file_get_contents('example.com:80'));
```
* Added subsection field access:
```php
echo $reference->first('PlayerInfos')->first('Client')->first('Player')->Name;
```
* Added `Response::first` to access the first reference
* Key-Value pairs should not appear in the wrong section any more (the parser is now aware of indentation)
* Sulphur is now licensed under the ISC license

## v1.1.2

* Fixed chaining of `where` calls

## v1.1.1

* Fixed field values sometimes being the wrong datatype

## v1.1.0

* Added `passes` and `doesNotPass` for filtering with custom callbacks
* Minor improvements to the readme

## v1.0.0

* Initial release
