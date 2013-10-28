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
class Source_ECBTest extends TestCase {

	/**
	 * @covers OpenBuildings\Monetary\Source_ECB::convert_to_array
	 */
	public function test_convert_to_array()
	{

		$ecb_source = new M\Source_ECB;
		$this->assertEquals(array(
			'USD' => '1.3392',
			'JPY' => '130.33',
			'BGN' => '1.9558',
			'CZK' => '25.773',
			'DKK' => '7.4579',
			'GBP' => '0.85490',
			'HUF' => '299.25',
			'LTL' => '3.4528',
			'LVL' => '0.7027',
			'PLN' => '4.2267',
			'RON' => '4.4455',
			'SEK' => '8.7061',
			'CHF' => '1.2323',
			'NOK' => '7.9860',
			'HRK' => '7.5395',
			'RUB' => '44.1511',
			'TRY' => '2.6091',
			'AUD' => '1.4761',
			'BRL' => '3.2091',
			'CAD' => '1.3897',
			'CNY' => '8.2021',
			'HKD' => '10.3848',
			'IDR' => '14367.74',
			'ILS' => '4.7733',
			'INR' => '85.1330',
			'KRW' => '1499.19',
			'MXN' => '17.4437',
			'MYR' => '4.4006',
			'NZD' => '1.6780',
			'PHP' => '58.775',
			'SGD' => '1.7087',
			'THB' => '42.399',
			'ZAR' => '13.6055',
		), $ecb_source->convert_to_array(self::ECB_XML_DATA));
	}

	/**
	 * @covers OpenBuildings\Monetary\Source_ECB::fetch_remote_data
	 */
	public function test_fetch_remote_data()
	{
		$ecb_mock = $this->getMock('OpenBuildings\Monetary\Source_ECB', array('_request'));

		$ecb_mock
			->expects($this->once())
			->method('_request')
			->with($this->equalTo(M\Source_ECB::API_URL))
			->will($this->returnValue('ABCDE'));

		$result = $ecb_mock->fetch_remote_data();
		$this->assertEquals('ABCDE', $result);
	}
}
