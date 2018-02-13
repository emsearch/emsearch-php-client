<?php

namespace Emsearch\Api\Resources;

use Emsearch\Api\ApiClient;
use Emsearch\Api\Exceptions\UnexpectedResponseException;

/**
 * SyncTaskType resource class
 * 
 * @package Emsearch\Api\Resources
 */
class SyncTaskType 
{
	/**
	 * API client
	 *
	 * @var ApiClient
	 */
	protected $apiClient;

	/**
	 * @var string
	 */
	public $id;

	/**
	 * Format: int32.
	 * 
	 * @var int
	 */
	public $sync_tasks_count;

	/**
	 * Format: int32.
	 * 
	 * @var int
	 */
	public $sync_task_type_versions_count;

	/**
	 * SyncTaskType resource class constructor
	 * 
	 * @param ApiClient $apiClient API Client to use for this manager requests
	 * @param string $id
	 * @param int $sync_tasks_count Format: int32.
	 * @param int $sync_task_type_versions_count Format: int32.
	 */
	public function __construct(ApiClient $apiClient, $id = null, $sync_tasks_count = null, $sync_task_type_versions_count = null)
	{
		$this->apiClient = $apiClient;
		$this->id = $id;
		$this->sync_tasks_count = $sync_tasks_count;
		$this->sync_task_type_versions_count = $sync_task_type_versions_count;
	}
	/**
	 * Update a sync task type
	 * 
	 * Excepted HTTP code : 201
	 * 
	 * @param string $id
	 * 
	 * @return SyncTaskTypeResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function update($id)
	{
		$routePath = '/api/syncTaskType/{syncTaskTypeId}';

		$pathReplacements = [
			'{syncTaskTypeId}' => $this->id,
		];

		$routeUrl = str_replace(array_keys($pathReplacements), array_values($pathReplacements), $routePath);

		$bodyParameters = [];
		$bodyParameters['id'] = $id;

		$requestOptions = [];
		$requestOptions['form_params'] = $bodyParameters;

		$request = $this->apiClient->getHttpClient()->request('patch', $routeUrl, $requestOptions);

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

		$response = new SyncTaskTypeResponse(
			$this->apiClient, 
			new SyncTaskType(
				$this->apiClient, 
				$requestBody['data']['id'], 
				$requestBody['data']['sync_tasks_count'], 
				$requestBody['data']['sync_task_type_versions_count']
			)
		);

		return $response;
	}
	
	/**
	 * Delete specified sync task type
	 * 
	 * The sync task type versions will be automatically deleted too.<br />
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
		$routePath = '/api/syncTaskType/{syncTaskTypeId}';

		$pathReplacements = [
			'{syncTaskTypeId}' => $this->id,
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
	
	/**
	 * Sync task type sync task type versions list
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $search Search words
	 * @param int $page Format: int32. Pagination : Page number
	 * @param int $limit Format: int32. Pagination : Maximum entries per page
	 * @param string $order_by Order by : {field},[asc|desc]
	 * 
	 * @return SyncTaskTypeVersionListResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function getVersions($search = null, $page = null, $limit = null, $order_by = null)
	{
		$routePath = '/api/syncTaskType/{syncTaskTypeId}/version';

		$pathReplacements = [
			'{syncTaskTypeId}' => $this->id,
		];

		$routeUrl = str_replace(array_keys($pathReplacements), array_values($pathReplacements), $routePath);

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

		$response = new SyncTaskTypeVersionListResponse(
			$this->apiClient, 
			array_map(function($data) {
				return new SyncTaskTypeVersion(
					$this->apiClient, 
					$data['sync_task_type_id'], 
					$data['i18n_lang_id'], 
					$data['description'], 
					$data['created_at'], 
					$data['updated_at']
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
