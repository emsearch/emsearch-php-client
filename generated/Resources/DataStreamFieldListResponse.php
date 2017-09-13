<?php

namespace emsearch\Api\Resources;

use emsearch\Api\ApiClient;
use emsearch\Api\Exceptions\UnexpectedResponseException;

/**
 * DataStreamFieldListResponse resource class
 * 
 * @package emsearch\Api\Resources
 */
class DataStreamFieldListResponse 
{
	/**
	 * API client
	 *
	 * @var ApiClient
	 */
	protected $apiClient;

	/**
	 * @var DataStreamField[]
	 */
	public $data;

	/**
	 * @var Meta
	 */
	public $meta;

	/**
	 * DataStreamFieldListResponse resource class constructor
	 * 
	 * @param ApiClient $apiClient API Client to use for this manager requests
	 * @param DataStreamField[] $data
	 * @param Meta $meta
	 */
	public function __construct(ApiClient $apiClient, $data = null, $meta = null)
	{
		$this->apiClient = $apiClient;
		$this->data = $data;
		$this->meta = $meta;
	}
}
