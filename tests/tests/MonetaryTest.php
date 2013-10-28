<?php

use OpenBuildings\Monetary as M;

/**
 *
 * @author Yasen Yanev <yasen@openbuildings.com>
 * @author Ivan Kerin <ivan@openbuildings.com>
 * @author Haralan Dobrev <hdobrev@despark.com>
 * @copyright (c) 2013 OpenBuildings Inc.
 * @license http://spdx.org/licenses/BSD-3-Clause
 */
class MonetaryTest extends Monetary_TestCase {

	/**
	 * @covers OpenBuildings\Monetary\Monetary::__construct
	 */
	public function test_constructor_defaults()
	{
		$monetary = new M\Monetary;
		$this->assertEquals(
			M\Monetary::DEFAULT_CURRENCY,
			$monetary->default_currency()
		);

		$this->assertInstanceOf(
			'OpenBuildings\Monetary\Source_Ecb',
			$monetary->source()
		);

		$this->assertEquals(
			M\Monetary::DEFAULT_PRECISION,
			$monetary->precision()
		);
	}

	/**
	 * @covers OpenBuildings\Monetary\Monetary::__construct
	 */
	public function test_constructor()
	{
		$monetary = new M\Monetary('EUR', new M\Source_Static, 4);
		$this->assertEquals('EUR',$monetary->default_currency());

		$this->assertInstanceOf(
			'OpenBuildings\Monetary\Source_Static',
			$monetary->source()
		);

		$this->assertEquals(4, $monetary->precision());
	}

	public function data_format()
	{
		return array(
			array(10,     NULL , 2, '$10.00'),
			array(10,     'EUR', 2, '€10.00'),
			array(5.97,   'GBP', 2, '£5.97'),
			array(5.9764, 'GBP', 2, '£5.98'),
			array(530.05, 'USD', 2, '$530.05'),
			array(25.00,  'BGN', 2, '25.00 лв'),
			array(25.00,  'JPY', 2, '¥25.00'),
			array(25.00,  'DKK', 2, 'kr25.00'),
			array(10,     NULL , 3, '$10.000'),
			array(10,     'EUR', 3, '€10.000'),
			array(5.97,   'GBP', 3, '£5.970'),
			array(5.9764, 'GBP', 3, '£5.976'),
			array(5.9764, 'JPY', 3, '¥5.976'),
			array(5.9764, 'DKK', 3, 'kr5.976'),
			array(530.05, 'USD', 3, '$530.050'),
			array(25.00,  'BGN', 3, '25.000 лв'),
			array(-10,     NULL , 2, '-$10.00'),
			array(-10,     'EUR', 2, '-€10.00'),
			array(-5.97,   'GBP', 2, '-£5.97'),
			array(-5.9764, 'GBP', 2, '-£5.98'),
			array(-530.05, 'USD', 2, '-$530.05'),
			array(-25.00,  'BGN', 2, '-25.00 лв'),
			array(-10,     NULL , 3, '-$10.000'),
			array(-10,     'EUR', 3, '-€10.000'),
			array(-5.97,   'GBP', 3, '-£5.970'),
			array(-5.9764, 'GBP', 3, '-£5.976'),
			array(-530.05, 'USD', 3, '-$530.050'),
			array(-25.00,  'BGN', 3, '-25.000 лв'),
			array(-5.97,   'JPY', 2, '¥-5.97'),
			array(-5.9764, 'JPY', 2, '¥-5.98'),
			array(-5.97,   'DKK', 2, 'kr-5.97'),
			array(-5.9764, 'DKK', 2, 'kr-5.98'),
			array(-530.05, 'USD', 2, '-$530.05'),
			array(-25.00,  'BGN', 2, '-25.00 лв'),
			array(-10,     NULL , 3, '-$10.000'),
			array(-10,     'EUR', 3, '-€10.000'),
			array(-5.97,   'GBP', 3, '-£5.970'),
			array(-5.9764, 'GBP', 3, '-£5.976'),
			array(-530.05, 'USD', 3, '-$530.050'),
			array(-5.9764, 'JPY', 3, '¥-5.976'),
			array(-530.05, 'DKK', 3, 'kr-530.050'),
			array(-25.00,  'BGN', 3, '-25.000 лв'),
		);
	}

	/**
	 * @dataProvider data_format
	 * @covers OpenBuildings\Monetary\Monetary::format
	 */
	public function test_format($amount, $currency, $precision, $formatted_amount)
	{
		$this->assertEquals($formatted_amount, $this->monetary->format($amount, $currency, $precision));
	}

	/**
	 * @covers OpenBuildings\Monetary\Monetary::default_currency
	 */
	public function test_default_currency()
	{
		$this->assertEquals(self::CURRENCY, $this->monetary->default_currency());

		$this->monetary->default_currency('GBP');
		$this->assertEquals('GBP', $this->monetary->default_currency());

		$this->monetary->default_currency(self::CURRENCY);
		$this->assertEquals(self::CURRENCY, $this->monetary->default_currency());
	}

	/**
	 * @covers OpenBuildings\Monetary\Monetary::precision
	 */
	public function test_precision()
	{
		$this->assertEquals(2, $this->monetary->precision());

		$this->monetary->precision(3);
		$this->assertEquals(3, $this->monetary->precision());

		$this->monetary->precision(2);
		$this->assertEquals(2, $this->monetary->precision());
	}

	/**
	 * @covers OpenBuildings\Monetary\Monetary::source
	 */
	public function test_source()
	{
		$monetary = new M\Monetary;

		$this->assertInstanceOf('OpenBuildings\Monetary\Sourceable', $monetary->source());
		$this->assertInstanceOf('OpenBuildings\Monetary\Source_ECB', $monetary->source());

		$source = new M\Source_Static;
		$monetary->source($source);
		$this->assertInstanceOf('OpenBuildings\Monetary\Sourceable', $monetary->source());
		$this->assertInstanceOf('OpenBuildings\Monetary\Source_Static', $monetary->source());
	}

	public function data_currency_template()
	{
		return array(
			array('USD', NULL, '$:amount'),
			array('GBP', NULL, '£:amount'),
			array('EUR', NULL, '€:amount'),
			array('BGN', NULL, ':amount лв'),
			array('JPY', NULL, '¥:amount'),
			array('DKK', NULL, 'kr:amount'),
			array('XXX', NULL, ':amount XXX'),
			array('USD', 50, '$:amount'),
			array('GBP', 50, '£:amount'),
			array('EUR', 50, '€:amount'),
			array('BGN', 50, ':amount лв'),
			array('JPY', 50, '¥:amount'),
			array('DKK', 50, 'kr:amount'),
			array('XXX', 50, ':amount XXX'),
			array('USD', 0, '$:amount'),
			array('GBP', 0, '£:amount'),
			array('EUR', 0, '€:amount'),
			array('BGN', 0, ':amount лв'),
			array('JPY', 0, '¥:amount'),
			array('DKK', 0, 'kr:amount'),
			array('XXX', 0, ':amount XXX'),
			array('USD', -50, '-$:amount'),
			array('GBP', -50, '-£:amount'),
			array('EUR', -50, '-€:amount'),
			array('BGN', -50, '-:amount лв'),
			array('JPY', -50, '¥-:amount'),
			array('DKK', -50, 'kr-:amount'),
			array('XXX', -50, '-:amount XXX'),
		);
	}

	/**
	 * @dataProvider data_currency_template
	 * @covers OpenBuildings\Monetary\Monetary::currency_template
	 */
	public function test_currency_template($currency, $amount, $expected_template)
	{
		$this->assertSame(
			$expected_template,
			$this->monetary->currency_template($currency, $amount)
		);
	}

	public function data_round()
	{
		return array(
			 array(NULL,         2, '0.00'),
			 array(FALSE,        2, '0.00'),
			 array(0,            2, '0.00'),
			 array(0.00,         2, '0.00'),
			 array(10,           2, '10.00'),
			 array('10',         2, '10.00'),
			 array(10.00,        2, '10.00'),
			 array('10.00',      2, '10.00'),
			 array(359.21,       2, '359.21'),
			 array('359.21',     2, '359.21'),
			 array(34.5674374,   2, '34.57'),
			 array('34.5674374', 2, '34.57'),
			 array(.23423,       2, '0.23'),
			 array('.23423',     2, '0.23'),
			 array(.23453,       2, '0.23'),
			 array('.23453',     2, '0.23'),
			 array(NULL,         3, '0.000'),
			 array(FALSE,        3, '0.000'),
			 array(0,            3, '0.000'),
			 array(0.00,         3, '0.000'),
			 array(10,           3, '10.000'),
			 array('10',         3, '10.000'),
			 array(10.00,        3, '10.000'),
			 array('10.00',      3, '10.000'),
			 array(359.21,       3, '359.210'),
			 array('359.21',     3, '359.210'),
			 array(34.5674374,   3, '34.567'),
			 array('34.5674374', 3, '34.567'),
			 array(34.5675374,   3, '34.568'),
			 array('34.5675374', 3, '34.568'),
			 array(.23423,       3, '0.234'),
			 array('.23423',     3, '0.234'),
		);
	}

	/**
	 * @dataProvider data_round
	 * @covers OpenBuildings\Monetary\Monetary::round
	 */
	public function test_round($amount, $precision, $expected_result)
	{
		$this->assertSame(
			$expected_result,
			$this->monetary->round($amount, $precision)
		);
	}

	public function data_default_template()
	{
		return array(
			array('USD', NULL, ':amount USD'),
			array('EUR', NULL, ':amount EUR'),
			array('EUR', 0, ':amount EUR'),
			array('EUR', 55, ':amount EUR'),
			array('EUR', 102.53, ':amount EUR'),
			array('EUR', -102.53, '-:amount EUR'),
			array('USD', -55, '-:amount USD'),
		);
	}

	/**
	 * @dataProvider data_default_template
	 * @covers OpenBuildings\Monetary\Monetary::default_template
	 */
	public function test_default_template($currency, $amount, $expected_template)
	{
		$this->assertSame(
			$expected_template,
			$this->monetary->default_template($currency, $amount)
		);
	}
}
