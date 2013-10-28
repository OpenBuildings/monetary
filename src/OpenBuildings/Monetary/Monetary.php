<?php

namespace OpenBuildings\Monetary;

/**
 * @author Yasen Yanev <yasen@openbuildings.com>
 * @author Ivan Kerin <ivan@openbuildings.com>
 * @author Haralan Dobrev <hdobrev@despark.com>
 * @copyright (c) 2013 OpenBuildings Inc.
 * @license http://spdx.org/licenses/BSD-3-Clause
 */
class Monetary {

	const DEFAULT_INSTANCE = 'default';

	const DEFAULT_CURRENCY = 'USD';

	const DEFAULT_SOURCE = 'OpenBuildings\Monetary\Source_ECB';

	const DEFAULT_PRECISION = 2;

	const POSITIVE = 'positive';

	const NEGATIVE = 'negative';

	const DEFAULT_POSITIVE_TEMPLATE_PREFIX = ':amount ';

	const DEFAULT_NEGATIVE_TEMPLATE_PREFIX = '-:amount ';

	/**
	 * Templates for formatting amounts in a given currency.
	 * @var array Currency codes as keys and templates as values.
	 * @see OpenBuildings\Monetary\Monetary::currency_template
	 */
	public static $currency_templates = array(
		'USD' => array(
			self::POSITIVE => '$:amount',
			self::NEGATIVE => '-$:amount',
		),
		'EUR' => array(
			self::POSITIVE => '€:amount',
			self::NEGATIVE => '-€:amount',
		),
		'GBP' => array(
			self::POSITIVE => '£:amount',
			self::NEGATIVE => '-£:amount',
		),
		'BGN' => array(
			self::POSITIVE => ':amount лв',
			self::NEGATIVE => '-:amount лв',
		),
		'JPY' => array(
			self::POSITIVE => '¥:amount',
			self::NEGATIVE => '¥-:amount',
		),
		'DKK' => array(
			self::POSITIVE => 'kr:amount',
			self::NEGATIVE => 'kr-:amount',
		)
	);

	/**
	 * @var array of Monetary instances
	 */
	protected static $_instances = array();

	public static function instance($group = self::DEFAULT_INSTANCE)
	{
		if (empty(self::$_instances[$group]))
		{
			self::$_instances[$group] = new static;
		}

		return self::$_instances[$group];
	}

	/**
	 * The default currency which will be used when no currency is provided.
	 * @var string the currency code
	 */
	protected $_default_currency;

	/**
	 * Source of exchange rates
	 * @var Sourceable
	 */
	protected $_source;

	/**
	 * The precision used for formatting amounts.
	 * @var integer
	 */
	protected $_precision;

	public function __construct(
		$default_currency = NULL,
		Sourceable $source = NULL,
		$precision = NULL
	)
	{
		$this->default_currency($default_currency);
		if ($source)
		{
			$this->source($source);
		}
		$this->precision($precision);
	}

	/**
	 * Get or set the source for the exchange rates.
	 * When getting a source and no source was set previously, it will create a
	 * new instance of OpenBuildings\Monetary\Monetary::DEFAULT_SOURCE
	 * @param  Sourceable $source source instance
	 * @return Sourceable|$this source when getting; $this when setting
	 */
	public function source(Sourceable $source = NULL)
	{
		if ( ! $source)
		{
			if ( ! $this->_source)
			{
				$default_source = static::DEFAULT_SOURCE;
				$this->_source = new $default_source;
			}

			if ( ! $source)
				return $this->_source;
		} // @codeCoverageIgnore

		$this->_source = $source;

		return $this;
	}

	/**
	 * Get or set the precision for formatting
	 * @param  integer $precision number of digits after the decimal point
	 * @return integer|$this an integer when getting; $this when setting
	 */
	public function precision($precision = NULL)
	{
		if ( ! $precision)
		{
			if ( ! $this->_precision)
			{
				$this->_precision = static::DEFAULT_PRECISION;
			}

			if ( ! $precision)
				return $this->_precision;
		} // @codeCoverageIgnore

		$this->_precision = $precision;

		return $this;
	}

	/**
	 * Get or set the default currency
	 * @param  string $default_currency set this currency (optional)
	 * @return string|$this string when getting; $this when setting
	 */
	public function default_currency($default_currency = NULL)
	{
		if ( ! $default_currency)
		{
			if ( ! $this->_default_currency)
			{
				$this->_default_currency = static::DEFAULT_CURRENCY;
			}

			if ( ! $default_currency)
				return $this->_default_currency;
		} // @codeCoverageIgnore

		$this->_default_currency = $default_currency;

		return $this;
	}

	/**
	 * Convert amount from one currency to another
	 * @param  float $value  amount to be converted
	 * @param  string $from  Currency of the amount. If NULL, default is used.
	 * @param  string $to    Desired currency. If NULL, default is used.
	 * @return float         Amount converted in the desired currency.
	 * @uses OpenBuildings\Monetary\Monetary::exchange_rate
	 */
	public function convert($value, $from = NULL, $to = NULL)
	{
		if ( ! $value)
			return (float) $value;

		return (float) $value * $this->exchange_rate($from, $to);
	}

	/**
	 * Get the exchange rate between two currencies
	 * @param  string $from Source currency code. If NULL, default is used.
	 * @param  string $to   Target currency code. If NULL, default is used.
	 * @return float        Exchange rate
	 * @uses OpenBuildings\Monetary\Monetary::exchange_rates
	 */
	public function exchange_rate($from = NULL, $to = NULL)
	{
		$from = $from ?: $this->default_currency();
		$to   = $to   ?: $this->default_currency();

		if ($from === $to)
			return 1.00;

		return $this->source()->rate($from, $to);
	}

	/**
	 * Formats an amount into a readable string with a currency sign.
	 * @param  float   $amount
	 * @param  string  $currency
	 * @param  integer $precision
	 * @return string formatted amount with a currency sign
	 * @uses OpenBuildings\Monetary\Monetary::round for rounding the amount
	 * @uses OpenBuildings\Monetary\Monetary::precision for default precision
	 * @uses OpenBuildings\Monetary\Monetary::currency_template for the template
	 */
	public function format($amount, $currency = NULL, $precision = NULL)
	{
		$precision = $precision === NULL ? $this->precision() : $precision;

		$currency = $currency ?: $this->default_currency();

		$template = $this->currency_template($currency, $amount);

		$amount = $this->round(abs($amount), $precision);

		return preg_replace('/\:amount/u', $amount, $template);
	}

	/**
	 * Round the amount to the given precision
	 * @param  integer|string|float $amount Amount to be rounded
	 * @param  string $precision Number of digits after the decimal point
	 * @return string the rounded amount
	 */
	public function round($amount, $precision)
	{
		return number_format( (float) $amount, $precision);
	}

	/**
	 * Convert an amount to a different currency and format it
	 * @param  float $amount  Amount to be converted and formatted
	 * @param  string $from   Currency of the amount. If NULL, default is used.
	 * @param  string $to     Desired currency. If Null, default is used.
	 * @return strgin         Formatted and converted amount.
	 * @uses OpenBuildings\Monetary\Monetary::format for the formatting
	 * @uses OpenBuildings\Monetary\Monetary::convert for the conversion
	 */
	public function convert_and_format($amount, $from = NULL, $to = NULL)
	{
		return $this->format($this->convert($amount, $from, $to), $to);
	}

	/**
	 * Get the template for a currency.
	 * Either found in the $currency_templates
	 * or built by the format <amount> <currency_code>
	 * @param  string $currency currency code
	 * @return string           template with ":amount" placeholder
	 */
	public function currency_template($currency, $amount = NULL)
	{
		$sign = ($amount === NULL OR $amount >= 0)
			? self::POSITIVE
			: self::NEGATIVE;

		return empty(self::$currency_templates[$currency])
			? $this->default_template($currency, $amount)
			: (empty(self::$currency_templates[$currency][$sign])
				? self::$currency_templates[$currency]
				: self::$currency_templates[$currency][$sign]);
	}

	public function default_template($currency, $amount = NULL)
	{
		return ($amount === NULL OR $amount >= 0)
			? self::DEFAULT_POSITIVE_TEMPLATE_PREFIX.$currency
			: self::DEFAULT_NEGATIVE_TEMPLATE_PREFIX.$currency;
	}
}