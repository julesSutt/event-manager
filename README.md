# Event manager

A lightweight library for simple events management

### Installation

Install project with composer

```bash
composer require julessutt/event-manager
```

### Autoload and use class
```php
require_once 'vendor/autoload.php';

use EventManager\Emitter;
```

### Get instance and register an event

```php
$em = Emitter::getInstance();
$em->on('user.new', function ($user) {
    echo "New user: {$user->getName()}";
});
```

### Trigger event
```php
$em->emit('user.new', $user);
```
## Author and github project

[Author - @julesSutt](https://www.github.com/julesSutt)
[Github project](https://github.com/julesSutt/event-manager)