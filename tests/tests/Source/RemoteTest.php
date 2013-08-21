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

		$this->assertInstanceOf(
			'OpenBuildings\Monetary\Cacheable',
			$remote->cache()
		);
		$this->assertInstanceOf(
			'OpenBuildings\Monetary\Cache',
			$remote->cache()
		);

		$mock_cache = $this->getMock('OpenBuildings\Monetary\Cache');
		$remote->cache($mock_cache);
		$this->assertInstanceOf(
			'OpenBuildings\Monetary\Cacheable',
			$remote->cache()
		);
		$this->assertInstanceOf(
			'OpenBuildings\Monetary\Cache',
			$remote->cache()
		);

		$this->assertSame($mock_cache, $remote->cache());
	}

	/**
	 * @covers OpenBuildings\Monetary\Source_Remote::_exchange_rates
	 */
	public function test_exchange_rates()
	{
		$cache_mock = $this->getMock('OpenBuildings\Monetary\Cache', array(
			'read_cache',
			'write_cache'
		));
		
		$cache_mock
			->expects($this->at(0))
			->method('read_cache')
			->with($this->equalTo(M\Source_ECB::CACHE_KEY))
			->will($this->returnValue(FALSE));

		$cache_mock
			->expects($this->any())
			->method('read_cache')
			->with($this->equalTo(M\Source_ECB::CACHE_KEY))
			->will($this->returnValue('ABCDE'));

		$cache_mock
			->expects($this->once())
			->method('write_cache')
			->with(
				$this->equalTo(M\Source_ECB::CACHE_KEY),
				$this->equalTo('ABCDE')
			);

		$remote = $this->getMock('OpenBuildings\Monetary\Source_ECB', array(
			'_converted_exchange_rates'
		));

		$remote
			->expects($this->any())
			->method('_converted_exchange_rates')
			->will($this->returnValue('ABCDE'));

		$remote->cache($cache_mock);

		$exchange_rates = $remote->exchange_rates();
		$this->assertSame('ABCDE', $exchange_rates);

		$remote = $this->getMock('OpenBuildings\Monetary\Source_ECB', array(
			'_converted_exchange_rates'
		));

		$remote
			->expects($this->exactly(0))
			->method('_converted_exchange_rates');

		$remote->cache($cache_mock);
		$exchange_rates = $remote->exchange_rates();
		$this->assertSame('ABCDE', $exchange_rates);
	}

	/**
	 * @covers OpenBuildings\Monetary\Source_Remote::_converted_exchange_rates
	 */
	public function test_converted_exchange_rates()
	{
		$cache_mock = $this->getMock('OpenBuildings\Monetary\Cache', array(
			'read_cache',
			'write_cache'
		));

		$cache_mock
			->expects($this->any())
			->method('read_cache')
			->will($this->returnValue(FALSE));

		$cache_mock
			->expects($this->any())
			->method('write_cache');

		$remote = $this->getMock('OpenBuildings\Monetary\Source_ECB', array(
			'fetch_remote_data',
			'convert_to_array'
		));

		$remote
			->expects($this->at(0))
			->method('fetch_remote_data')
			->will($this->returnValue(''));

		$remote
			->expects($this->at(1))
			->method('fetch_remote_data')
			->will($this->returnValue('ABCDE'));

		$remote
			->expects($this->once())
			->method('convert_to_array')
			->with($this->equalTo('ABCDE'))
			->will($this->returnValue(array('ABCDE' => '1.00')));

		$remote->cache($cache_mock);

		$converted_exchange_rates = $remote->exchange_rates();
		$this->assertNull($converted_exchange_rates);
		$converted_exchange_rates = $remote->exchange_rates();

		$this->assertSame(array('ABCDE' => '1.00'), $converted_exchange_rates);
	}

	/**
	 * @covers OpenBuildings\Monetary\Source_Remote::_request
	 */
	public function test_request()
	{
		$this->markTestIncomplete();
	}

}