<?php

/*
 * PHP-IDs (https://github.com/delight-im/PHP-IDs)
 * Copyright (c) delight.im (https://www.delight.im/)
 * Licensed under the MIT License (https://opensource.org/licenses/MIT)
 */

// enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 'stdout');

// enable assertions
ini_set('assert.active', 1);
ini_set('zend.assertions', 1);
ini_set('assert.exception', 1);

header('Content-type: text/plain; charset=utf-8');

require __DIR__.'/../vendor/autoload.php';

function generateRandomPrime() {
	$min = new \phpseclib\Math\BigInteger(1e6);
	$max = new \phpseclib\Math\BigInteger(\Jenssegers\Optimus\Optimus::MAX_INT);

	$prime = (new \phpseclib\Math\BigInteger())->randomPrime($min, $max);

	return (int) ((string) $prime);
}

function generateModularMultiplicativeInverse($prime) {
	$a = new \phpseclib\Math\BigInteger($prime);
	$m = new \phpseclib\Math\BigInteger(\Jenssegers\Optimus\Optimus::MAX_INT + 1);

	$x = $a->modInverse($m);

	return (int) ((string) $x);
}

function generateRandomInt() {
	// TODO: use `random_int(...)` in PHP 7.0.0+
	return mt_rand(1, \Jenssegers\Optimus\Optimus::MAX_INT);
}

$generator = new \Delight\Ids\Id();

echo '$num | $generator->encode($num) | $generator->decode(...)';
echo "\n";
echo '---------------------------------------------------------';
echo "\n";

for ($i = 0; $i < 25; $i++) {
	$encoded = $generator->encode($i);
	$decoded = $generator->decode($encoded);

	echo sprintf('% 5s', $i);
	echo ' ';
	echo sprintf('% 25s', $encoded);
	echo ' ';
	echo sprintf('% 25s', $decoded);
	echo "\n";
}

$numFirst = 3141592;
echo "\n";
echo 'Shortening a number without obfuscating it:';
echo "\n";
echo "\t";
echo '$generator->shorten(';
echo $numFirst;
echo '); // => "';
echo $generator->shorten($numFirst);
echo '"';
echo "\n";
echo "\t";
echo '$generator->unshorten("';
echo $generator->shorten($numFirst);
echo '"); // => ';
echo $generator->unshorten($generator->shorten($numFirst));
echo "\n";

$numSecond = 42;
echo "\n";
echo 'Obfuscating a number without shortening it:';
echo "\n";
echo "\t";
echo '$generator->obfuscate(';
echo $numSecond;
echo '); // => ';
echo $generator->obfuscate($numSecond);
echo "\n";
echo "\t";
echo '$generator->deobfuscate(';
echo $generator->obfuscate($numSecond);
echo '); // => ';
echo $generator->deobfuscate($generator->obfuscate($numSecond));
echo "\n";

$prime = generateRandomPrime();
echo "\n";
echo 'Random prime number:';
echo "\n";
echo "\t";
echo $prime;

echo "\n";
echo 'Modular multiplicative inverse of random prime:';
echo "\n";
echo "\t";
echo generateModularMultiplicativeInverse($prime);

echo "\n";
echo 'Random integer:';
echo "\n";
echo "\t";
echo generateRandomInt();

echo "\n";
echo 'Creating a random alphabet:';
echo "\n";
echo "\t";
echo '\Delight\Ids\Id::createRandomAlphabet(); // => "';
echo \Delight\Ids\Id::createRandomAlphabet();
echo '"';
echo "\n";
