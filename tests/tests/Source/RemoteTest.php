<?php

use OpenBuildings\Monetary as M;

/**
 * 
 * @author Yasen Yanev <yasen@openbuildings.com>
 * @author Ivan Kerin <ivan@openbuildings.com>
 * @author Haralan Dobrev <hdobrev@despark.com>
 * @copyright (c) 2013 OpenBuildings Inc.
 * @license http://spdx.org/licenses/BSD-3-Clause
 */
class Source_RemoteTest extends TestCase {

	/**
	 * @covers OpenBuildings\Monetary\Source_Remote::__construct
	 */
	public function test_constructor()
	{
		$mock_cache = $this->getMock('OpenBuildings\Monetary\Cache');
		
		$remote = new M\Source_ECB($mock_cache);

		$this->assertSame($mock_cache, $remote->cache());
	}

	/**
	 * @covers OpenBuildings\Monetary\Source_Remote::cache
	 */
	public function test_cache()
	{
		$remote = new M\Source_ECB;

		$this->assertInstanceOf(
			'OpenBuildings\Monetary\Cacheable',
			$remote->cache()
		);
		$this->assertInstanceOf(
			'OpenBuildings\Monetary\Cache',
			$remote->cache()
		);

		$mock_cache = $this->getMock('OpenBuildings\Monetary\Cache');
		$remote->cache($mock_cache);
		$this->assertInstanceOf(
			'OpenBuildings\Monetary\Cacheable',
			$remote->cache()
		);
		$this->assertInstanceOf(
			'OpenBuildings\Monetary\Cache',
			$remote->cache()
		);

		$this->assertSame($mock_cache, $remote->cache());
	}

	/**
	 * @covers OpenBuildings\Monetary\Source_Remote::request_driver
	 */
	public function test_request_driver()
	{
		$remote = new M\Source_ECB;

		$this->assertInstanceOf(
			'OpenBuildings\Monetary\Requestable',
			$remote->request_driver()
		);
		$this->assertInstanceOf(
			'OpenBuildings\Monetary\CURL',
			$remote->request_driver()
		);

		$mock_request_driver = $this->getMock('OpenBuildings\Monetary\CURL');
		$remote->request_driver($mock_request_driver);
		$this->assertInstanceOf(
			'OpenBuildings\Monetary\Requestable',
			$remote->request_driver()
		);
		$this->assertInstanceOf(
			'OpenBuildings\Monetary\CURL',
			$remote->request_driver()
		);

		$this->assertSame($mock_request_driver, $remote->request_driver());
	}

	/**
	 * @covers OpenBuildings\Monetary\Source_Remote::_exchange_rates
	 */
	public function test_exchange_rates()
	{
		$cache_mock = $this->getMock('OpenBuildings\Monetary\Cache', array(
			'read_cache',
			'write_cache'
		));
		
		$cache_mock
			->expects($this->at(0))
			->method('read_cache')
			->with($this->equalTo(M\Source_ECB::CACHE_KEY))
			->will($this->returnValue(FALSE));

		$cache_mock
			->expects($this->any())
			->method('read_cache')
			->with($this->equalTo(M\Source_ECB::CACHE_KEY))
			->will($this->returnValue('ABCDE'));

		$cache_mock
			->expects($this->once())
			->method('write_cache')
			->with(
				$this->equalTo(M\Source_ECB::CACHE_KEY),
				$this->equalTo('ABCDE')
			);

		$remote = $this->getMock('OpenBuildings\Monetary\Source_ECB', array(
			'_converted_exchange_rates'
		));

		$remote
			->expects($this->any())
			->method('_converted_exchange_rates')
			->will($this->returnValue('ABCDE'));

		$remote->cache($cache_mock);

		$exchange_rates = $remote->exchange_rates();
		$this->assertSame('ABCDE', $exchange_rates);

		$remote = $this->getMock('OpenBuildings\Monetary\Source_ECB', array(
			'_converted_exchange_rates'
		));

		$remote
			->expects($this->exactly(0))
			->method('_converted_exchange_rates');

		$remote->cache($cache_mock);
		$exchange_rates = $remote->exchange_rates();
		$this->assertSame('ABCDE', $exchange_rates);
	}

	/**
	 * @covers OpenBuildings\Monetary\Source_Remote::_converted_exchange_rates
	 */
	public function test_converted_exchange_rates()
	{
		$cache_mock = $this->getMock('OpenBuildings\Monetary\Cache', array(
			'read_cache',
			'write_cache'
		));

		$cache_mock
			->expects($this->any())
			->method('read_cache')
			->will($this->returnValue(FALSE));

		$cache_mock
			->expects($this->any())
			->method('write_cache');

		$remote = $this->getMock('OpenBuildings\Monetary\Source_ECB', array(
			'fetch_remote_data',
			'convert_to_array'
		));

		$remote
			->expects($this->at(0))
			->method('fetch_remote_data')
			->will($this->returnValue(''));

		$remote
			->expects($this->at(1))
			->method('fetch_remote_data')
			->will($this->returnValue('ABCDE'));

		$remote
			->expects($this->once())
			->method('convert_to_array')
			->with($this->equalTo('ABCDE'))
			->will($this->returnValue(array('ABCDE' => '1.00')));

		$remote->cache($cache_mock);

		$converted_exchange_rates = $remote->exchange_rates();
		$this->assertNull($converted_exchange_rates);
		$converted_exchange_rates = $remote->exchange_rates();

		$this->assertSame(array('ABCDE' => '1.00'), $converted_exchange_rates);
	}

	/**
	 * @covers OpenBuildings\Monetary\Source_Remote::_request
	 */
	public function test_request()
	{
		$cache_mock = $this->getMock('OpenBuildings\Monetary\Cache', array(
			'read_cache',
			'write_cache'
		));

		$cache_mock
			->expects($this->any())
			->method('read_cache')
			->will($this->returnValue(FALSE));

		$cache_mock
			->expects($this->any())
			->method('write_cache');

		$request_mock = $this->getMock('OpenBuildings\Monetary\CURL', array(
			'request'
		));

		$request_mock
			->expects($this->once())
			->method('request')
			->with($this->equalTo(array(
				CURLOPT_URL => M\Source_ECB::API_URL,
				CURLOPT_HTTPHEADER => array(
					'User-Agent: '.M\Source_ECB::USER_AGENT
				)
			)))
			->will($this->returnValue(self::ECB_XML_DATA));

		$remote = new M\Source_ECB($cache_mock, $request_mock);
		$exchange_rates = $remote->exchange_rates();
		$this->assertSame(array(
			'USD',
			'JPY',
			'BGN',
			'CZK',
			'DKK',
			'GBP',
			'HUF',
			'LTL',
			'LVL',
			'PLN',
			'RON',
			'SEK',
			'CHF',
			'NOK',
			'HRK',
			'RUB',
			'TRY',
			'AUD',
			'BRL',
			'CAD',
			'CNY',
			'HKD',
			'IDR',
			'ILS',
			'INR',
			'KRW',
			'MXN',
			'MYR',
			'NZD',
			'PHP',
			'SGD',
			'THB',
			'ZAR',
		), array_keys($exchange_rates));
	}

}