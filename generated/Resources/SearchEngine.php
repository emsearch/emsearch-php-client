<?php

namespace Emsearch\Api\Resources;

use Emsearch\Api\ApiClient;
use Emsearch\Api\Exceptions\UnexpectedResponseException;

/**
 * SearchEngine resource class
 * 
 * @package Emsearch\Api\Resources
 */
class SearchEngine 
{
	/**
	 * API client
	 *
	 * @var ApiClient
	 */
	protected $apiClient;

	/**
	 * Format: uuid.
	 * 
	 * @var string
	 */
	public $id;

	/**
	 * @var string
	 */
	public $name;

	/**
	 * @var string
	 */
	public $class_name;

	/**
	 * Format: date-time.
	 * 
	 * @var string
	 */
	public $created_at;

	/**
	 * Format: date-time.
	 * 
	 * @var string
	 */
	public $updated_at;

	/**
	 * Format: int32.
	 * 
	 * @var int
	 */
	public $projects_count;

	/**
	 * SearchEngine resource class constructor
	 * 
	 * @param ApiClient $apiClient API Client to use for this manager requests
	 * @param string $id Format: uuid.
	 * @param string $name
	 * @param string $class_name
	 * @param string $created_at Format: date-time.
	 * @param string $updated_at Format: date-time.
	 * @param int $projects_count Format: int32.
	 */
	public function __construct(ApiClient $apiClient, $id = null, $name = null, $class_name = null, $created_at = null, $updated_at = null, $projects_count = null)
	{
		$this->apiClient = $apiClient;
		$this->id = $id;
		$this->name = $name;
		$this->class_name = $class_name;
		$this->created_at = $created_at;
		$this->updated_at = $updated_at;
		$this->projects_count = $projects_count;
	}
	/**
	 * Update a search engine
	 * 
	 * Excepted HTTP code : 201
	 * 
	 * @param string $name
	 * @param string $class_name
	 * 
	 * @return SearchEngineResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function update($name, $class_name)
	{
		$routePath = '/api/searchEngine/{searchEngineId}';

		$pathReplacements = [
			'{searchEngineId}' => $this->id,
		];

		$routeUrl = str_replace(array_keys($pathReplacements), array_values($pathReplacements), $routePath);

		$bodyParameters = [];
		$bodyParameters['name'] = $name;
		$bodyParameters['class_name'] = $class_name;

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

		$response = new SearchEngineResponse(
			$this->apiClient, 
			new SearchEngine(
				$this->apiClient, 
				$requestBody['data']['id'], 
				$requestBody['data']['name'], 
				$requestBody['data']['class_name'], 
				$requestBody['data']['created_at'], 
				$requestBody['data']['updated_at'], 
				(isset($requestBody['data']['projects_count']) ? $requestBody['data']['projects_count'] : null)
			)
		);

		return $response;
	}
	
	/**
	 * Delete specified search engine
	 * 
	 * <aside class="warning">Avoid using this feature ! Check foreign keys constraints to remove dependent resources properly before.</aside>
	 * 
	 * Excepted HTTP code : 204
	 * 
	 * @return ErrorResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function delete()
	{
		$routePath = '/api/searchEngine/{searchEngineId}';

		$pathReplacements = [
			'{searchEngineId}' => $this->id,
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
	
	/**
	 * Search engine project list
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
	public function getProjects($include = null, $search = null, $page = null, $limit = null, $order_by = null)
	{
		$routePath = '/api/searchEngine/{searchEngineId}/project';

		$pathReplacements = [
			'{searchEngineId}' => $this->id,
		];

		$routeUrl = str_replace(array_keys($pathReplacements), array_values($pathReplacements), $routePath);

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
							)) : null), 
							((isset($data['dataStream']['data']['dataStreamDecoder']) && !is_null($data['dataStream']['data']['dataStreamDecoder'])) ? (new DataStreamDecoderResponse(
								$this->apiClient, 
								new DataStreamDecoder(
									$this->apiClient, 
									$data['dataStream']['data']['dataStreamDecoder']['data']['id'], 
									$data['dataStream']['data']['dataStreamDecoder']['data']['name'], 
									$data['dataStream']['data']['dataStreamDecoder']['data']['class_name'], 
									$data['dataStream']['data']['dataStreamDecoder']['data']['file_mime_type'], 
									$data['dataStream']['data']['dataStreamDecoder']['data']['created_at'], 
									$data['dataStream']['data']['dataStreamDecoder']['data']['updated_at']
								)
							)) : null)
						)
					)) : null), 
					((isset($data['searchEngine']) && !is_null($data['searchEngine'])) ? (new SearchEngineResponse(
						$this->apiClient, 
						new SearchEngine(
							$this->apiClient, 
							$data['searchEngine']['data']['id'], 
							$data['searchEngine']['data']['name'], 
							$data['searchEngine']['data']['class_name'], 
							$data['searchEngine']['data']['created_at'], 
							$data['searchEngine']['data']['updated_at'], 
							(isset($data['searchEngine']['data']['projects_count']) ? $data['projects_count'] : null)
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
}
