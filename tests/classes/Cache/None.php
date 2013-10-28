<?php

use OpenBuildings\Monetary\Cacheable;

/**
 * Non-caching cache used for testing purposes.
 * @author Haralan Dobrev <hkdobrev@gmail.com>
 * @copyright 2013 OpenBuildings, Inc.
 * @license http://spdx.org/licenses/BSD-3-Clause
 */
class Cache_None implements Cacheable {

	public function read_cache($cache_key)
	{
		return NULL;
	}

	public function write_cache($cache_key, $data)
	{
		return $this;
	}
}
