Sulphur
=======

A self-contained library to parse and filter data from the
Clonk masterserver protocol, as used in the games Clonk Rage
(http://clonk.de) and OpenClonk (http://openclonk.org).

This library is licensed under the MIT license.


Installing
----------

Install Sulphur by using composer.

    $ composer require beheh/sulphur

Basic usage
-----------

```php
// fetch masterserver response
$response = Sulphur\ResponseFactory::fromUrl('example.com:80');

// iterate through all games currently in lobby
foreach($response->where('State')->is('Lobby') as $reference) {
    echo $reference->Title.' is now open!';
}

// show comment of first game containing "CMC" (case insensitive)
$references = $response->where('Title')->contains('cmc', true);
echo $references[0]->Comment;

// show title of first running league game
$references = $response->where('State')->is('Running')->where('League')->exists();
echo $references[0]->Title;
```

References
----------

Game sessions are tracked as game references. They can have a
variety of fields which describe something about the game.

### Fields ###

You can read from fields simply by accessing the corresponding local variables.

    $reference->Title;
    $reference->Game;

### Filtering ###

Responses can be filtered in multiple ways:

    $response->where('League')->exists();
    $response->where('Comment')->contains('friendly');
    $response->where('Comment')->contains('friendly', true); // case insensitive
    $response->where('Version')->matches('/4(,[0-9]+){3}/');

    $response->where('League')->doesNotExist();
    $response->where('Comment')->doesNotContain('bad');
    $response->where('Comment')->doesNotContains('bad', true); // case insensitive
    $response->where('Version')->doesNotMatch('/5(,[0-9]+){3}/');

The query calls return an object which should handle like an array.

### Chain filtering ###

You can filter by multiple fields by repeating calls to `where`.

    $response->where('State')->is('Running')->where('League')->exists();