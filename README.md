Transform
=========

Transform data from a format to another, e.g. models, arrays, DTOs…

# From object to array

```php
$mapping = [
    'from' => User::class,
    'to' => 'array',
    'fields' => [
        'password' => [
            'target_name' => 'encodedPassword',
            'exclude' => true,

            'transform' => false,
            'reverse_transform' => false,

            'transform' => function ($user, $password) {
                return md5($password);
            },
            'reverse_transform' => function ($data, $encodedPassword) {
                return null;
            },
        ],
    ],
    'callbacks' => [
        'post_transform' => function ($user, $data) {
        },
        'post_reverse_transform' => function ($data, $user) {
        },
    ],
];

$data = $transformer->transform($user, 'array', $mapping);
```
