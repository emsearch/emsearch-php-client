<?php

namespace Emsearch\Api\Managers;

use Emsearch\Api\ApiClient;
use Emsearch\Api\Exceptions\UnexpectedResponseException;
use Emsearch\Api\Resources\SearchEngineListResponse;
use Emsearch\Api\Resources\ErrorResponse;
use Emsearch\Api\Resources\SearchEngineResponse;
use Emsearch\Api\Resources\SearchEngine;
use Emsearch\Api\Resources\Meta;
use Emsearch\Api\Resources\Pagination;

/**
 * SearchEngine manager class
 * 
 * @package Emsearch\Api\Managers
 */
class SearchEngineManager 
{
	/**
	 * API client
	 *
	 * @var ApiClient
	 */
	protected $apiClient;

	/**
	 * SearchEngine manager class constructor
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
	 * Show search engine list
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $include Include responses : {include1},{include2,{include3}[...]
	 * @param string $search Search words
	 * @param int $page Format: int32. Pagination : Page number
	 * @param int $limit Format: int32. Pagination : Maximum entries per page
	 * @param string $order_by Order by : {field},[asc|desc]
	 * 
	 * @return SearchEngineListResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function all($include = null, $search = null, $page = null, $limit = null, $order_by = null)
	{
		$routeUrl = '/api/searchEngine';

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
				(isset($requestBody['app_error_code']) ? $requestBody['app_error_code'] : null), 
				$requestBody['message'], 
				(isset($requestBody['errors']) ? $requestBody['errors'] : null), 
				(isset($requestBody['status_code']) ? $requestBody['status_code'] : null), 
				(isset($requestBody['debug']) ? $requestBody['debug'] : null)
			);

			throw new UnexpectedResponseException($request->getStatusCode(), 200, $request, $apiExceptionResponse);
		}

		$requestBody = json_decode((string) $request->getBody(), true);

		$response = new SearchEngineListResponse(
			$this->apiClient, 
			array_map(function($data) {
				return new SearchEngine(
					$this->apiClient, 
					$data['id'], 
					$data['name'], 
					$data['class_name'], 
					$data['created_at'], 
					$data['updated_at'], 
					(isset($data['projects_count']) ? $data['projects_count'] : null)
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
	 * Create and store new search engine
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
	public function create($name, $class_name)
	{
		$routeUrl = '/api/searchEngine';

		$bodyParameters = [];
		$bodyParameters['name'] = $name;
		$bodyParameters['class_name'] = $class_name;

		$requestOptions = [];
		$requestOptions['form_params'] = $bodyParameters;

		$request = $this->apiClient->getHttpClient()->request('post', $routeUrl, $requestOptions);

		if ($request->getStatusCode() != 201) {
			$requestBody = json_decode((string) $request->getBody(), true);

			$apiExceptionResponse = new ErrorResponse(
				$this->apiClient, 
				(isset($requestBody['app_error_code']) ? $requestBody['app_error_code'] : null), 
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
	 * Get specified search engine
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $searchEngineId Search Engine UUID
	 * @param string $include Include responses : {include1},{include2,{include3}[...]
	 * 
	 * @return SearchEngineResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function get($searchEngineId, $include = null)
	{
		$routePath = '/api/searchEngine/{searchEngineId}';

		$pathReplacements = [
			'{searchEngineId}' => $searchEngineId,
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
				(isset($requestBody['app_error_code']) ? $requestBody['app_error_code'] : null), 
				$requestBody['message'], 
				(isset($requestBody['errors']) ? $requestBody['errors'] : null), 
				(isset($requestBody['status_code']) ? $requestBody['status_code'] : null), 
				(isset($requestBody['debug']) ? $requestBody['debug'] : null)
			);

			throw new UnexpectedResponseException($request->getStatusCode(), 200, $request, $apiExceptionResponse);
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
	 * Update a search engine
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $searchEngineId User UUID
	 * @param string $name
	 * @param string $class_name
	 * 
	 * @return SearchEngineResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function update($searchEngineId, $name, $class_name)
	{
		$routePath = '/api/searchEngine/{searchEngineId}';

		$pathReplacements = [
			'{searchEngineId}' => $searchEngineId,
		];

		$routeUrl = str_replace(array_keys($pathReplacements), array_values($pathReplacements), $routePath);

		$bodyParameters = [];
		$bodyParameters['name'] = $name;
		$bodyParameters['class_name'] = $class_name;

		$requestOptions = [];
		$requestOptions['form_params'] = $bodyParameters;

		$request = $this->apiClient->getHttpClient()->request('patch', $routeUrl, $requestOptions);

		if ($request->getStatusCode() != 200) {
			$requestBody = json_decode((string) $request->getBody(), true);

			$apiExceptionResponse = new ErrorResponse(
				$this->apiClient, 
				(isset($requestBody['app_error_code']) ? $requestBody['app_error_code'] : null), 
				$requestBody['message'], 
				(isset($requestBody['errors']) ? $requestBody['errors'] : null), 
				(isset($requestBody['status_code']) ? $requestBody['status_code'] : null), 
				(isset($requestBody['debug']) ? $requestBody['debug'] : null)
			);

			throw new UnexpectedResponseException($request->getStatusCode(), 200, $request, $apiExceptionResponse);
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
	 * @param string $searchEngineId Search Engine UUID
	 * 
	 * @return ErrorResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function delete($searchEngineId)
	{
		$routePath = '/api/searchEngine/{searchEngineId}';

		$pathReplacements = [
			'{searchEngineId}' => $searchEngineId,
		];

		$routeUrl = str_replace(array_keys($pathReplacements), array_values($pathReplacements), $routePath);

		$requestOptions = [];

		$request = $this->apiClient->getHttpClient()->request('delete', $routeUrl, $requestOptions);

		if ($request->getStatusCode() != 204) {
			$requestBody = json_decode((string) $request->getBody(), true);

			$apiExceptionResponse = new ErrorResponse(
				$this->apiClient, 
				(isset($requestBody['app_error_code']) ? $requestBody['app_error_code'] : null), 
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
			(isset($requestBody['app_error_code']) ? $requestBody['app_error_code'] : null), 
			$requestBody['message'], 
			(isset($requestBody['errors']) ? $requestBody['errors'] : null), 
			(isset($requestBody['status_code']) ? $requestBody['status_code'] : null), 
			(isset($requestBody['debug']) ? $requestBody['debug'] : null)
		);

		return $response;
	}
}
