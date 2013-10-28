<?php

namespace OpenBuildings\Monetary;

/**
 * Abstract Source class.
 * @author Yasen Yanev <yasen@openbuildings.com>
 * @author Ivan Kerin <ivan@openbuildings.com>
 * @author Haralan Dobrev <hdobrev@despark.com>
 * @copyright (c) 2013 OpenBuildings Inc.
 * @license http://spdx.org/licenses/BSD-3-Clause
 */
abstract class Source implements Sourceable {

	public function rate($from, $to)
	{
		if ( ! ($currency_data = $this->exchange_rates()))
			return 1.00;

		$from_rate = empty($currency_data[$from]) ? 1 : $currency_data[$from];
		$to_rate = empty($currency_data[$to]) ? 1 : $currency_data[$to];

		return (float) $to_rate / (float) $from_rate;
	}

	public function serialize()
	{
		return serialize($this->exchange_rates());
	}

	abstract public function exchange_rates();
}