<?php

/**
 * Test currency conversions
 * @author Yasen Yanev <yasen@openbuildings.com>
 * @author Ivan Kerin <ivan@openbuildings.com>
 * @author Haralan Dobrev <hdobrev@despark.com>
 * @copyright (c) 2013 OpenBuildings Inc.
 * @license http://spdx.org/licenses/BSD-3-Clause
 */
class Monetary_ConvertTest extends Monetary_TestCase {

	public function data_exchange_rate()
	{
		return array(
			array(NULL,  NULL,  1.00),
			array('USD', NULL,  1.00),
			array(NULL,  'USD', 1.00),
			array('GBP', 'USD', 1.5860179836837),
			array('USD', 'GBP', 0.63050987459638),
			array('GBP', NULL,  1.5860179836837),
			array(NULL, 'GBP',  0.63050987459638),
			array('EUR', 'GBP', 0.83965),
			array('GBP', 'EUR', 1.1909724289883),
		);
	}

	/**
	 * @dataProvider data_exchange_rate
	 * @covers OpenBuildings\Monetary\Monetary::exchange_rate
	 */
	public function test_exchange_rate($currency, $target_currency, $expected_rate)
	{
		$this->assertSame($expected_rate, $this->monetary->exchange_rate($currency, $target_currency));
	}

	public function data_convert()
	{
		return array(
			array(0,     NULL,  NULL,  0.00),
			array(FALSE, NULL,  NULL,  0.00),
			array(NULL,  NULL,  NULL,  0.00),
			array(10,    NULL,  'USD', 10.00),
			array(10,    'USD',  NULL, 10.00),
			array(10,    NULL,  'EUR', 7.5091987684914),
			array(10,    'EUR',  NULL, 13.317),
			array(10,    'EUR', 'BGN', 19.558),
			array(10,    'EUR', 'GBP', 8.3965),
			array(10,    'GBP', 'BGN', 23.293038766153),
			array(10,    'USD', 'EUR', 7.5091987684914),
		);
	}

	/**
	 * @dataProvider data_convert
	 * @covers OpenBuildings\Monetary\Monetary::convert
	 */
	public function test_convert($amount, $currency, $target_currency, $converted_amount)
	{
		$this->assertEquals($converted_amount, $this->monetary->convert($amount, $currency, $target_currency));
	}

	public function data_convert_and_format()
	{
		return array(
			array(10, NULL,  'USD', '$10.00'),
			array(10, 'USD', NULL,  '$10.00'),
			array(10, NULL,  'EUR', '€7.51'),
			array(10, 'EUR', NULL,  '$13.32'),
			array(10, 'EUR', 'BGN', '19.56 лв'),
			array(10, 'EUR', 'GBP', '£8.40'),
			array(10, 'GBP', 'BGN', '23.29 лв'),
			array(10, 'USD', 'EUR', '€7.51'),
		);
	}

	/**
	 * @dataProvider data_convert_and_format
	 * @covers OpenBuildings\Monetary\Monetary::convert_and_format
	 */
	public function test_convert_and_format($amount, $currency, $target_currency, $formatted_amount)
	{
		$this->assertEquals($formatted_amount, $this->monetary->convert_and_format($amount, $currency, $target_currency));
	}
}
