<?php

namespace Emsearch\Api\Managers;

use Emsearch\Api\ApiClient;
use Emsearch\Api\Exceptions\UnexpectedResponseException;
use Emsearch\Api\Resources\SyncTaskStatusVersionListResponse;
use Emsearch\Api\Resources\ErrorResponse;
use Emsearch\Api\Resources\SyncTaskStatusVersionResponse;
use Emsearch\Api\Resources\SyncTaskStatusVersion;
use Emsearch\Api\Resources\Meta;
use Emsearch\Api\Resources\Pagination;

/**
 * SyncTaskStatusVersion manager class
 * 
 * @package Emsearch\Api\Managers
 */
class SyncTaskStatusVersionManager 
{
	/**
	 * API client
	 *
	 * @var ApiClient
	 */
	protected $apiClient;

	/**
	 * SyncTaskStatusVersion manager class constructor
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
	 * Sync task status version item list
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $search Search words
	 * @param int $page Format: int32. Pagination : Page number
	 * @param int $limit Format: int32. Pagination : Maximum entries per page
	 * @param string $order_by Order by : {field},[asc|desc]
	 * 
	 * @return SyncTaskStatusVersionListResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function all($search = null, $page = null, $limit = null, $order_by = null)
	{
		$routeUrl = '/api/syncTaskStatusVersion';

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

		$response = new SyncTaskStatusVersionListResponse(
			$this->apiClient, 
			array_map(function($data) {
				return new SyncTaskStatusVersion(
					$this->apiClient, 
					$data['sync_task_status_id'], 
					$data['i18n_lang_id'], 
					(isset($data['description']) ? $data['description'] : null), 
					(isset($data['created_at']) ? $data['created_at'] : null), 
					(isset($data['updated_at']) ? $data['updated_at'] : null)
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
	 * Create and store new sync task status version
	 * 
	 * Excepted HTTP code : 201
	 * 
	 * @param string $sync_task_status_id
	 * @param string $i18n_lang_id
	 * @param string $description
	 * 
	 * @return SyncTaskStatusVersionResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function create($sync_task_status_id, $i18n_lang_id, $description)
	{
		$routeUrl = '/api/syncTaskStatusVersion';

		$bodyParameters = [];
		$bodyParameters['sync_task_status_id'] = $sync_task_status_id;
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

		$response = new SyncTaskStatusVersionResponse(
			$this->apiClient, 
			new SyncTaskStatusVersion(
				$this->apiClient, 
				$requestBody['data']['sync_task_status_id'], 
				$requestBody['data']['i18n_lang_id'], 
				(isset($requestBody['data']['description']) ? $requestBody['data']['description'] : null), 
				(isset($requestBody['data']['created_at']) ? $requestBody['data']['created_at'] : null), 
				(isset($requestBody['data']['updated_at']) ? $requestBody['data']['updated_at'] : null)
			)
		);

		return $response;
	}
	
	/**
	 * Get specified sync task status version
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $syncTaskStatusId Sync Task Status ID
	 * @param string $i18nLangId I18n Lang Id
	 * 
	 * @return SyncTaskStatusVersionResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function get($syncTaskStatusId, $i18nLangId)
	{
		$routePath = '/api/syncTaskStatusVersion/{syncTaskStatusId},{i18nLangId}';

		$pathReplacements = [
			'{syncTaskStatusId}' => $syncTaskStatusId,
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

		$response = new SyncTaskStatusVersionResponse(
			$this->apiClient, 
			new SyncTaskStatusVersion(
				$this->apiClient, 
				$requestBody['data']['sync_task_status_id'], 
				$requestBody['data']['i18n_lang_id'], 
				(isset($requestBody['data']['description']) ? $requestBody['data']['description'] : null), 
				(isset($requestBody['data']['created_at']) ? $requestBody['data']['created_at'] : null), 
				(isset($requestBody['data']['updated_at']) ? $requestBody['data']['updated_at'] : null)
			)
		);

		return $response;
	}
	
	/**
	 * Update a specified sync task status version
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $syncTaskStatusId Sync Task Status ID
	 * @param string $i18nLangId I18n Lang Id
	 * @param string $sync_task_status_id
	 * @param string $i18n_lang_id
	 * @param string $description
	 * 
	 * @return SyncTaskStatusVersionResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function update($syncTaskStatusId, $i18nLangId, $sync_task_status_id, $i18n_lang_id, $description)
	{
		$routePath = '/api/syncTaskStatusVersion/{syncTaskStatusId},{i18nLangId}';

		$pathReplacements = [
			'{syncTaskStatusId}' => $syncTaskStatusId,
			'{i18nLangId}' => $i18nLangId,
		];

		$routeUrl = str_replace(array_keys($pathReplacements), array_values($pathReplacements), $routePath);

		$bodyParameters = [];
		$bodyParameters['sync_task_status_id'] = $sync_task_status_id;
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

		$response = new SyncTaskStatusVersionResponse(
			$this->apiClient, 
			new SyncTaskStatusVersion(
				$this->apiClient, 
				$requestBody['data']['sync_task_status_id'], 
				$requestBody['data']['i18n_lang_id'], 
				(isset($requestBody['data']['description']) ? $requestBody['data']['description'] : null), 
				(isset($requestBody['data']['created_at']) ? $requestBody['data']['created_at'] : null), 
				(isset($requestBody['data']['updated_at']) ? $requestBody['data']['updated_at'] : null)
			)
		);

		return $response;
	}
	
	/**
	 * Delete specified sync task status version
	 * 
	 * Excepted HTTP code : 204
	 * 
	 * @param string $syncTaskStatusId Sync Task Status ID
	 * @param string $i18nLangId I18n Lang Id
	 * 
	 * @return ErrorResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function delete($syncTaskStatusId, $i18nLangId)
	{
		$routePath = '/api/syncTaskStatusVersion/{syncTaskStatusId},{i18nLangId}';

		$pathReplacements = [
			'{syncTaskStatusId}' => $syncTaskStatusId,
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
