Transform
=========

Transform data from a format to another, e.g. models, arrays, DTOs…

# Usage

Using the transformer is pretty simple:

```php
$result = $transformer->transform($from, $to);
```

`$from` can be:

- an object
- an array

`$to` can be:

- an object
- `'array'`: a new array will be created
- a class name: a new instance of the class will be created

You can transform from and to arrays and objects.

# Mapping

Use configuration to define how to map objects to arrays:

```php
$transformer->addMapping([
    User::class => [
        'fields' => [
            'name',
            'password',
        ],
    ],
]);

$data = $transformer->transform($user, 'array');
```

## Properties

```php
class User
{
    public $name;
    private $password; // works with private properties too

    // Getters and setters will be called if they exist
    private $email;
    public function getEmail() { ... }
    public function setEmail($email) { ... }
}

// ...

    User::class => [
        'fields' => [
            'name',
            'password',
            'email',
        ],
    ],
```

## Getters and setters

You can configure specific methods to be called where reading or setting the value:

```php
class User
{
    public function someMethod() { ... }
    public function someOtherMethod($value) { ... }
}

// ...

    User::class => [
        'fields' => [
            'name' => [
                'get' => 'someMethod()',
                'set' => 'someOtherMethod()',
            ],
        ],
    ],
```

## Accessors

You can use PHP callables to define how to set and get a field:

```php
class User
{
    private $login;
}

// ...

    User::class => [
        'fields' => [
            'name' => [
                'get' => function () {
                    return $this->login;
                },
                'set' => function ($value) {
                    $this->login = $value
                },
            ],
        ],
    ],
```

Closures are rebound to the object: that means `$this` represents the object being transformed. You can access private properties and methods just like if you were *inside* the class.

You can mark a field as read-only or write-only by setting a `null` accessor:

```php
    User::class => [
        'fields' => [
            'name' => [
                'get' => function () { ... },
                'set' => null, // read-only
            ],
        ],
    ],
```

## Field types

You can declare the types of each field using the `type` index:

```php
class Product
{
    /**
     * @var Price
     */
    private $price;
}

// ...

    Product::class => [
        'fields' => [
            'price' => [
                'type' => Price::class,
            ],
        ],
    ],
```
