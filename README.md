# Sulphur

[![Build Status](https://travis-ci.org/beheh/sulphur.svg?branch=master)](https://travis-ci.org/beheh/sulphur)
[![License](https://img.shields.io/packagist/l/beheh/sulphur.svg)](https://packagist.org/packages/beheh/sulphur)

A full library to parse and filter data from the Clonk masterserver protocol, as used in the games Clonk Rage (http://clonk.de) and OpenClonk (http://openclonk.org).

This library was created by Benedict Etzel (developer@beheh.de) and is licensed under the ISC license.

## Installing

Install Sulphur by using [composer](https://getcomposer.org/).

```ShellSession
$ composer require beheh/sulphur
```

## Examples

```php
// fetch masterserver response
$parser = new Sulphur\Parser();
$response = $parser->parse(file_get_contents('example.com:80'));

// count all running games
echo count($response->where('State')->is('Running')).' game(s) are currently running';

// iterate through all games currently in lobby
foreach($response->where('State')->is('Lobby') as $reference) {
    echo $reference->Title.' is now open!';
}

// show comment of first game containing "CMC" (case insensitive)
$references = $response->where('Title')->contains('cmc', true);
echo $references[0]->Comment;

// show title of first running league game
$references = $response->where('State')->is('Running')
                       ->where('League')->doesNotExist();
echo $references[0]->Title;

// count games for Clonk Rage or OpenClonk
$references = $response->where('Game')->passes(function($field, $value) { return $value === 'Clonk Rage' || $value === 'OpenClonk'; });
echo count($references).' Clonk Rage and OpenClonk games open';

// print all player names in a reference
foreach($reference->first('PlayerInfos')->all('Client') as $client) {
	foreach($client->all('Player') as $player) {
		echo $player->Name;
	}
}
```

## Basic usage

You can access the master data by using the parser and passing masterserver data.

```php
$parser = new Sulphur\Parser();
$response = $parser->parse($data);
```

It's recommended to cache this data so the masterserver doesn't blacklist your server.

## Game references

Game sessions are tracked as game references. They can have a variety of fields which describe something about the game.

### Access

To access references, simply call the corresponding functions in the reference object:

```php
$references = $response->all();
$references = $response->where('Title')->is('Clepal');
$reference = $response->first('Reference'); // or $response->first()
```

The calls return an object which should handle like an array.

### Filtering

Responses can be filtered in multiple ways:

```php
$response->where('State')->is('Lobby');
$response->where('League')->exists();
$response->where('Comment')->contains('friendly');
$response->where('Comment')->contains('friendly', true); // case insensitive
$response->where('Version')->matches('/4(,[0-9]+){3}/');
```

Inverse filtering is also available:

```php
$response->where('State')->isNot('Running');
$response->where('League')->doesNotExist();
$response->where('Comment')->doesNotContain('bad');
$response->where('Comment')->doesNotContains('bad', true); // case insensitive
$response->where('Version')->doesNotMatch('/5(,[0-9]+){3}/');
```

You can also use custom callbacks (anything accepted by call_user_func):

```php
$response->where('Title')->passes(function($field, $value) { return strlen($value) > 5; });
$response->where('Title')->doesNotPass(function($field, $value) { return strlen($value) <= 3; });
```

### Chain filtering

You can filter multiple fields by repeating calls to `where`:

```php
$response->where('State')->is('Running')->where('League')->exists();
$response->where('State')->is('Lobby')->where('Password')->doesNotExist();
```

### Fields

Fields can be read simply by accessing the corresponding local variables (case-sensitive):

```php
echo $reference->Title;
echo $reference->Game;
```

### Subsections

To access fields in a specific section you can use the `all` and `first` methods:

```php
echo $reference->first('PlayerInfos')->first('Client')->first('Player')->Name;
foreach($reference->all('Resource') as $resource) {
	echo $resource->Filename;
}
```