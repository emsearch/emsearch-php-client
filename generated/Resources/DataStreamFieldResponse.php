<?php

namespace emsearch\Api\Resources;

use emsearch\Api\ApiClient;
use emsearch\Api\Exceptions\UnexpectedResponseException;

/**
 * DataStreamFieldResponse resource class
 * 
 * @package emsearch\Api\Resources
 */
class DataStreamFieldResponse 
{
	/**
	 * API client
	 *
	 * @var ApiClient
	 */
	protected $apiClient;

	/**
	 * @var DataStreamField
	 */
	public $data;

	/**
	 * DataStreamFieldResponse resource class constructor
	 * 
	 * @param ApiClient $apiClient API Client to use for this manager requests
	 * @param DataStreamField $data
	 */
	public function __construct(ApiClient $apiClient, $data = null)
	{
		$this->apiClient = $apiClient;
		$this->data = $data;
	}
}
