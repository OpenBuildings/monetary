<?php

namespace OpenBuildings\Monetary;

/**
 * Static source of exchange rates.
 * Useful for testing or when network requests are not desired or possible.
 * @author Yasen Yanev <yasen@openbuildings.com>
 * @author Ivan Kerin <ivan@openbuildings.com>
 * @author Haralan Dobrev <hdobrev@despark.com>
 * @copyright (c) 2013 OpenBuildings Inc.
 * @license http://spdx.org/licenses/BSD-3-Clause
 */
class Source_Static extends Source {
	
	protected function _exchange_rates()
	{
		return array(
			'EUR' => '1.00',
			'USD' => '1.3317',
			'JPY' => '118.18',
			'BGN' => '1.9558',
			'CZK' => '25.613',
			'DKK' => '7.4636',
			'GBP' => '0.83965',
			'HUF' => '294.32',
			'LTL' => '3.4528',
			'LVL' => '0.6978',
			'PLN' => '4.1737',
			'RON' => '4.3572',
			'SEK' => '8.6909',
			'CHF' => '1.2383',
			'NOK' => '7.4420',
			'HRK' => '7.5833',
			'RUB' => '40.2588',
			'TRY' => '2.3593',
			'AUD' => '1.2613',
			'BRL' => '2.7279',
			'CAD' => '1.3242',
			'CNY' => '8.2840',
			'HKD' => '10.3244',
			'IDR' => '12823.79',
			'ILS' => '4.9837',
			'INR' => '71.5390',
			'KRW' => '1416.26',
			'MXN' => '16.9153',
			'MYR' => '4.0517',
			'NZD' => '1.5837',
			'PHP' => '54.096',
			'SGD' => '1.6344',
			'THB' => '39.605',
			'ZAR' => '11.7943',
		);
	}
}