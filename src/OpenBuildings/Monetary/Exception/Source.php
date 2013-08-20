<?php

namespace OpenBuildings\Monetary;

/**
 * @author Haralan Dobrev <hdobrev@despark.com>
 * @copyright (c) 2013 OpenBuildings Inc.
 * @license http://spdx.org/licenses/BSD-3-Clause
 */
class Exception_Source extends Exception {

	public $source_name;

	public function __construct($message, $source_name, $variables = array())
	{
		$this->source_name = $variables[':source_name'] = $source_name;

		parent::__construct($message, $variables);
	}
}
