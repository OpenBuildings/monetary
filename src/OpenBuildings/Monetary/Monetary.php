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
	 * Templates for formatting amounts in a given currency.
	 * @var array Currency codes as keys and templates as values.
	 * @see OpenBuildings\Monetary\Monetary::currency_template
	 */
	public $currency_templates = array(
		'USD' => '$:amount',
		'EUR' => '€:amount',
		'GBP' => '£:amount',
		'BGN' => ':amount лв',
		'JPY' => '¥:amount',
	);

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

	public function __construct($default_currency = self::DEFAULT_CURRENCY, Sourceable $source = NULL, $precision = self::DEFAULT_PRECISION)
	{
		$this->default_currency($default_currency);
		$this->source($source);
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
		if ( ! $this->_source AND ! $source)
		{
			$default_source = static::DEFAULT_SOURCE;
			$this->_source = new $default_source;
		}

		if ( ! $source)
			return $this->_source;

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
			return $this->_precision;

		$this->_precision = $precision;

		return $this;
	}

	/**
	 * Get exchange rates from the source
	 * @return array
	 */
	public function exchange_rates()
	{
		return $this->source()->exchange_rates();
	}

	/**
	 * Get or set the default currency
	 * @param  string $default_currency set this currency (optional)
	 * @return string|$this string when getting; $this when setting
	 */
	public function default_currency($default_currency = NULL)
	{
		if ( ! $default_currency)
			return $this->_default_currency;

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

		if ( ! ($currency_data = $this->exchange_rates()))
			return 1.00;

		$from_rate = empty($currency_data[$from]) ? 1 : $currency_data[$from];
		$to_rate = empty($currency_data[$to]) ? 1 : $currency_data[$to];

		return (float) $to_rate / (float) $from_rate;
	}

	/**
	 * Formats an amount into a readable string with a currency sign.
	 * @param  float  $amount
	 * @param  string $currency
	 * @return string formatted amount with a currency sign
	 * @uses OpenBuildings\Monetary\Monetary::round for rounding the amount
	 * @uses OpenBuildings\Monetary\Monetary::precision for the rounding precision
	 * @uses OpenBuildings\Monetary\Monetary::currency_template for the template
	 */
	public function format($amount, $currency = NULL)
	{
		$amount = $this->round($amount, $this->precision());

		$currency = $currency ?: $this->default_currency();

		$template = $this->currency_template($currency);

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
	public function currency_template($currency)
	{
		return empty($this->currency_templates[$currency])
			? ':amount '.$currency
			: $this->currency_templates[$currency];
	}
}