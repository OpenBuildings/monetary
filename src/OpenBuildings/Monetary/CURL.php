<?php

namespace OpenBuildings\Monetary;

/**
 * @author Haralan Dobrev <hdobrev@despark.com>
 * @copyright (c) 2013 OpenBuildings Inc.
 * @license http://spdx.org/licenses/BSD-3-Clause
 */
class CURL implements Requestable {

	/**
	 * Perform a cURL request
	 * @param  array  $curl_options
	 * @return string response of the request
	 * @throws Exception_Source If the remote returns an error.
	 */
	public function request(array $curl_options)
	{
		$curl = $this->_init($curl_options);
		$response = $this->_execute($curl);

		// Execute response
		if ($response === FALSE)
		{
			$this->_handle_error($curl);

			return FALSE;
		}

		$this->_close($curl);

		return $response;
	}

	/**
	 * Initializes a cURL resource and set options
	 * @param  array  $curl_options
	 * @return resource cURL resource
	 * @codeCoverageIgnore
	 */
	protected function _init(array $curl_options)
	{
		$options = array(
			CURLOPT_SSL_VERIFYPEER => FALSE,
			CURLOPT_SSL_VERIFYHOST => FALSE,
			CURLOPT_RETURNTRANSFER => TRUE,
			CURLOPT_FOLLOWLOCATION => TRUE,
			CURLOPT_MAXREDIRS      => 10
		);

		$options += $curl_options;

		$curl = curl_init();

		curl_setopt_array($curl, $options);

		return $curl;
	}

	/**
	 * Executes a cURL request
	 * @param  resource $curl cURL resource
	 * @return string|FALSE response from server or FALSE on failure
     * @codeCoverageIgnore
	 */
	protected function _execute($curl)
	{
		return curl_exec($curl);
	}

	/**
	 * Close a CURL handle
	 * @param  resource $curl
	 */
	protected function _close($curl)
	{
		curl_close($curl);
	}

	/**
	 * Handle cURL errors
	 * @param  resource $curl cURL resource with an error
	 * @throws Exception_Source with the error message and error code
	 */
	protected function _handle_error($curl)
	{
		// Get the error code and message
		$code  = curl_errno($curl);
		$error = curl_error($curl);

		$this->_close($curl);

		throw new Exception_Source(
			'Fetching :source_name data failed: :error (:code)',
			'remote',
			array(
				':error' => $error,
				':code' => $code
			)
		);
	}
}