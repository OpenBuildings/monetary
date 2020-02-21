<?php

use OpenBuildings\Monetary\Exception as Exception;

/**
 * Test OpenBuildings\Monetary\Exception class.
 * @author Haralan Dobrev <hdobrev@despark.com>
 * @copyright (c) 2013 OpenBuildings Inc.
 * @license http://spdx.org/licenses/BSD-3-Clause
 */
class ExceptionTest extends \PHPUnit\Framework\TestCase {

	/**
	 * @covers OpenBuildings\Monetary\Exception::__construct
	 */
	public function test_constructor()
	{
		$exception = new Exception('some message :alabala', array(
			':alabala' => 'abcde'
		));

		$this->assertSame($exception->getMessage(), 'some message abcde');
	}
}
