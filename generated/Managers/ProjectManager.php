<?php

namespace emsearch\Api\Managers;

use emsearch\Api\ApiClient;
use emsearch\Api\Resources\ProjectListResponse;
use emsearch\Api\Resources\ProjectResponse;

/**
 * Project manager class
 * 
 * @package emsearch\Api\Managers
 */
class ProjectManager 
{
	/**
	 * API client
	 *
	 * @var ApiClient
	 */
	protected $apiClient;

	/**
	 * Project manager class constructor
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
	 * Project list
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @return ProjectListResponse
	 */
	public function all()
	{
		$url = '/api/project';

		$request = $this->apiClient->getHttpClient()->request('get', $url);

		return $request;
	}
	
	/**
	 * Create and store new project
	 * 
	 * Excepted HTTP code : 201
	 * 
	 * @param string $search_engine_id Format: uuid.
	 * @param string $name
	 * @param string $data_stream_id Format: uuid.
	 * 
	 * @return ProjectResponse
	 */
	public function create($search_engine_id, $name, $data_stream_id = null)
	{
		$url = '/api/project';

		$request = $this->apiClient->getHttpClient()->request('post', $url);

		return $request;
	}
	
	/**
	 * Get specified project
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $projectId Project UUID
	 * 
	 * @return ProjectResponse
	 */
	public function get($projectId)
	{
		$path = '/api/project/{projectId}';

		$pathReplacements = [
			'{projectId}' => $projectId,
		];

		$url = str_replace(array_keys($pathReplacements), array_values($pathReplacements), $path);

		$request = $this->apiClient->getHttpClient()->request('get', $url);

		return $request;
	}
	
	/**
	 * Update a specified project
	 * 
	 * Excepted HTTP code : 201
	 * 
	 * @param string $projectId Project UUID
	 * @param string $search_engine_id Format: uuid.
	 * @param string $name
	 * @param string $data_stream_id Format: uuid.
	 * 
	 * @return ProjectResponse
	 */
	public function update($projectId, $search_engine_id, $name, $data_stream_id = null)
	{
		$path = '/api/project/{projectId}';

		$pathReplacements = [
			'{projectId}' => $projectId,
		];

		$url = str_replace(array_keys($pathReplacements), array_values($pathReplacements), $path);

		$request = $this->apiClient->getHttpClient()->request('patch', $url);

		return $request;
	}
	
	/**
	 * Delete specified project
	 * 
	 * All relationships between the project and his users will be automatically deleted too.<br />
	 * The project sync items will be automatically deleted too.<br />
	 * The project data stream will be automatically deleted too, if exists.
	 * <aside class="notice">Only <code>Owner</code> of project is allowed to delete it.</aside>
	 * 
	 * Excepted HTTP code : 204
	 * 
	 * @param string $projectId Project UUID
	 */
	public function delete($projectId)
	{
		$path = '/api/project/{projectId}';

		$pathReplacements = [
			'{projectId}' => $projectId,
		];

		$url = str_replace(array_keys($pathReplacements), array_values($pathReplacements), $path);

		$request = $this->apiClient->getHttpClient()->request('delete', $url);

		return $request;
	}
}
