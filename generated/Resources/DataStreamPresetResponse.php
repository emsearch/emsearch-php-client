<?php

namespace Emsearch\Api\Resources;

use Emsearch\Api\ApiClient;
use Emsearch\Api\Exceptions\UnexpectedResponseException;

/**
 * DataStreamPresetResponse resource class
 * 
 * @package Emsearch\Api\Resources
 */
class DataStreamPresetResponse 
{
	/**
	 * API client
	 *
	 * @var ApiClient
	 */
	protected $apiClient;

	/**
	 * @var DataStreamPreset
	 */
	public $data;

	/**
	 * DataStreamPresetResponse resource class constructor
	 * 
	 * @param ApiClient $apiClient API Client to use for this manager requests
	 * @param DataStreamPreset $data
	 */
	public function __construct(ApiClient $apiClient, $data = null)
	{
		$this->apiClient = $apiClient;
		$this->data = $data;
	}
}
