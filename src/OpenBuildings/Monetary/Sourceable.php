<?php

namespace OpenBuildings\Monetary;

/**
 * Source of currency exchange rates.
 * @author Haralan Dobrev <hdobrev@despark.com>
 * @copyright (c) 2013 OpenBuildings Inc.
 * @license http://spdx.org/licenses/BSD-3-Clause
 */
interface Sourceable extends \Serializable {

	/**
	 * Get exchange rates
	 * @return array single-dimension assoc array with currency codes as keys
	 * and exchange rates as value.
	 */
	public function exchange_rates();
}