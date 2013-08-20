<?php

namespace OpenBuildings\Monetary;

/**
 * Cacheable source.
 * @author Haralan Dobrev <hdobrev@despark.com>
 * @copyright (c) 2013 OpenBuildings Inc.
 * @license http://spdx.org/licenses/BSD-3-Clause
 */
interface Source_Cacheable {

	public function cache(Cacheable $cache = NULL);
}