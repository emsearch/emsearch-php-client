<?php

namespace Emsearch\Api\Resources;

use Emsearch\Api\ApiClient;
use Emsearch\Api\Exceptions\UnexpectedResponseException;

/**
 * SyncTaskStatusResponse resource class
 * 
 * @package Emsearch\Api\Resources
 */
class SyncTaskStatusResponse 
{
	/**
	 * API client
	 *
	 * @var ApiClient
	 */
	protected $apiClient;

	/**
	 * @var SyncTaskStatus
	 */
	public $data;

	/**
	 * SyncTaskStatusResponse resource class constructor
	 * 
	 * @param ApiClient $apiClient API Client to use for this manager requests
	 * @param SyncTaskStatus $data
	 */
	public function __construct(ApiClient $apiClient, $data = null)
	{
		$this->apiClient = $apiClient;
		$this->data = $data;
	}
}
