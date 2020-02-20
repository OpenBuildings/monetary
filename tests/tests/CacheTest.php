<?php

use Desarrolla2\Cache\Cache;
use OpenBuildings\Monetary as M;
use Desarrolla2\Cache as DCache;

/**
 * Test OpenBuildings\Monetary\Cache class.
 * @author Haralan Dobrev <hdobrev@despark.com>
 * @copyright (c) 2013 OpenBuildings Inc.
 * @license http://spdx.org/licenses/BSD-3-Clause
 */
class Monetary_CacheTest extends \PHPUnit\Framework\TestCase {

	public function test_cacheable()
	{
		$cache = new M\Cache;
		$this->assertInstanceOf(M\Cacheable::class, $cache);
	}

	/**
	 * @covers OpenBuildings\Monetary\Cache::cache_driver
	 */
	public function test_cache_driver()
	{
		$cache = new M\Cache;
		$cache_driver = $cache->cache_driver();
		$this->assertInstanceOf(Cache::class, $cache_driver);

		$this->assertSame($cache_driver, $cache->cache_driver());
	}

	/**
	 * @covers OpenBuildings\Monetary\Cache::read_cache
	 */
	public function test_read_cache()
	{
		$cache_key = 'some-cache-key';

        $cache_driver_mock = $this
            ->getMockBuilder(Cache::class)
            ->setMethods(['get'])
            ->getMock();

        $cache_mock = $this
            ->getMockBuilder(M\Cache::class)
            ->setMethods(['cache_driver'])
            ->getMock();

		$cache_mock
			->expects($this->once())
			->method('cache_driver')
			->will($this->returnValue($cache_driver_mock));

		$cache_driver_mock
			->expects($this->once())
			->method('get')
			->with($this->equalTo($cache_key));

		$cache_mock->read_cache($cache_key);
	}

	/**
	 * @covers OpenBuildings\Monetary\Cache::write_cache
	 */
	public function test_write_cache()
	{
		$cache_key = 'some-cache-key';
		$data = 'some-cache-data';

        $cache_driver_mock = $this
            ->getMockBuilder(Cache::class)
            ->setMethods(['set'])
            ->getMock();

        $cache_mock = $this
            ->getMockBuilder(M\Cache::class)
            ->setMethods(['cache_driver'])
            ->getMock();

		$cache_mock
			->expects($this->once())
			->method('cache_driver')
			->will($this->returnValue($cache_driver_mock));

		$cache_driver_mock
			->expects($this->once())
			->method('set')
			->with($this->equalTo($cache_key), $this->equalTo($data));

		$cache_mock->write_cache($cache_key, $data);
	}

}
