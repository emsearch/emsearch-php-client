<?php

namespace emsearch\Api\Resources;

use emsearch\Api\ApiClient;
use emsearch\Api\Exceptions\UnexpectedResponseException;

/**
 * DataStreamPresetFieldListResponse resource class
 * 
 * @package emsearch\Api\Resources
 */
class DataStreamPresetFieldListResponse 
{
	/**
	 * API client
	 *
	 * @var ApiClient
	 */
	protected $apiClient;

	/**
	 * @var DataStreamPresetField[]
	 */
	public $data;

	/**
	 * @var Meta
	 */
	public $meta;

	/**
	 * DataStreamPresetFieldListResponse resource class constructor
	 * 
	 * @param ApiClient $apiClient API Client to use for this manager requests
	 * @param DataStreamPresetField[] $data
	 * @param Meta $meta
	 */
	public function __construct(ApiClient $apiClient, $data = null, $meta = null)
	{
		$this->apiClient = $apiClient;
		$this->data = $data;
		$this->meta = $meta;
	}
}
