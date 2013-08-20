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
class Source_RemoteTest extends Monetary_TestCase {

	/**
	 * @covers OpenBuildings\Monetary\Source_Remote::__construct
	 */
	public function test_constructor()
	{
		$mock_cache = $this->getMock('OpenBuildings\Monetary\Cache');
		
		$remote = new M\Source_ECB($mock_cache);

		$this->assertSame($mock_cache, $remote->cache());
	}

	/**
	 * @covers OpenBuildings\Monetary\Source_Remote::cache
	 */
	public function test_cache()
	{
		$remote = new M\Source_ECB;

		$this->assertInstanceOf('OpenBuildings\Monetary\Cacheable', $remote->cache());
		$this->assertInstanceOf('OpenBuildings\Monetary\Cache', $remote->cache());

		$mock_cache = $this->getMock('OpenBuildings\Monetary\Cache');
		$remote->cache($mock_cache);
		$this->assertInstanceOf('OpenBuildings\Monetary\Cacheable', $remote->cache());
		$this->assertInstanceOf('OpenBuildings\Monetary\Cache', $remote->cache());

		$this->assertSame($mock_cache, $remote->cache());
	}

	/**
	 * @covers OpenBuildings\Monetary\Source_Remote::_exchange_rates
	 */
	public function test_exchange_rates()
	{
		$this->markTestIncomplete();
	}

	/**
	 * @covers OpenBuildings\Monetary\Source_Remote::_converted_exchange_rates
	 */
	public function test_converted_exchange_rates()
	{
		$this->markTestIncomplete();
	}

	/**
	 * @covers OpenBuildings\Monetary\Source_Remote::_request
	 */
	public function test_request()
	{
		$this->markTestIncomplete();
	}

}