<?php

namespace OpenBuildings\Monetary;

/**
 * Cacheable source.
 * @author Haralan Dobrev <hdobrev@despark.com>
 * @copyright (c) 2013 OpenBuildings Inc.
 * @license http://spdx.org/licenses/BSD-3-Clause
 */
interface Source_Cacheable {

	/**
	 * Get or set the cache driver for the source
	 * @param  Cacheable $cache cache driver
	 * @return Cacheable|$this Cacheable when getting; $this when setting
	 */
	public function cache(Cacheable $cache = NULL);
}