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
class SourceTest extends PHPUnit_Framework_TestCase {

	public function test_sourceable()
	{
		$cache = new M\Source_Static;
		$this->assertInstanceOf('OpenBuildings\Monetary\Sourceable', $cache);
	}

	/**
	 * @covers OpenBuildings\Monetary\Source::rate
	 */
	public function test_rate_without_currency_data()
	{
		$source = $this->getMock('OpenBuildings\Monetary\Source_Static', array(
			'exchange_rates'
		));

		$source
			->expects($this->once())
			->method('exchange_rates')
			->will($this->returnValue(FALSE));

		$this->assertSame(1.00, $source->rate('ABC', 'XST'));
	}

	public function data_rate()
	{
		return array(
			array(
				array(
					'USD' => '1.5',
					'GBP' => '0.5'
				),
				'GBP',
				'USD',
				3.00
			),
			array(
				array(
					'USD' => '1.5',
				),
				'USD',
				'EUR',
				0.66666666666667
			),
			array(
				array(
					'USD' => '1.5',
				),
				'EUR',
				'USD',
				1.5
			),
			array(
				array(
					'USD' => '1.5',
					'EUR' => '2',
				),
				'USD',
				'EUR',
				1.3333333333333
			),
			array(
				array(
					'USD' => '1.5',
					'EUR' => '2',
				),
				'EUR',
				'USD',
				0.75
			),
			array(
				array(
					'USD' => '1.5',
					'GBP' => '0.5',
					'EUR' => '2.00'
				),
				'GBP',
				'USD',
				3.00
			),
			array(
				array(
					'USD' => '1.5',
					'GBP' => '0.5',
					'EUR' => '2.00'
				),
				'USD',
				'GBP',
				0.33333333333333
			),
			array(
				array(
					'USD' => '1.5',
					'GBP' => '0.5',
					'EUR' => '2.00'
				),
				'USD',
				'EUR',
				1.3333333333333
			),
			array(
				array(
					'USD' => '1.5',
					'GBP' => '0.5',
					'EUR' => '2.00'
				),
				'EUR',
				'USD',
				0.75
			),
			array(
				array(
					'USD' => '1.5',
					'GBP' => '0.5',
					'EUR' => '2.00'
				),
				'EUR',
				'GBP',
				0.25
			),
			array(
				array(
					'USD' => '1.5',
					'GBP' => '0.5',
					'EUR' => '2.00'
				),
				'GBP',
				'EUR',
				4.0
			),
		);
	}

	/**
	 * @dataProvider data_rate
	 * @covers OpenBuildings\Monetary\Source::rate
	 */
	public function test_rate($currency_data, $from, $to, $expected_rate)
	{
		$source = new M\Source_Static($currency_data);
		$this->assertSame($expected_rate, $source->rate($from, $to));
	}

	/**
	 * @covers OpenBuildings\Monetary\Source::exchange_rates
	 */
	public function test_exchange_rates()
	{
		$source_mock = $this->getMock('OpenBuildings\Monetary\Source_Static', array('_exchange_rates'));

		$expected_rates = array('ABC' => '1.25');

		$source_mock
			->expects($this->once())
			->method('_exchange_rates')
			->will($this->returnValue($expected_rates));

		$this->assertSame($expected_rates, $source_mock->exchange_rates());
		$this->assertSame($expected_rates, $source_mock->exchange_rates());
	}

	/**
	 * @covers OpenBuildings\Monetary\Source::serialize
	 * @covers OpenBuildings\Monetary\Source::unserialize
	 * @covers OpenBuildings\Monetary\Source_Static::unserialize
	 */
	public function test_serialize()
	{
		$rates = array('XXX' => '12.5');
		$source = new M\Source_Static($rates);
		$unserialized_source = unserialize(serialize($source));
		$this->assertEquals($source, $unserialized_source);
		$this->assertSame($rates, $unserialized_source->exchange_rates());
	}

}