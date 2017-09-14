<?php

namespace emsearch\Api\Managers;

use emsearch\Api\ApiClient;
use emsearch\Api\Exceptions\UnexpectedResponseException;
use emsearch\Api\Resources\ProjectListResponse;
use emsearch\Api\Resources\ErrorResponse;
use emsearch\Api\Resources\ProjectResponse;
use emsearch\Api\Resources\Project;
use emsearch\Api\Resources\DataStreamResponse;
use emsearch\Api\Resources\DataStream;
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
	 * @param string $include Include responses : {include1},{include2,{include3}[...]
	 * @param string $search Search words
	 * @param int $page Format: int32. Pagination : Page number
	 * @param int $limit Format: int32. Pagination : Maximum entries per page
	 * @param string $order_by Order by : {field},[asc|desc]
	 * 
	 * @return ProjectListResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function all($include = null, $search = null, $page = null, $limit = null, $order_by = null)
	{
		$routeUrl = '/api/project';

		$queryParameters = [];

		if (!is_null($include)) {
			$queryParameters['include'] = $include;
		}

		if (!is_null($search)) {
			$queryParameters['search'] = $search;
		}

		if (!is_null($page)) {
			$queryParameters['page'] = $page;
		}

		if (!is_null($limit)) {
			$queryParameters['limit'] = $limit;
		}

		if (!is_null($order_by)) {
			$queryParameters['order_by'] = $order_by;
		}

		$requestOptions = [];
		$requestOptions['query'] = $queryParameters;

		$request = $this->apiClient->getHttpClient()->request('get', $routeUrl, $requestOptions);

		if ($request->getStatusCode() != 200) {
			$requestBody = json_decode((string) $request->getBody(), true);

			$apiExceptionResponse = new ErrorResponse(
				$this->apiClient, 
				$requestBody['message'], 
				(isset($requestBody['errors']) ? $requestBody['errors'] : null), 
				(isset($requestBody['status_code']) ? $requestBody['status_code'] : null), 
				(isset($requestBody['debug']) ? $requestBody['debug'] : null)
			);

			throw new UnexpectedResponseException($request->getStatusCode(), 200, $request, $apiExceptionResponse);
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
					$data['updated_at'], 
					((isset($data['dataStream']) && !is_null($data['dataStream'])) ? (new DataStreamResponse(
						$this->apiClient, 
						new DataStream(
							$this->apiClient, 
							$data['dataStream']['data']['id'], 
							$data['dataStream']['data']['data_stream_decoder_id'], 
							$data['dataStream']['data']['name'], 
							$data['dataStream']['data']['feed_url'], 
							$data['dataStream']['data']['created_at'], 
							$data['dataStream']['data']['updated_at'], 
							((isset($data['dataStream']['data']['project']) && !is_null($data['dataStream']['data']['project'])) ? (new ProjectResponse(
								$this->apiClient, 
								null
							)) : null)
						)
					)) : null)
				); 
			}, $requestBody['data']), 
			new Meta(
				$this->apiClient, 
				((isset($requestBody['meta']['pagination']) && !is_null($requestBody['meta']['pagination'])) ? (new Pagination(
					$this->apiClient, 
					$requestBody['meta']['pagination']['total'], 
					$requestBody['meta']['pagination']['count'], 
					$requestBody['meta']['pagination']['per_page'], 
					$requestBody['meta']['pagination']['current_page'], 
					$requestBody['meta']['pagination']['total_pages'], 
					$requestBody['meta']['pagination']['links']
				)) : null)
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
	 * @throws UnexpectedResponseException
	 */
	public function create($search_engine_id, $name, $data_stream_id = null)
	{
		$routeUrl = '/api/project';

		$bodyParameters = [];
		$bodyParameters['search_engine_id'] = $search_engine_id;
		$bodyParameters['name'] = $name;

		if (!is_null($data_stream_id)) {
			$bodyParameters['data_stream_id'] = $data_stream_id;
		}

		$requestOptions = [];
		$requestOptions['form_params'] = $bodyParameters;

		$request = $this->apiClient->getHttpClient()->request('post', $routeUrl, $requestOptions);

		if ($request->getStatusCode() != 201) {
			$requestBody = json_decode((string) $request->getBody(), true);

			$apiExceptionResponse = new ErrorResponse(
				$this->apiClient, 
				$requestBody['message'], 
				(isset($requestBody['errors']) ? $requestBody['errors'] : null), 
				(isset($requestBody['status_code']) ? $requestBody['status_code'] : null), 
				(isset($requestBody['debug']) ? $requestBody['debug'] : null)
			);

			throw new UnexpectedResponseException($request->getStatusCode(), 201, $request, $apiExceptionResponse);
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
				$requestBody['data']['updated_at'], 
				((isset($requestBody['data']['dataStream']) && !is_null($requestBody['data']['dataStream'])) ? (new DataStreamResponse(
					$this->apiClient, 
					new DataStream(
						$this->apiClient, 
						$requestBody['data']['dataStream']['data']['id'], 
						$requestBody['data']['dataStream']['data']['data_stream_decoder_id'], 
						$requestBody['data']['dataStream']['data']['name'], 
						$requestBody['data']['dataStream']['data']['feed_url'], 
						$requestBody['data']['dataStream']['data']['created_at'], 
						$requestBody['data']['dataStream']['data']['updated_at'], 
						null
					)
				)) : null)
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
	 * @param string $include Include responses : {include1},{include2,{include3}[...]
	 * 
	 * @return ProjectResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function get($projectId, $include = null)
	{
		$routePath = '/api/project/{projectId}';

		$pathReplacements = [
			'{projectId}' => $projectId,
		];

		$routeUrl = str_replace(array_keys($pathReplacements), array_values($pathReplacements), $routePath);

		$queryParameters = [];

		if (!is_null($include)) {
			$queryParameters['include'] = $include;
		}

		$requestOptions = [];
		$requestOptions['query'] = $queryParameters;

		$request = $this->apiClient->getHttpClient()->request('get', $routeUrl, $requestOptions);

		if ($request->getStatusCode() != 200) {
			$requestBody = json_decode((string) $request->getBody(), true);

			$apiExceptionResponse = new ErrorResponse(
				$this->apiClient, 
				$requestBody['message'], 
				(isset($requestBody['errors']) ? $requestBody['errors'] : null), 
				(isset($requestBody['status_code']) ? $requestBody['status_code'] : null), 
				(isset($requestBody['debug']) ? $requestBody['debug'] : null)
			);

			throw new UnexpectedResponseException($request->getStatusCode(), 200, $request, $apiExceptionResponse);
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
				$requestBody['data']['updated_at'], 
				((isset($requestBody['data']['dataStream']) && !is_null($requestBody['data']['dataStream'])) ? (new DataStreamResponse(
					$this->apiClient, 
					new DataStream(
						$this->apiClient, 
						$requestBody['data']['dataStream']['data']['id'], 
						$requestBody['data']['dataStream']['data']['data_stream_decoder_id'], 
						$requestBody['data']['dataStream']['data']['name'], 
						$requestBody['data']['dataStream']['data']['feed_url'], 
						$requestBody['data']['dataStream']['data']['created_at'], 
						$requestBody['data']['dataStream']['data']['updated_at'], 
						null
					)
				)) : null)
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
	 * @throws UnexpectedResponseException
	 */
	public function update($projectId, $search_engine_id, $name, $data_stream_id = null)
	{
		$routePath = '/api/project/{projectId}';

		$pathReplacements = [
			'{projectId}' => $projectId,
		];

		$routeUrl = str_replace(array_keys($pathReplacements), array_values($pathReplacements), $routePath);

		$bodyParameters = [];
		$bodyParameters['search_engine_id'] = $search_engine_id;
		$bodyParameters['name'] = $name;

		if (!is_null($data_stream_id)) {
			$bodyParameters['data_stream_id'] = $data_stream_id;
		}

		$requestOptions = [];
		$requestOptions['form_params'] = $bodyParameters;

		$request = $this->apiClient->getHttpClient()->request('patch', $routeUrl, $requestOptions);

		if ($request->getStatusCode() != 201) {
			$requestBody = json_decode((string) $request->getBody(), true);

			$apiExceptionResponse = new ErrorResponse(
				$this->apiClient, 
				$requestBody['message'], 
				(isset($requestBody['errors']) ? $requestBody['errors'] : null), 
				(isset($requestBody['status_code']) ? $requestBody['status_code'] : null), 
				(isset($requestBody['debug']) ? $requestBody['debug'] : null)
			);

			throw new UnexpectedResponseException($request->getStatusCode(), 201, $request, $apiExceptionResponse);
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
				$requestBody['data']['updated_at'], 
				((isset($requestBody['data']['dataStream']) && !is_null($requestBody['data']['dataStream'])) ? (new DataStreamResponse(
					$this->apiClient, 
					new DataStream(
						$this->apiClient, 
						$requestBody['data']['dataStream']['data']['id'], 
						$requestBody['data']['dataStream']['data']['data_stream_decoder_id'], 
						$requestBody['data']['dataStream']['data']['name'], 
						$requestBody['data']['dataStream']['data']['feed_url'], 
						$requestBody['data']['dataStream']['data']['created_at'], 
						$requestBody['data']['dataStream']['data']['updated_at'], 
						null
					)
				)) : null)
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
	 * @return ErrorResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function delete($projectId)
	{
		$routePath = '/api/project/{projectId}';

		$pathReplacements = [
			'{projectId}' => $projectId,
		];

		$routeUrl = str_replace(array_keys($pathReplacements), array_values($pathReplacements), $routePath);

		$requestOptions = [];

		$request = $this->apiClient->getHttpClient()->request('delete', $routeUrl, $requestOptions);

		if ($request->getStatusCode() != 204) {
			$requestBody = json_decode((string) $request->getBody(), true);

			$apiExceptionResponse = new ErrorResponse(
				$this->apiClient, 
				$requestBody['message'], 
				(isset($requestBody['errors']) ? $requestBody['errors'] : null), 
				(isset($requestBody['status_code']) ? $requestBody['status_code'] : null), 
				(isset($requestBody['debug']) ? $requestBody['debug'] : null)
			);

			throw new UnexpectedResponseException($request->getStatusCode(), 204, $request, $apiExceptionResponse);
		}

		$requestBody = json_decode((string) $request->getBody(), true);

		$response = new ErrorResponse(
			$this->apiClient, 
			$requestBody['message'], 
			(isset($requestBody['errors']) ? $requestBody['errors'] : null), 
			(isset($requestBody['status_code']) ? $requestBody['status_code'] : null), 
			(isset($requestBody['debug']) ? $requestBody['debug'] : null)
		);

		return $response;
	}
}
