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
class Source_ECBTest extends Monetary_TestCase {

	/**
	 * @covers OpenBuildings\Monetary\Source_ECB::convert_to_array
	 */
	public function test_convert_to_array()
	{
		$xml_data = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<gesmes:Envelope xmlns:gesmes="http://www.gesmes.org/xml/2002-08-01" xmlns="http://www.ecb.int/vocabulary/2002-08-01/eurofxref">
	<gesmes:subject>Reference rates</gesmes:subject>
	<gesmes:Sender>
		<gesmes:name>European Central Bank</gesmes:name>
	</gesmes:Sender>
	<Cube>
		<Cube time='2013-08-20'>
			<Cube currency='USD' rate='1.3392'/>
			<Cube currency='JPY' rate='130.33'/>
			<Cube currency='BGN' rate='1.9558'/>
			<Cube currency='CZK' rate='25.773'/>
			<Cube currency='DKK' rate='7.4579'/>
			<Cube currency='GBP' rate='0.85490'/>
			<Cube currency='HUF' rate='299.25'/>
			<Cube currency='LTL' rate='3.4528'/>
			<Cube currency='LVL' rate='0.7027'/>
			<Cube currency='PLN' rate='4.2267'/>
			<Cube currency='RON' rate='4.4455'/>
			<Cube currency='SEK' rate='8.7061'/>
			<Cube currency='CHF' rate='1.2323'/>
			<Cube currency='NOK' rate='7.9860'/>
			<Cube currency='HRK' rate='7.5395'/>
			<Cube currency='RUB' rate='44.1511'/>
			<Cube currency='TRY' rate='2.6091'/>
			<Cube currency='AUD' rate='1.4761'/>
			<Cube currency='BRL' rate='3.2091'/>
			<Cube currency='CAD' rate='1.3897'/>
			<Cube currency='CNY' rate='8.2021'/>
			<Cube currency='HKD' rate='10.3848'/>
			<Cube currency='IDR' rate='14367.74'/>
			<Cube currency='ILS' rate='4.7733'/>
			<Cube currency='INR' rate='85.1330'/>
			<Cube currency='KRW' rate='1499.19'/>
			<Cube currency='MXN' rate='17.4437'/>
			<Cube currency='MYR' rate='4.4006'/>
			<Cube currency='NZD' rate='1.6780'/>
			<Cube currency='PHP' rate='58.775'/>
			<Cube currency='SGD' rate='1.7087'/>
			<Cube currency='THB' rate='42.399'/>
			<Cube currency='ZAR' rate='13.6055'/>
		</Cube>
	</Cube>
</gesmes:Envelope>
XML;

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
		), $ecb_source->convert_to_array($xml_data));
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
