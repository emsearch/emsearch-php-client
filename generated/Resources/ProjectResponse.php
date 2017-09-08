<?php

namespace emsearch\Api\Resources;

use emsearch\Api\ApiClient;
use emsearch\Api\Exceptions\ApiException;
use emsearch\Api\Resources\Project;

/**
 * ProjectResponse resource class
 * 
 * @package emsearch\Api\Resources
 */
class ProjectResponse 
{
	/**
	 * API client
	 *
	 * @var ApiClient
	 */
	protected $apiClient;

	/**
	 * @var Project
	 */
	public $data;

	/**
	 * ProjectResponse resource class constructor
	 * 
	 * @param ApiClient $apiClient API Client to use for this manager requests
	 * @param Project $data
	 */
	public function __construct(ApiClient $apiClient, $data = null)
	{
		$this->apiClient = $apiClient;
		$this->data = $data;
	}
}
