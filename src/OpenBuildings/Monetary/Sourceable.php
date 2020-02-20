<?php

namespace OpenBuildings\Monetary;

use Serializable;

/**
 * Source of currency exchange rates.
 * @author Haralan Dobrev <hdobrev@despark.com>
 * @copyright (c) 2013 OpenBuildings Inc.
 * @license http://spdx.org/licenses/BSD-3-Clause
 */
interface Sourceable extends Serializable {

	/**
	 * Get exchange rate between two currencies
	 * @param string $from the source currency
	 * @param string $to the target currency
	 * @return float the exchange rate between the two currencies
	 */
	public function rate($from, $to);
}