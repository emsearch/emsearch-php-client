<?php

namespace Emsearch\Api\Managers;

use Emsearch\Api\ApiClient;
use Emsearch\Api\Exceptions\UnexpectedResponseException;
use Emsearch\Api\Resources\SyncTaskStatusListResponse;
use Emsearch\Api\Resources\ErrorResponse;
use Emsearch\Api\Resources\SyncTaskStatusResponse;
use Emsearch\Api\Resources\SyncTaskStatus;
use Emsearch\Api\Resources\Meta;
use Emsearch\Api\Resources\Pagination;

/**
 * SyncTaskStatus manager class
 * 
 * @package Emsearch\Api\Managers
 */
class SyncTaskStatusManager 
{
	/**
	 * API client
	 *
	 * @var ApiClient
	 */
	protected $apiClient;

	/**
	 * SyncTaskStatus manager class constructor
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
	 * Show sync task status list
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $search Search words
	 * @param int $page Format: int32. Pagination : Page number
	 * @param int $limit Format: int32. Pagination : Maximum entries per page
	 * @param string $order_by Order by : {field},[asc|desc]
	 * 
	 * @return SyncTaskStatusListResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function all($search = null, $page = null, $limit = null, $order_by = null)
	{
		$routeUrl = '/api/syncTaskStatus';

		$queryParameters = [];

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

		$response = new SyncTaskStatusListResponse(
			$this->apiClient, 
			array_map(function($data) {
				return new SyncTaskStatus(
					$this->apiClient, 
					$data['id'], 
					$data['sync_tasks_count'], 
					$data['sync_task_logs_count'], 
					$data['sync_task_status_versions_count']
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
	 * Create and store new sync task status
	 * 
	 * Excepted HTTP code : 201
	 * 
	 * @param string $id
	 * 
	 * @return SyncTaskStatusResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function create($id)
	{
		$routeUrl = '/api/syncTaskStatus';

		$bodyParameters = [];
		$bodyParameters['id'] = $id;

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

		$response = new SyncTaskStatusResponse(
			$this->apiClient, 
			new SyncTaskStatus(
				$this->apiClient, 
				$requestBody['data']['id'], 
				$requestBody['data']['sync_tasks_count'], 
				$requestBody['data']['sync_task_logs_count'], 
				$requestBody['data']['sync_task_status_versions_count']
			)
		);

		return $response;
	}
	
	/**
	 * Get specified sync task status
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $syncTaskStatusId Sync task type ID
	 * 
	 * @return SyncTaskStatusResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function get($syncTaskStatusId)
	{
		$routePath = '/api/syncTaskStatus/{syncTaskStatusId}';

		$pathReplacements = [
			'{syncTaskStatusId}' => $syncTaskStatusId,
		];

		$routeUrl = str_replace(array_keys($pathReplacements), array_values($pathReplacements), $routePath);

		$requestOptions = [];

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

		$response = new SyncTaskStatusResponse(
			$this->apiClient, 
			new SyncTaskStatus(
				$this->apiClient, 
				$requestBody['data']['id'], 
				$requestBody['data']['sync_tasks_count'], 
				$requestBody['data']['sync_task_logs_count'], 
				$requestBody['data']['sync_task_status_versions_count']
			)
		);

		return $response;
	}
	
	/**
	 * Update a sync task status
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $syncTaskStatusId Sync task status ID
	 * @param string $id
	 * 
	 * @return SyncTaskStatusResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function update($syncTaskStatusId, $id)
	{
		$routePath = '/api/syncTaskStatus/{syncTaskStatusId}';

		$pathReplacements = [
			'{syncTaskStatusId}' => $syncTaskStatusId,
		];

		$routeUrl = str_replace(array_keys($pathReplacements), array_values($pathReplacements), $routePath);

		$bodyParameters = [];
		$bodyParameters['id'] = $id;

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

		$response = new SyncTaskStatusResponse(
			$this->apiClient, 
			new SyncTaskStatus(
				$this->apiClient, 
				$requestBody['data']['id'], 
				$requestBody['data']['sync_tasks_count'], 
				$requestBody['data']['sync_task_logs_count'], 
				$requestBody['data']['sync_task_status_versions_count']
			)
		);

		return $response;
	}
	
	/**
	 * Delete specified sync task status
	 * 
	 * The sync task status versions will be automatically deleted too.<br />
	 * <aside class="warning">Avoid using this feature ! Check foreign keys constraints to remove dependent resources properly before.</aside>
	 * 
	 * Excepted HTTP code : 204
	 * 
	 * @param string $syncTaskStatusId Sync task status ID
	 * 
	 * @return ErrorResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function delete($syncTaskStatusId)
	{
		$routePath = '/api/syncTaskStatus/{syncTaskStatusId}';

		$pathReplacements = [
			'{syncTaskStatusId}' => $syncTaskStatusId,
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
