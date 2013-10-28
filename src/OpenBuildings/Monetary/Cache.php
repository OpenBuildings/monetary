<?php

namespace OpenBuildings\Monetary;

use Desarrolla2\Cache as DCache;
use Desarrolla2\Cache\Adapter as DCache_Adapter;

/**
 * Simple cache using Desarrolla2\Cache.
 * @author Haralan Dobrev <hdobrev@despark.com>
 * @copyright (c) 2013 OpenBuildings Inc.
 * @license http://spdx.org/licenses/BSD-3-Clause
 */
class Cache implements Cacheable {

	/**
	 * Cache will be valid for 1 day
	 */
	const CACHE_LIFETIME = 86400;

	const CACHE_DIR = 'cache';

	/**
	 * @var Desarrolla2\Cache\Cache
	 */
	protected $_cache;

	/**
	 * Get an instance of the cache helper
	 * @return Desarrolla2\Cache\Cache
	 */
	public function cache_driver()
	{
		if ( ! $this->_cache)
		{
			$adapter = new DCache_Adapter\File(
				realpath(__DIR__.'../../../'.static::CACHE_DIR)
			);
			$adapter->setOption('ttl', static::CACHE_LIFETIME);
			$this->_cache = new DCache\Cache($adapter);
		}

		return $this->_cache;
	}

	public function read_cache($cache_key)
	{
		return $this->cache_driver()->get($cache_key);
	}

	public function write_cache($cache_key, $data)
	{
		$this->cache_driver()->set($cache_key, $data);

		return $this;
	}

}