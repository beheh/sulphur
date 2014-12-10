# Sulphur

[![Build Status](https://travis-ci.org/beheh/sulphur.svg?branch=master)](https://travis-ci.org/beheh/sulphur)
[![License](https://img.shields.io/packagist/l/beheh/sulphur.svg)](https://packagist.org/packages/beheh/sulphur)

A self-contained library to parse and filter data from the Clonk masterserver protocol, as used in the games Clonk Rage (http://clonk.de) and OpenClonk (http://openclonk.org).

This library is licensed under the MIT license.


## Installing

Install Sulphur by using [composer](https://getcomposer.org/).

    $ composer require beheh/sulphur

## Basic usage

```php
// fetch masterserver response
$response = Sulphur\ResponseFactory::fromUrl('example.com:80');

// count all running games
echo count($response->where('State')->is('Running')).' game(s) are currently running.';

// iterate through all games currently in lobby
foreach($response->where('State')->is('Lobby') as $reference) {
    echo $reference->Title.' is now open!';
}

// show comment of first game containing "CMC" (case insensitive)
$references = $response->where('Title')->contains('cmc', true);
echo $references[0]->Comment;

// show title of first running league game
$references = $response->where('State')->is('Running')
                       ->where('League')->exists();
echo $references[0]->Title;
```

## Game references

Game sessions are tracked as game references. They can have a variety of fields which describe something about the game.

### Access

To access references, simply call the corresponding functions in
the reference object:

    $references = $response->all();
    $references = $response->where('Title')->is('Clepal');

The calls return an object which should handle like an array.

### Filtering

Responses can be filtered in multiple ways:

    $response->where('State')->is('Lobby');
    $response->where('League')->exists();
    $response->where('Comment')->contains('friendly');
    $response->where('Comment')->contains('friendly', true); // case insensitive
    $response->where('Version')->matches('/4(,[0-9]+){3}/');
	$response->where('Title')->passes(function($field, $value) { return strlen($value) > 5; });

Inverse filtering is also available:

    $response->where('State')->isNot('Running');
    $response->where('League')->doesNotExist();
    $response->where('Comment')->doesNotContain('bad');
    $response->where('Comment')->doesNotContains('bad', true); // case insensitive
    $response->where('Version')->doesNotMatch('/5(,[0-9]+){3}/');
	$response->where('Title')->doesNotPass(function($field, $value) { return strlen($value) <= 3; });

The `passes` and `doesNotPass` methods accept any callable accepted by call_user_func.

### Chain filtering

You can filter multiple fields by repeating calls to `where`.

    $response->where('State')->is('Running')->where('League')->exists();
    $response->where('State')->is('Lobby')->where('Password')->doesNotExist();

### Fields

Fields can be read simply by accessing the corresponding local variables (case-sensitive!).

    $reference->Title;
    $reference->Game;