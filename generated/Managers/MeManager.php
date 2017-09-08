<?php

namespace emsearch\Api\Managers;

use emsearch\Api\ApiClient;
use emsearch\Api\Resources\ProjectListResponse;

/**
 * Me manager class
 * 
 * @package emsearch\Api\Managers
 */
class MeManager 
{
	/**
	 * API client
	 *
	 * @var ApiClient
	 */
	protected $apiClient;

	/**
	 * Me manager class constructor
	 *
	 * @param ApiClient $apiClient API Client to use for this manager requests
	 */
	public function __construct(ApiClient $apiClient)
	{
		$this->apiClient = $apiClient;
	}

	/**
	 * Return the API client used for this manager requests
	 *
	 * @return ApiClient
	 */
	public function getApiClient()
	{
		return $this->apiClient;
	}

	/**
	 * Get current user
	 * 
	 * Excepted HTTP code : 200
	 */
	public function getUser()
	{
		$url = '/api/me';

		$request = $this->apiClient->request('get', $url);

		return $request;
	}
	
	/**
	 * Current user project list
	 * 
	 * You can specify a GET parameter `user_role_id` to filter results.
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $user_role_id
	 * 
	 * @return ProjectListResponse
	 */
	public function getProjects($user_role_id = null)
	{
		$url = '/api/me/project';

		$request = $this->apiClient->request('get', $url);

		return $request;
	}
}
