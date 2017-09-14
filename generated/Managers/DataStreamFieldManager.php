<?php

namespace emsearch\Api\Managers;

use emsearch\Api\ApiClient;
use emsearch\Api\Exceptions\UnexpectedResponseException;
use emsearch\Api\Resources\DataStreamFieldListResponse;
use emsearch\Api\Resources\ErrorResponse;
use emsearch\Api\Resources\DataStreamFieldResponse;
use emsearch\Api\Resources\DataStreamField;
use emsearch\Api\Resources\Meta;
use emsearch\Api\Resources\Pagination;

/**
 * DataStreamField manager class
 * 
 * @package emsearch\Api\Managers
 */
class DataStreamFieldManager 
{
	/**
	 * API client
	 *
	 * @var ApiClient
	 */
	protected $apiClient;

	/**
	 * DataStreamField manager class constructor
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
	 * Show data stream field list
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $include Include responses : {include1},{include2,{include3}[...]
	 * @param string $search Search words
	 * @param int $page Format: int32. Pagination : Page number
	 * @param int $limit Format: int32. Pagination : Maximum entries per page
	 * @param string $order_by Order by : {field},[asc|desc]
	 * 
	 * @return DataStreamFieldListResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function all($include = null, $search = null, $page = null, $limit = null, $order_by = null)
	{
		$routeUrl = '/api/dataStreamField';

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

		$response = new DataStreamFieldListResponse(
			$this->apiClient, 
			array_map(function($data) {
				return new DataStreamField(
					$this->apiClient, 
					$data['id'], 
					$data['data_stream_id'], 
					$data['name'], 
					$data['path'], 
					$data['versioned'], 
					$data['searchable'], 
					$data['to_retrieve'], 
					$data['created_at'], 
					$data['updated_at']
				); 
			}, $requestBody['data']), 
			((isset($requestBody['meta']) && !is_null($requestBody['meta'])) ? (new Meta(
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
			)) : null)
		);

		return $response;
	}
	
	/**
	 * Create and store new data stream field
	 * 
	 * Excepted HTTP code : 201
	 * 
	 * @param string $data_stream_id Format: uuid.
	 * @param string $name
	 * @param string $path
	 * @param boolean $versioned
	 * @param boolean $searchable
	 * @param boolean $to_retrieve
	 * 
	 * @return DataStreamFieldResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function create($data_stream_id, $name, $path, $versioned, $searchable, $to_retrieve)
	{
		$routeUrl = '/api/dataStreamField';

		$bodyParameters = [];
		$bodyParameters['data_stream_id'] = $data_stream_id;
		$bodyParameters['name'] = $name;
		$bodyParameters['path'] = $path;
		$bodyParameters['versioned'] = $versioned;
		$bodyParameters['searchable'] = $searchable;
		$bodyParameters['to_retrieve'] = $to_retrieve;

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

		$response = new DataStreamFieldResponse(
			$this->apiClient, 
			((isset($requestBody['data']) && !is_null($requestBody['data'])) ? (new DataStreamField(
				$this->apiClient, 
				$requestBody['data']['id'], 
				$requestBody['data']['data_stream_id'], 
				$requestBody['data']['name'], 
				$requestBody['data']['path'], 
				$requestBody['data']['versioned'], 
				$requestBody['data']['searchable'], 
				$requestBody['data']['to_retrieve'], 
				$requestBody['data']['created_at'], 
				$requestBody['data']['updated_at']
			)) : null)
		);

		return $response;
	}
	
	/**
	 * Get specified data stream field
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $dataStreamFieldId Data stream field UUID
	 * @param string $include Include responses : {include1},{include2,{include3}[...]
	 * 
	 * @return DataStreamFieldResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function get($dataStreamFieldId, $include = null)
	{
		$routePath = '/api/dataStreamField/{dataStreamFieldId}';

		$pathReplacements = [
			'{dataStreamFieldId}' => $dataStreamFieldId,
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

		$response = new DataStreamFieldResponse(
			$this->apiClient, 
			((isset($requestBody['data']) && !is_null($requestBody['data'])) ? (new DataStreamField(
				$this->apiClient, 
				$requestBody['data']['id'], 
				$requestBody['data']['data_stream_id'], 
				$requestBody['data']['name'], 
				$requestBody['data']['path'], 
				$requestBody['data']['versioned'], 
				$requestBody['data']['searchable'], 
				$requestBody['data']['to_retrieve'], 
				$requestBody['data']['created_at'], 
				$requestBody['data']['updated_at']
			)) : null)
		);

		return $response;
	}
	
	/**
	 * Update a data stream field
	 * 
	 * Excepted HTTP code : 201
	 * 
	 * @param string $dataStreamFieldId Data stream field UUID
	 * @param string $data_stream_id Format: uuid.
	 * @param string $name
	 * @param string $path
	 * @param boolean $versioned
	 * @param boolean $searchable
	 * @param boolean $to_retrieve
	 * 
	 * @return DataStreamFieldResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function update($dataStreamFieldId, $data_stream_id, $name, $path, $versioned, $searchable, $to_retrieve)
	{
		$routePath = '/api/dataStreamField/{dataStreamFieldId}';

		$pathReplacements = [
			'{dataStreamFieldId}' => $dataStreamFieldId,
		];

		$routeUrl = str_replace(array_keys($pathReplacements), array_values($pathReplacements), $routePath);

		$bodyParameters = [];
		$bodyParameters['data_stream_id'] = $data_stream_id;
		$bodyParameters['name'] = $name;
		$bodyParameters['path'] = $path;
		$bodyParameters['versioned'] = $versioned;
		$bodyParameters['searchable'] = $searchable;
		$bodyParameters['to_retrieve'] = $to_retrieve;

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

		$response = new DataStreamFieldResponse(
			$this->apiClient, 
			((isset($requestBody['data']) && !is_null($requestBody['data'])) ? (new DataStreamField(
				$this->apiClient, 
				$requestBody['data']['id'], 
				$requestBody['data']['data_stream_id'], 
				$requestBody['data']['name'], 
				$requestBody['data']['path'], 
				$requestBody['data']['versioned'], 
				$requestBody['data']['searchable'], 
				$requestBody['data']['to_retrieve'], 
				$requestBody['data']['created_at'], 
				$requestBody['data']['updated_at']
			)) : null)
		);

		return $response;
	}
	
	/**
	 * Delete specified data stream field
	 * 
	 * Excepted HTTP code : 204
	 * 
	 * @param string $dataStreamFieldId Data stream field UUID
	 * 
	 * @return ErrorResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function delete($dataStreamFieldId)
	{
		$routePath = '/api/dataStreamField/{dataStreamFieldId}';

		$pathReplacements = [
			'{dataStreamFieldId}' => $dataStreamFieldId,
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
