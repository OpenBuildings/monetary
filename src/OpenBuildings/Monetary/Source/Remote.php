<?php

namespace OpenBuildings\Monetary;

/**
 * Fetch exchange rates from a remote service
 * @author Yasen Yanev <yasen@openbuildings.com>
 * @author Ivan Kerin <ivan@openbuildings.com>
 * @author Haralan Dobrev <hdobrev@despark.com>
 * @copyright (c) 2013 OpenBuildings Inc.
 * @license http://spdx.org/licenses/BSD-3-Clause
 */
abstract class Source_Remote extends Source implements Source_Cacheable {

	const NAME = 'remote';
	
	const USER_AGENT = 'OpenBuildings Monetary';

	const CACHE_KEY = 'openbuildings-monetary';

	const DEFAULT_CACHE = 'OpenBuildings\Monetary\Cache';

	/**
	 * @var OpenBuildings\Monetary\Cacheable
	 */
	protected $_cache;

	public function __construct(Cacheable $cache = NULL)
	{
		$this->cache($cache);
	}

	/**
	 * Get an instance of the cache helper
	 * @return Desarrolla2\Cache\Cache
	 */
	public function cache(Cacheable $cache = NULL)
	{
		if ( ! $this->_cache AND ! $cache)
		{
			$default_cache = self::DEFAULT_CACHE;
			$this->_cache = new $default_cache;
		}

		if ( ! $cache)
			return $this->_cache;

		$this->_cache = $cache;

		return $this;
	}

	/**
	 * Fetch remote data
	 * @return string raw response
	 */
	abstract public function fetch_remote_data();

	/**
	 * Convert raw currency data to array of currencies
	 * @param  string $raw_data currency data
	 * @return array                 currencies
	 */
	abstract public function convert_to_array($raw_data);

	protected function _exchange_rates()
	{
		if ( ! $exchange_rates = $this->cache()->read_cache(self::CACHE_KEY))
		{
			$exchange_rates = $this->_converted_exchange_rates();
			$this->cache()->write_cache(self::CACHE_KEY, $exchange_rates);
		}

		return $exchange_rates;
	}

	protected function _converted_exchange_rates()
	{
		if ( ! ($raw_data = $this->fetch_remote_data()))
			return NULL;

		return $this->convert_to_array($raw_data);
	}

	/**
	 * Perform a cURL request 
	 * @param  string $url          API endpoint
	 * @param  array $post_data     POST data
	 * @param  array $headers       HTTP headers
	 * @param  array $curl_options  cURL options
	 * @return string               raw response
	 * @throws Exception_Source If the remote returns an error.
	 */
	protected function _request($url, array $post_data = NULL, array $headers = NULL, array $curl_options = NULL)
	{
		// Create a new curl instance
		$curl = curl_init($url);

		$options = array(
			CURLOPT_SSL_VERIFYPEER => FALSE,
			CURLOPT_SSL_VERIFYHOST => FALSE,
			CURLOPT_RETURNTRANSFER => TRUE,
			CURLOPT_FOLLOWLOCATION => TRUE,
			CURLOPT_MAXREDIRS      => 10
		);

		if ($post_data)
		{
			$options[CURLOPT_POSTFIELDS] = http_build_query($post_data, NULL, '&');
			$options[CURLOPT_POST] = TRUE;
		}

		$headers = (array) $headers;

		$headers []= 'User-Agent: '.self::USER_AGENT;

		$options[CURLOPT_HTTPHEADER] = $headers;

		// Set curl options
		curl_setopt_array($curl, array_merge($options, (array) $curl_options));

		// Execute response
		if (($response_string = curl_exec($curl)) === FALSE)
		{
			// Get the error code and message
			$code  = curl_errno($curl);
			$error = curl_error($curl);

			// Close curl
			curl_close($curl);

			throw new Exception_Source('Fetching :source_name data failed! :error (:code)', self::NAME, array(
				':error' => $error,
				':code' => $code
			));
		}

		// Close curl
		curl_close($curl);

		return $response_string;
	}
}