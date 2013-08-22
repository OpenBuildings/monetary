<?php

namespace OpenBuildings\Monetary;

/**
 * @author Haralan Dobrev <hdobrev@despark.com>
 * @copyright (c) 2013 OpenBuildings Inc.
 * @license http://spdx.org/licenses/BSD-3-Clause
 */
interface Requestable {

	/**
	 * Perform a request to remote a service
	 * @param  array  $options options for the request (e.g. cURL options)
	 * @return string response
	 */
	public function request(array $options);

}