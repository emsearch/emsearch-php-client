<?php

namespace emsearch\Api\Managers;

use emsearch\Api\ApiClient;
use emsearch\Api\Exceptions\UnexpectedResponseException;
use emsearch\Api\Resources\DataStreamListResponse;
use emsearch\Api\Resources\ErrorResponse;
use emsearch\Api\Resources\DataStreamResponse;
use emsearch\Api\Resources\DataStream;
use emsearch\Api\Resources\ProjectResponse;
use emsearch\Api\Resources\Project;
use emsearch\Api\Resources\Meta;
use emsearch\Api\Resources\Pagination;

/**
 * DataStream manager class
 * 
 * @package emsearch\Api\Managers
 */
class DataStreamManager 
{
	/**
	 * API client
	 *
	 * @var ApiClient
	 */
	protected $apiClient;

	/**
	 * DataStream manager class constructor
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
	 * Show data stream list
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $include Include responses : {include1},{include2,{include3}[...]
	 * @param string $search Search words
	 * @param int $page Format: int32. Pagination : Page number
	 * @param int $limit Format: int32. Pagination : Maximum entries per page
	 * @param string $order_by Order by : {field},[asc|desc]
	 * 
	 * @return DataStreamListResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function all($include = null, $search = null, $page = null, $limit = null, $order_by = null)
	{
		$routeUrl = '/api/dataStream';

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

		$response = new DataStreamListResponse(
			$this->apiClient, 
			array_map(function($data) {
				return new DataStream(
					$this->apiClient, 
					$data['id'], 
					$data['data_stream_decoder_id'], 
					$data['name'], 
					$data['feed_url'], 
					$data['created_at'], 
					$data['updated_at'], 
					(isset($data['project']) ? (new ProjectResponse(
						$this->apiClient, 
						new Project(
							$this->apiClient, 
							$data['project']['data']['id'], 
							$data['project']['data']['search_engine_id'], 
							$data['project']['data']['data_stream_id'], 
							$data['project']['data']['name'], 
							$data['project']['data']['created_at'], 
							$data['project']['data']['updated_at'], 
							(isset($data['project']['data']['dataStream']) ? (new DataStreamResponse(
								$this->apiClient, 
								null
							)) : null)
						)
					)) : null)
				); 
			}, $requestBody['data']), 
			new Meta(
				$this->apiClient, 
				(isset($requestBody['meta']['pagination']) ? (new Pagination(
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
	 * Create and store new data stream
	 * 
	 * Only one data stream per project is allowed.
	 * 
	 * Excepted HTTP code : 201
	 * 
	 * @param string $data_stream_decoder_id Format: uuid.
	 * @param string $name
	 * @param string $feed_url Format: url.
	 * 
	 * @return DataStreamResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function create($data_stream_decoder_id, $name, $feed_url)
	{
		$routeUrl = '/api/dataStream';

		$bodyParameters = [];
		$bodyParameters['data_stream_decoder_id'] = $data_stream_decoder_id;
		$bodyParameters['name'] = $name;
		$bodyParameters['feed_url'] = $feed_url;

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

		$response = new DataStreamResponse(
			$this->apiClient, 
			new DataStream(
				$this->apiClient, 
				$requestBody['data']['id'], 
				$requestBody['data']['data_stream_decoder_id'], 
				$requestBody['data']['name'], 
				$requestBody['data']['feed_url'], 
				$requestBody['data']['created_at'], 
				$requestBody['data']['updated_at'], 
				(isset($requestBody['data']['project']) ? (new ProjectResponse(
					$this->apiClient, 
					new Project(
						$this->apiClient, 
						$requestBody['data']['project']['data']['id'], 
						$requestBody['data']['project']['data']['search_engine_id'], 
						$requestBody['data']['project']['data']['data_stream_id'], 
						$requestBody['data']['project']['data']['name'], 
						$requestBody['data']['project']['data']['created_at'], 
						$requestBody['data']['project']['data']['updated_at'], 
						null
					)
				)) : null)
			)
		);

		return $response;
	}
	
	/**
	 * Get specified data stream
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $dataStreamId Data stream UUID
	 * @param string $include Include responses : {include1},{include2,{include3}[...]
	 * 
	 * @return DataStreamResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function get($dataStreamId, $include = null)
	{
		$routePath = '/api/dataStream/{dataStreamId}';

		$pathReplacements = [
			'{dataStreamId}' => $dataStreamId,
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

		$response = new DataStreamResponse(
			$this->apiClient, 
			new DataStream(
				$this->apiClient, 
				$requestBody['data']['id'], 
				$requestBody['data']['data_stream_decoder_id'], 
				$requestBody['data']['name'], 
				$requestBody['data']['feed_url'], 
				$requestBody['data']['created_at'], 
				$requestBody['data']['updated_at'], 
				(isset($requestBody['data']['project']) ? (new ProjectResponse(
					$this->apiClient, 
					new Project(
						$this->apiClient, 
						$requestBody['data']['project']['data']['id'], 
						$requestBody['data']['project']['data']['search_engine_id'], 
						$requestBody['data']['project']['data']['data_stream_id'], 
						$requestBody['data']['project']['data']['name'], 
						$requestBody['data']['project']['data']['created_at'], 
						$requestBody['data']['project']['data']['updated_at'], 
						null
					)
				)) : null)
			)
		);

		return $response;
	}
	
	/**
	 * Update a data stream
	 * 
	 * Excepted HTTP code : 201
	 * 
	 * @param string $dataStreamId Data stream UUID
	 * @param string $data_stream_decoder_id Format: uuid.
	 * @param string $name
	 * @param string $feed_url Format: url.
	 * 
	 * @return DataStreamResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function update($dataStreamId, $data_stream_decoder_id, $name, $feed_url)
	{
		$routePath = '/api/dataStream/{dataStreamId}';

		$pathReplacements = [
			'{dataStreamId}' => $dataStreamId,
		];

		$routeUrl = str_replace(array_keys($pathReplacements), array_values($pathReplacements), $routePath);

		$bodyParameters = [];
		$bodyParameters['data_stream_decoder_id'] = $data_stream_decoder_id;
		$bodyParameters['name'] = $name;
		$bodyParameters['feed_url'] = $feed_url;

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

		$response = new DataStreamResponse(
			$this->apiClient, 
			new DataStream(
				$this->apiClient, 
				$requestBody['data']['id'], 
				$requestBody['data']['data_stream_decoder_id'], 
				$requestBody['data']['name'], 
				$requestBody['data']['feed_url'], 
				$requestBody['data']['created_at'], 
				$requestBody['data']['updated_at'], 
				(isset($requestBody['data']['project']) ? (new ProjectResponse(
					$this->apiClient, 
					new Project(
						$this->apiClient, 
						$requestBody['data']['project']['data']['id'], 
						$requestBody['data']['project']['data']['search_engine_id'], 
						$requestBody['data']['project']['data']['data_stream_id'], 
						$requestBody['data']['project']['data']['name'], 
						$requestBody['data']['project']['data']['created_at'], 
						$requestBody['data']['project']['data']['updated_at'], 
						null
					)
				)) : null)
			)
		);

		return $response;
	}
	
	/**
	 * Delete specified data stream
	 * 
	 * Excepted HTTP code : 204
	 * 
	 * @param string $dataStreamId Data stream UUID
	 * 
	 * @return ErrorResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function delete($dataStreamId)
	{
		$routePath = '/api/dataStream/{dataStreamId}';

		$pathReplacements = [
			'{dataStreamId}' => $dataStreamId,
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
