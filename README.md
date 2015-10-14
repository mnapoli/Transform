Transform
=========

Transform data from a format to another, e.g. models, arrays, DTOs…

# From object to array

```php
$transformer->addConfiguration([
    User::class => [
        'fields' => [
            'name',
            'password',
            'virtual' => [
                'get' => function () {
                    return rand();
                },
                'set' => null, // read-only
            ],
        ],
    ],
]);

$data = $transformer->transform($user);
```
