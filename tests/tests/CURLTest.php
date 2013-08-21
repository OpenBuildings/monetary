<?php

use OpenBuildings\Monetary\CURL as CURL;

/**
 * Test OpenBuildings\Monetary\CURL class.
 * @author Haralan Dobrev <hdobrev@despark.com>
 * @copyright (c) 2013 OpenBuildings Inc.
 * @license http://spdx.org/licenses/BSD-3-Clause
 */
class CURLTest extends PHPUnit_Framework_TestCase {

	/**
	 * @covers OpenBuildings\Monetary\CURL::request
	 */
	public function test_request()
	{
		$curl = $this->getMock('OpenBuildings\Monetary\CURL', array(
			'_init',
			'_execute',
			'_close',
		));

		$curl
			->expects($this->once())
			->method('_init')
			->with($this->equalTo(array(
				'ABCDE' => 'QWERTY'
			)))
			->will($this->returnValue('ABRACADABRA'));

		$curl
			->expects($this->once())
			->method('_execute')
			->with($this->equalTo('ABRACADABRA'))
			->will($this->returnValue('response string'));

		$curl
			->expects($this->once())
			->method('_close')
			->with($this->equalTo('ABRACADABRA'));

		$response = $curl->request(array(
			'ABCDE' => 'QWERTY'
		));

		$this->assertSame('response string', $response);
	}

	/**
	 * @covers OpenBuildings\Monetary\CURL::request
	 */
	public function test_request_with_error()
	{
		$curl = $this->getMock('OpenBuildings\Monetary\CURL', array(
			'_init',
			'_execute',
			'_handle_error',
			'_close',
		));

		$curl
			->expects($this->once())
			->method('_init')
			->with($this->equalTo(array(
				'ABCDE' => 'QWERTY'
			)))
			->will($this->returnValue('ABRACADABRA'));

		$curl
			->expects($this->once())
			->method('_execute')
			->with($this->equalTo('ABRACADABRA'))
			->will($this->returnValue(FALSE));

		$curl
			->expects($this->once())
			->method('_handle_error')
			->with($this->equalTo('ABRACADABRA'));

		$response = $curl->request(array(
			'ABCDE' => 'QWERTY'
		));

		$this->assertFalse($response);

	}

	/**
	 * @covers OpenBuildings\Monetary\CURL::_handle_error
	 */
	public function test_handle_error()
	{
		$curl = $this->getMock('OpenBuildings\Monetary\CURL', array(
			'_init',
			'_execute',
			'_close',
		));

		$resource = curl_init();

		$curl
			->expects($this->once())
			->method('_init')
			->with($this->equalTo(array(
				'ABCDE' => 'QWERTY'
			)))
			->will($this->returnValue($resource));

		$curl
			->expects($this->once())
			->method('_execute')
			->with($this->equalTo($resource))
			->will($this->returnValue(FALSE));

		$curl
			->expects($this->once())
			->method('_close')
			->with($this->equalTo($resource));

		$this->setExpectedException(
			'OpenBuildings\Monetary\Exception_Source',
			'Fetching remote data failed:  (0)'
		);

		$curl->request(array(
			'ABCDE' => 'QWERTY'
		));

		curl_close($resource);
	}

	/**
	 * @covers OpenBuildings\Monetary\CURL::_close
	 */
	public function test_close()
	{
		$curl = $this->getMock('OpenBuildings\Monetary\CURL', array(
			'_init',
			'_execute',
		));

		$resource = curl_init();

		$curl
			->expects($this->any())
			->method('_init')
			->with($this->equalTo(array(
				'ABCDE' => 'QWERTY'
			)))
			->will($this->returnValue($resource));

		$curl
			->expects($this->any())
			->method('_execute')
			->with($this->equalTo($resource))
			->will($this->returnValue('response string'));

		$curl->request(array(
			'ABCDE' => 'QWERTY'
		));

		$this->setExpectedException('PHPUnit_Framework_Error_Warning');

		curl_close($resource);
	}

}
