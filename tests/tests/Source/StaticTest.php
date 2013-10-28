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
class Source_StaticTest extends TestCase {

	/**
	 * @covers OpenBuildings\Monetary\Source_Static::__construct
	 */
	public function test_constructor()
	{
		$rates = array('XXX' => '1.54');
		$source = new M\Source_Static($rates);
		$this->assertEquals($rates, $source->exchange_rates());
	}

	/**
	 * @covers OpenBuildings\Monetary\Source_Static::exchange_rates
	 */
	public function test_exchange_rates()
	{
		$source = new M\Source_Static;
		$exchange_rates = $source->exchange_rates();
		$this->assertArrayHasKey('USD', $exchange_rates);
		$this->assertArrayHasKey('BGN', $exchange_rates);
		$this->assertArrayHasKey('GBP', $exchange_rates);
		$this->assertArrayHasKey('EUR', $exchange_rates);
		$this->assertArrayHasKey('JPY', $exchange_rates);
	}

	/**
	 * @covers OpenBuildings\Monetary\Source::serialize
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
