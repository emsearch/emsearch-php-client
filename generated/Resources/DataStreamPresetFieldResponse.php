<?php

namespace emsearch\Api\Resources;

use emsearch\Api\ApiClient;
use emsearch\Api\Exceptions\UnexpectedResponseException;

/**
 * DataStreamPresetFieldResponse resource class
 * 
 * @package emsearch\Api\Resources
 */
class DataStreamPresetFieldResponse 
{
	/**
	 * API client
	 *
	 * @var ApiClient
	 */
	protected $apiClient;

	/**
	 * @var DataStreamPresetField
	 */
	public $data;

	/**
	 * DataStreamPresetFieldResponse resource class constructor
	 * 
	 * @param ApiClient $apiClient API Client to use for this manager requests
	 * @param DataStreamPresetField $data
	 */
	public function __construct(ApiClient $apiClient, $data = null)
	{
		$this->apiClient = $apiClient;
		$this->data = $data;
	}
}
