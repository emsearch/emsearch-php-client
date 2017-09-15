<?php

namespace emsearch\Api\Resources;

use emsearch\Api\ApiClient;
use emsearch\Api\Exceptions\UnexpectedResponseException;

/**
 * SyncTaskTypeVersionListResponse resource class
 * 
 * @package emsearch\Api\Resources
 */
class SyncTaskTypeVersionListResponse 
{
	/**
	 * API client
	 *
	 * @var ApiClient
	 */
	protected $apiClient;

	/**
	 * @var SyncTaskTypeVersion[]
	 */
	public $data;

	/**
	 * @var Meta
	 */
	public $meta;

	/**
	 * SyncTaskTypeVersionListResponse resource class constructor
	 * 
	 * @param ApiClient $apiClient API Client to use for this manager requests
	 * @param SyncTaskTypeVersion[] $data
	 * @param Meta $meta
	 */
	public function __construct(ApiClient $apiClient, $data = null, $meta = null)
	{
		$this->apiClient = $apiClient;
		$this->data = $data;
		$this->meta = $meta;
	}
}
