<?php

/*
 * PHP-IDs (https://github.com/delight-im/PHP-IDs)
 * Copyright (c) delight.im (https://www.delight.im/)
 * Licensed under the MIT License (https://opensource.org/licenses/MIT)
 */

namespace Delight\Ids;

/** Short, obfuscated and efficient IDs for PHP */
final class Id {

	/** The default alphabet for the base conversion */
	const ALPHABET_DEFAULT = '23456789bcdfghjkmnpqrstvwxyzBCDFGHJKLMNPQRSTVWXYZ-_';
	/** The default prime number for Knuth's multiplicative hashing */
	const PRIME_DEFAULT = 1125812041;
	/** The modular multiplicative inverse of the default prime for Knuth's multiplicative hashing */
	const INVERSE_DEFAULT = 348986105;
	/** The default random integer for Knuth's multiplicative hashing */
	const RANDOM_DEFAULT = 998048641;

	/** @var string the alphabet for the base conversion */
	private $alphabet;
	/** @var int the size of the alphabet */
	private $base;
	/** @var \Jenssegers\Optimus\Optimus the generator performing Knuth's multiplicative hashing */
	private $obfuscator;

	/**
	 * Constructor
	 *
	 * @param string|null $alphabet (optional) the alphabet for the base conversion
	 * @param int|null $prime (optional) the prime number for Knuth's multiplicative hashing
	 * @param int|null $inverse (optional) the modular multiplicative inverse of the prime for Knuth's multiplicative hashing
	 * @param int|null $random (optional) the random integer for Knuth's multiplicative hashing
	 */
	public function __construct($alphabet = null, $prime = null, $inverse = null, $random = null) {
		$this->alphabet = empty($alphabet) ? self::ALPHABET_DEFAULT : ((string) $alphabet);
		$this->base = strlen($this->alphabet);
		$this->obfuscator = new \Jenssegers\Optimus\Optimus(
			empty($prime) ? self::PRIME_DEFAULT : ((int) $prime),
			empty($inverse) ? self::INVERSE_DEFAULT : ((int) $inverse),
			empty($random) ? self::RANDOM_DEFAULT : ((int) $random)
		);
	}

	/**
	 * Encodes an ID to a short, obfuscated identifier
	 *
	 * @param int $num the (integer) ID to encode
	 * @return string the encoded identifier
	 */
	public function encode($num) {
		return $this->shorten($this->obfuscate($num));
	}

	/**
	 * Decodes an ID from a short, obfuscated identifier
	 *
	 * @param string $str the encoded identifier
	 * @return int the (integer) ID to encode
	 */
	public function decode($str) {
		return $this->deobfuscate($this->unshorten($str));
	}

	/**
	 * Converts the specified integer ID to a short string
	 *
	 * This is done by performing a (bijective) base conversion
	 *
	 * @param int $num the integer ID to convert
	 * @return string the short string
	 */
	public function shorten($num) {
		$str = '';

		while ($num > 0) {
			$str = $this->alphabet[($num % $this->base)] . $str;
			$num = (int) ($num / $this->base);
		}

		return $str;
	}

	/**
	 * Converts the specified short string to an integer ID
	 *
	 * This is done by performing a (bijective) base conversion
	 *
	 * @param string $str the short string to convert
	 * @return int the integer ID
	 */
	public function unshorten($str) {
		$num = 0;
		$len = strlen($str);

		for ($i = 0; $i < $len; $i++) {
			$num = $num * $this->base + strpos($this->alphabet, $str[$i]);
		}

		return $num;
	}

	/**
	 * Obfuscates the specified integer number
	 *
	 * This is done by performing Knuth's multiplicative hashing
	 *
	 * @param int $num the integer to obfuscate
	 * @return int the obfuscated integer
	 */
	public function obfuscate($num) {
		return $this->obfuscator->encode($num);
	}

	/**
	 * De-obfuscates the specified integer number
	 *
	 * This is done by performing Knuth's multiplicative hashing
	 *
	 * @param int $num the integer to de-obfuscate
	 * @return int the de-obfuscated integer
	 */
	public function deobfuscate($num) {
		return $this->obfuscator->decode($num);
	}

	/**
	 * Creates a random alphabet that may be used instead of the default one for customization
	 *
	 * @return string the new random alphabet
	 */
	public static function createRandomAlphabet() {
		return str_shuffle(self::ALPHABET_DEFAULT);
	}

}
