<?php

namespace emsearch\Api\Managers;

use emsearch\Api\ApiClient;
use emsearch\Api\Exceptions\ApiException;
use emsearch\Api\Resources\ProjectListResponse;
use emsearch\Api\Resources\ProjectResponse;
use emsearch\Api\Resources\Project;
use emsearch\Api\Resources\Meta;
use emsearch\Api\Resources\Pagination;

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
	 * 
	 * @throws ApiException
	 */
	public function all()
	{
		$url = '/api/project';

		$request = $this->apiClient->getHttpClient()->request('get', $url);

		if ($request->getStatusCode() != 200) {
			throw new ApiException('Unexpected response HTTP code : ' . $request->getStatusCode() . ' instead of 200');
		}

		$requestBody = json_decode((string) $request->getBody(), true);

		$response = new ProjectListResponse(
			$this->apiClient, 
			new Project(
				$this->apiClient, 
				$requestBody['data']['id'], 
				$requestBody['data']['search_engine_id'], 
				$requestBody['data']['data_stream_id'], 
				$requestBody['data']['name'], 
				$requestBody['data']['created_at'], 
				$requestBody['data']['updated_at']
			), 
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
	 * 
	 * @throws ApiException
	 */
	public function create($search_engine_id, $name, $data_stream_id = null)
	{
		$url = '/api/project';

		$request = $this->apiClient->getHttpClient()->request('post', $url);

		if ($request->getStatusCode() != 201) {
			throw new ApiException('Unexpected response HTTP code : ' . $request->getStatusCode() . ' instead of 201');
		}

		$requestBody = json_decode((string) $request->getBody(), true);

		$response = new ProjectResponse(
			$this->apiClient, 
			new Project(
				$this->apiClient, 
				$requestBody['data']['id'], 
				$requestBody['data']['search_engine_id'], 
				$requestBody['data']['data_stream_id'], 
				$requestBody['data']['name'], 
				$requestBody['data']['created_at'], 
				$requestBody['data']['updated_at']
			)
		);

		return $response;
	}
	
	/**
	 * Get specified project
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $projectId Project UUID
	 * 
	 * @return ProjectResponse
	 * 
	 * @throws ApiException
	 */
	public function get($projectId)
	{
		$path = '/api/project/{projectId}';

		$pathReplacements = [
			'{projectId}' => $projectId,
		];

		$url = str_replace(array_keys($pathReplacements), array_values($pathReplacements), $path);

		$request = $this->apiClient->getHttpClient()->request('get', $url);

		if ($request->getStatusCode() != 200) {
			throw new ApiException('Unexpected response HTTP code : ' . $request->getStatusCode() . ' instead of 200');
		}

		$requestBody = json_decode((string) $request->getBody(), true);

		$response = new ProjectResponse(
			$this->apiClient, 
			new Project(
				$this->apiClient, 
				$requestBody['data']['id'], 
				$requestBody['data']['search_engine_id'], 
				$requestBody['data']['data_stream_id'], 
				$requestBody['data']['name'], 
				$requestBody['data']['created_at'], 
				$requestBody['data']['updated_at']
			)
		);

		return $response;
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
	 * 
	 * @throws ApiException
	 */
	public function update($projectId, $search_engine_id, $name, $data_stream_id = null)
	{
		$path = '/api/project/{projectId}';

		$pathReplacements = [
			'{projectId}' => $projectId,
		];

		$url = str_replace(array_keys($pathReplacements), array_values($pathReplacements), $path);

		$request = $this->apiClient->getHttpClient()->request('patch', $url);

		if ($request->getStatusCode() != 201) {
			throw new ApiException('Unexpected response HTTP code : ' . $request->getStatusCode() . ' instead of 201');
		}

		$requestBody = json_decode((string) $request->getBody(), true);

		$response = new ProjectResponse(
			$this->apiClient, 
			new Project(
				$this->apiClient, 
				$requestBody['data']['id'], 
				$requestBody['data']['search_engine_id'], 
				$requestBody['data']['data_stream_id'], 
				$requestBody['data']['name'], 
				$requestBody['data']['created_at'], 
				$requestBody['data']['updated_at']
			)
		);

		return $response;
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
	 * 
	 * @throws ApiException
	 */
	public function delete($projectId)
	{
		$path = '/api/project/{projectId}';

		$pathReplacements = [
			'{projectId}' => $projectId,
		];

		$url = str_replace(array_keys($pathReplacements), array_values($pathReplacements), $path);

		$request = $this->apiClient->getHttpClient()->request('delete', $url);

		if ($request->getStatusCode() != 204) {
			throw new ApiException('Unexpected response HTTP code : ' . $request->getStatusCode() . ' instead of 204');
		}
	}
}
