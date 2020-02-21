<?php

use OpenBuildings\Monetary\Exception_Source as Exception_Source;

/**
 * Test OpenBuildings\Monetary\Exception class.
 * @author Haralan Dobrev <hdobrev@despark.com>
 * @copyright (c) 2013 OpenBuildings Inc.
 * @license http://spdx.org/licenses/BSD-3-Clause
 */
class Exception_SourceTest extends \PHPUnit\Framework\TestCase {

	/**
	 * @covers OpenBuildings\Monetary\Exception_Source::__construct
	 */
	public function test_constructor()
	{
		$exception = new Exception_Source('some message :alabala :source_name', 'qwerty', array(
			':alabala' => 'abcde'
		));

		$this->assertSame($exception->getMessage(), 'some message abcde qwerty');
		$this->assertSame($exception->source_name, 'qwerty');
	}
}
