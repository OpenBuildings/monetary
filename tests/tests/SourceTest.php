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

}