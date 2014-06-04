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
    $response = Sulphur\ResponseFactory::fromUrl('example.com:80);
    foreach($response->where('State')->is('Lobby') as $reference) {
        echo $reference->Title.' is now open!';
    }
```

References
----------

Game sessions are tracked as game references. They can have a
variety of fields which describe something about the game.

### Fields ###

    $reference->Title;
    $reference->Game;

### Filtering ###

    $response->where('League')->exists();
    $response->where('Comment')->contains('friendly');
    $response->where('Version')->matches('/4(,[0-9]+){3}/')

    $response->where('League')->doesNotExist();
    $response->where('Comment')->doesNotContain('bad');
    $response->where('Version')->doesNotMatch('/5(,[0-9]+){3}/')

The query calls return an object which should handle like an array.

### Chain filtering ###

You can filter by multiple fields by repeating calls to `where`.

    $response->where('State')->is('Running')->where('League')->exists();