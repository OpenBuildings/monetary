<?php

use OpenBuildings\Monetary\Monetary;
use OpenBuildings\Monetary\Source_Static;

/**
 * Common test case creatign a monetary instance with static source
 * @author Yasen Yanev <yasen@openbuildings.com>
 * @author Ivan Kerin <ivan@openbuildings.com>
 * @author Haralan Dobrev <hdobrev@despark.com>
 * @copyright (c) 2013 OpenBuildings Inc.
 * @license http://spdx.org/licenses/BSD-3-Clause
 */
class Monetary_TestCase extends TestCase {

	const CURRENCY = 'USD';

	public function setUp()
	{
		parent::setUp();

		$this->monetary = new Monetary(self::CURRENCY, new Source_Static);
	}
}
