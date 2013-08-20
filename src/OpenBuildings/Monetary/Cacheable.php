<?php

namespace OpenBuildings\Monetary;

/**
 * Cacheable interface.
 * @author Haralan Dobrev <hdobrev@despark.com>
 * @copyright (c) 2013 OpenBuildings Inc.
 * @license http://spdx.org/licenses/BSD-3-Clause
 */
interface Cacheable {

	public function read_cache($cache_key);

	public function write_cache($cache_key, $data);
}