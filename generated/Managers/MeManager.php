<?php

namespace emsearch\Api\Managers;

use emsearch\Api\ApiClient;
use emsearch\Api\Exceptions\ApiException;
use emsearch\Api\Resources\ProjectListResponse;
use emsearch\Api\Resources\Project;
use emsearch\Api\Resources\Meta;
use emsearch\Api\Resources\Pagination;

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
	 * 
	 * @throws ApiException
	 */
	public function getUser()
	{
		$url = '/api/me';

		$request = $this->apiClient->getHttpClient()->request('get', $url);

		if ($request->getStatusCode() != 200) {
			throw new ApiException('Unexpected response HTTP code : ' . $request->getStatusCode() . ' instead of 200');
		}
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
	 * 
	 * @throws ApiException
	 */
	public function getProjects($user_role_id = null)
	{
		$url = '/api/me/project';

		$request = $this->apiClient->getHttpClient()->request('get', $url);

		if ($request->getStatusCode() != 200) {
			throw new ApiException('Unexpected response HTTP code : ' . $request->getStatusCode() . ' instead of 200');
		}

		$requestBody = json_decode((string) $request->getBody(), true);

		$response = new ProjectListResponse(
			$this->apiClient, 
			array_map(function($data) {
				return new Project(
					$this->apiClient, 
					$data['id'], 
					$data['search_engine_id'], 
					$data['data_stream_id'], 
					$data['name'], 
					$data['created_at'], 
					$data['updated_at']
				); 
			}, $requestBody['data']), 
			new Meta(
				$this->apiClient, 
				new Pagination(
					$this->apiClient, 
					$requestBody['meta']['pagination']['total'], 
					$requestBody['meta']['pagination']['count'], 
					$requestBody['meta']['pagination']['per_page'], 
					$requestBody['meta']['pagination']['current_page'], 
					$requestBody['meta']['pagination']['total_pages'], 
					$requestBody['meta']['pagination']['links']
				)
			)
		);

		return $response;
	}
}
