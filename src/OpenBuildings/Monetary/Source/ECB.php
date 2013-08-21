<?php

namespace OpenBuildings\Monetary;

/**
 * Fetch exchange rates from European Central Bank
 * @author Yasen Yanev <yasen@openbuildings.com>
 * @author Ivan Kerin <ivan@openbuildings.com>
 * @author Haralan Dobrev <hdobrev@despark.com>
 * @copyright (c) 2013 OpenBuildings Inc.
 * @license http://spdx.org/licenses/BSD-3-Clause
 */
class Source_ECB extends Source_Remote {

	const CACHE_KEY = 'openbuildings-monetary-ecb';

	const API_URL = 'http://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml';

	const XML_NAMESPACE = 'ecb';

	const XML_NAMESPACE_URL = 'http://www.ecb.int/vocabulary/2002-08-01/eurofxref';

	const XPATH_CURRENCY = '//ecb:Cube[@currency]';

	/**
	 * Convert raw currency data to array of currencies
	 * @param  string $raw_data currency data in XML format
	 * @return array            currencies
	 */
	public function convert_to_array($raw_data)
	{
		$xml = new \SimpleXMLElement($raw_data);
		$xml->registerXPathNamespace(
			self::XML_NAMESPACE,
			self::XML_NAMESPACE_URL
		);

		$data = $xml->xpath(self::XPATH_CURRENCY);
		$currencies = array();

		foreach ($data as $currency) 
		{	
			$currencies[ (string) $currency->attributes()->currency] = (string) $currency->attributes()->rate;	
		}

		return $currencies;
	}

	public function fetch_remote_data()
	{
		return $this->_request(self::API_URL);
	}
}