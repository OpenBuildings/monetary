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

	const USER_AGENT = 'OpenBuildings Monetary';

	const CACHE_KEY = 'openbuildings-monetary-remote';

	const DEFAULT_CACHE = 'OpenBuildings\Monetary\Cache';

	const DEFAULT_REQUEST_DRIVER = 'OpenBuildings\Monetary\CURL';

	/**
	 * Exchange rates
	 * @var array
	 */
	protected $_exchange_rates;

	/**
	 * @var OpenBuildings\Monetary\Requestable
	 */
	protected $_request_driver;

	/**
	 * @var OpenBuildings\Monetary\Cacheable
	 */
	protected $_cache;

	/**
	 * Build a remote source with optional cache and request drivers
	 * @param Cacheable $cache cache driver
	 * @param Requestable $request_driver request driver
	 */
	public function __construct(Cacheable $cache = NULL, Requestable $request_driver = NULL)
	{
		$this->cache($cache);
		$this->request_driver($request_driver);
	}

	public function exchange_rates()
	{
		if ( ! $this->_exchange_rates AND
		 ! ($this->_exchange_rates = $this
		 	->cache()
		 	->read_cache(static::CACHE_KEY)))
		{
			$this->_exchange_rates = $this->_converted_exchange_rates();
			$this->cache()->write_cache(static::CACHE_KEY, $this->_exchange_rates);
		}

		return $this->_exchange_rates;
	}

	public function unserialize($data)
	{
		$this->_exchange_rates = unserialize($data);
	}

	/**
	 * Get an instance of the cache driver
	 * @return OpenBuildings\Monetary\Cacheable
	 */
	public function cache(Cacheable $cache = NULL)
	{
		if ( ! $cache)
		{
			if ( ! $this->_cache)
			{
				$default_cache = static::DEFAULT_CACHE;
				$this->_cache = new $default_cache;
			}

			if ( ! $cache)
				return $this->_cache;
		} // @codeCoverageIgnore

		$this->_cache = $cache;

		return $this;
	}

	/**
	 * Get an instance of the request driver
	 * @return OpenBuildings\Monetary\Requestable
	 */
	public function request_driver(Requestable $request_driver = NULL)
	{
		if ( ! $request_driver)
		{
			if ( ! $this->_request_driver)
			{
				$default_request_driver = static::DEFAULT_REQUEST_DRIVER;
				$this->_request_driver = new $default_request_driver;
			}

			if ( ! $request_driver)
				return $this->_request_driver;
		} // @codeCoverageIgnore

		$this->_request_driver = $request_driver;

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


	protected function _converted_exchange_rates()
	{
		if ( ! ($raw_data = $this->fetch_remote_data()))
			return NULL;

		return $this->convert_to_array($raw_data);
	}

	/**
	 * Perform a cURL request
	 * @param  string $url          API endpoint
	 * @param  array $curl_options  cURL options
	 * @return string               raw response
	 * @uses OpenBuildings\Monetary\CURL::request for the actual cURL request
	 */
	protected function _request($url, array $curl_options = NULL)
	{
		$options = array(CURLOPT_URL => $url);

		$curl_options = (array) $curl_options;

		$headers = empty($curl_options[CURLOPT_HTTPHEADER])
			? array()
			: $curl_options[CURLOPT_HTTPHEADER];

		unset($curl_options[CURLOPT_HTTPHEADER]);

		$headers []= 'User-Agent: '.static::USER_AGENT;

		$options[CURLOPT_HTTPHEADER] = $headers;

		return $this->request_driver()->request($options + $curl_options);
	}
}