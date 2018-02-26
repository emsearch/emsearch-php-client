<?php

namespace Emsearch\Api\Managers;

use Emsearch\Api\ApiClient;
use Emsearch\Api\Exceptions\UnexpectedResponseException;
use Emsearch\Api\Resources\SyncTaskTypeVersionListResponse;
use Emsearch\Api\Resources\ErrorResponse;
use Emsearch\Api\Resources\SyncTaskTypeVersionResponse;
use Emsearch\Api\Resources\SyncTaskTypeVersion;
use Emsearch\Api\Resources\Meta;
use Emsearch\Api\Resources\Pagination;

/**
 * SyncTaskTypeVersion manager class
 * 
 * @package Emsearch\Api\Managers
 */
class SyncTaskTypeVersionManager 
{
	/**
	 * API client
	 *
	 * @var ApiClient
	 */
	protected $apiClient;

	/**
	 * SyncTaskTypeVersion manager class constructor
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
	 * Sync task type version item list
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
	public function all($search = null, $page = null, $limit = null, $order_by = null)
	{
		$routeUrl = '/api/syncTaskTypeVersion';

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
	
	/**
	 * Create and store new sync task type version
	 * 
	 * Excepted HTTP code : 201
	 * 
	 * @param string $sync_task_type_id
	 * @param string $i18n_lang_id
	 * @param string $description
	 * 
	 * @return SyncTaskTypeVersionResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function create($sync_task_type_id, $i18n_lang_id, $description)
	{
		$routeUrl = '/api/syncTaskTypeVersion';

		$bodyParameters = [];
		$bodyParameters['sync_task_type_id'] = $sync_task_type_id;
		$bodyParameters['i18n_lang_id'] = $i18n_lang_id;
		$bodyParameters['description'] = $description;

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

		$response = new SyncTaskTypeVersionResponse(
			$this->apiClient, 
			new SyncTaskTypeVersion(
				$this->apiClient, 
				$requestBody['data']['sync_task_type_id'], 
				$requestBody['data']['i18n_lang_id'], 
				$requestBody['data']['description'], 
				$requestBody['data']['created_at'], 
				$requestBody['data']['updated_at']
			)
		);

		return $response;
	}
	
	/**
	 * Get specified sync task type version
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $syncTaskTypeId Sync Task Type ID
	 * @param string $i18nLangId I18n Lang Id
	 * 
	 * @return SyncTaskTypeVersionResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function get($syncTaskTypeId, $i18nLangId)
	{
		$routePath = '/api/syncTaskTypeVersion/{syncTaskTypeId},{i18nLangId}';

		$pathReplacements = [
			'{syncTaskTypeId}' => $syncTaskTypeId,
			'{i18nLangId}' => $i18nLangId,
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

		$response = new SyncTaskTypeVersionResponse(
			$this->apiClient, 
			new SyncTaskTypeVersion(
				$this->apiClient, 
				$requestBody['data']['sync_task_type_id'], 
				$requestBody['data']['i18n_lang_id'], 
				$requestBody['data']['description'], 
				$requestBody['data']['created_at'], 
				$requestBody['data']['updated_at']
			)
		);

		return $response;
	}
	
	/**
	 * Update a specified sync task type version
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $syncTaskTypeId Sync Task Type ID
	 * @param string $i18nLangId I18n Lang Id
	 * @param string $sync_task_type_id
	 * @param string $i18n_lang_id
	 * @param string $description
	 * 
	 * @return SyncTaskTypeVersionResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function update($syncTaskTypeId, $i18nLangId, $sync_task_type_id, $i18n_lang_id, $description)
	{
		$routePath = '/api/syncTaskTypeVersion/{syncTaskTypeId},{i18nLangId}';

		$pathReplacements = [
			'{syncTaskTypeId}' => $syncTaskTypeId,
			'{i18nLangId}' => $i18nLangId,
		];

		$routeUrl = str_replace(array_keys($pathReplacements), array_values($pathReplacements), $routePath);

		$bodyParameters = [];
		$bodyParameters['sync_task_type_id'] = $sync_task_type_id;
		$bodyParameters['i18n_lang_id'] = $i18n_lang_id;
		$bodyParameters['description'] = $description;

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

		$response = new SyncTaskTypeVersionResponse(
			$this->apiClient, 
			new SyncTaskTypeVersion(
				$this->apiClient, 
				$requestBody['data']['sync_task_type_id'], 
				$requestBody['data']['i18n_lang_id'], 
				$requestBody['data']['description'], 
				$requestBody['data']['created_at'], 
				$requestBody['data']['updated_at']
			)
		);

		return $response;
	}
	
	/**
	 * Delete specified sync task type version
	 * 
	 * Excepted HTTP code : 204
	 * 
	 * @param string $syncTaskTypeId Sync Task Type ID
	 * @param string $i18nLangId I18n Lang Id
	 * 
	 * @return ErrorResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function delete($syncTaskTypeId, $i18nLangId)
	{
		$routePath = '/api/syncTaskTypeVersion/{syncTaskTypeId},{i18nLangId}';

		$pathReplacements = [
			'{syncTaskTypeId}' => $syncTaskTypeId,
			'{i18nLangId}' => $i18nLangId,
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
