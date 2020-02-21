<?php

use OpenBuildings\Monetary as M;

/**
 * @author Haralan Dobrev <hdobrev@despark.com>
 * @copyright (c) 2013 OpenBuildings Inc.
 * @license http://spdx.org/licenses/BSD-3-Clause
 */
class Monetary_InstanceTest extends \PHPUnit\Framework\TestCase {

	/**
	 * @covers OpenBuildings\Monetary\Monetary::instance
	 */
	public function test_default_instance()
	{
		$monetary = M\Monetary::instance();

		$this->assertSame($monetary, M\Monetary::instance('default'));
	}

	/**
	 * @covers OpenBuildings\Monetary\Monetary::instance
	 */
	public function test_same_instance()
	{
		$monetary = M\Monetary::instance('same_instance');
		$this->assertSame($monetary, M\Monetary::instance('same_instance'));
	}

	/**
	 * @covers OpenBuildings\Monetary\Monetary::instance
	 */
	public function test_same_instance_with_source()
	{
		$monetary = M\Monetary::instance('same_instance_with_source');
		$this->assertSame($monetary, M\Monetary::instance('same_instance_with_source'));
	}

	/**
	 * @covers OpenBuildings\Monetary\Monetary::instance
	 * @covers OpenBuildings\Monetary\Monetary::default_currency
	 */
	public function test_same_instance_with_currency()
	{
		$monetary = M\Monetary::instance('same_instance_with_currency');
		$this->assertSame($monetary, M\Monetary::instance('same_instance_with_currency'));
		$monetary->default_currency('EUR');
		$this->assertSame($monetary, M\Monetary::instance('same_instance_with_currency'));
		$this->assertSame($monetary->default_currency(), M\Monetary::instance('same_instance_with_currency')->default_currency());
		$this->assertSame('EUR', M\Monetary::instance('same_instance_with_currency')->default_currency());
	}
}
