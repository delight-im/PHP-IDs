# PHP-IDs

Short, obfuscated and efficient IDs for PHP

No database changes are required. The original (integer) IDs are all you need.

No collisions. Reversible.

## Why do I need this?

 * Don't leak information to your competitors (e.g. number of orders, sign-ups per day)
 * Prevent resource enumeration by waiving sequential IDs
 * Mix up IDs a little bit in order to make guessing them harder
 * ~~Security through obscurity~~

## Requirements

 * PHP 5.6.0+
   * GMP extension

## Installation

 * Install via [Composer](https://getcomposer.org/) (recommended)

   `$ composer require delight-im/ids`

   Include the Composer autoloader:

   `require __DIR__.'/vendor/autoload.php';`

 * or

 * Install manually

   * Copy the contents of the [`src`](src) directory to a subfolder of your project
   * Include the files in your code via `require` or `require_once`

## Usage

### Creating an instance

```php
$generator = new \Delight\Ids\Id();
```

### Encoding and decoding IDs

```php
$generator->encode(6); // => "43Vht7"
$generator->decode('43Vht7'); // => 6
```

### Shortening a number without obfuscating it

```php
$generator->shorten(3141592); // => "vJST"
$generator->unshorten("vJST"); // => 3141592
```

### Obfuscating a number without shortening it

```php
$generator->obfuscate(42); // => 958870139
$generator->deobfuscate(958870139); // => 42
```

## Customization

 1. Shuffle the characters of the alphabet that is used for the base conversion. Calling `\Delight\Ids\Id::createRandomAlphabet()` may be helpful for that purpose. You might also change the alphabet entirely, but there's usually no need to do that.
 1. Pass your new alphabet to the constructor as the first argument.
 1. Create [your own prime number, inverse prime and random number](https://github.com/jenssegers/optimus/blob/master/src/Commands/SparkCommand.php) for Knuth's multiplicative hashing.
 1. Pass your three new numbers to the constructor as the second, third and fourth argument, respectively.

## Contributing

All contributions are welcome! If you wish to contribute, please create an issue first so that your feature, problem or question can be discussed.

## License

This project is licensed under the terms of the [MIT License](https://opensource.org/licenses/MIT).
