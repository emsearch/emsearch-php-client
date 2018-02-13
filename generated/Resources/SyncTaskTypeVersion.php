<?php

namespace Emsearch\Api\Resources;

use Emsearch\Api\ApiClient;
use Emsearch\Api\Exceptions\UnexpectedResponseException;

/**
 * SyncTaskTypeVersion resource class
 * 
 * @package Emsearch\Api\Resources
 */
class SyncTaskTypeVersion 
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
	public $sync_task_type_id;

	/**
	 * @var string
	 */
	public $i18n_lang_id;

	/**
	 * @var string
	 */
	public $description;

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
	 * SyncTaskTypeVersion resource class constructor
	 * 
	 * @param ApiClient $apiClient API Client to use for this manager requests
	 * @param string $sync_task_type_id Format: uuid.
	 * @param string $i18n_lang_id
	 * @param string $description
	 * @param string $created_at Format: date-time.
	 * @param string $updated_at Format: date-time.
	 */
	public function __construct(ApiClient $apiClient, $sync_task_type_id = null, $i18n_lang_id = null, $description = null, $created_at = null, $updated_at = null)
	{
		$this->apiClient = $apiClient;
		$this->sync_task_type_id = $sync_task_type_id;
		$this->i18n_lang_id = $i18n_lang_id;
		$this->description = $description;
		$this->created_at = $created_at;
		$this->updated_at = $updated_at;
	}
	/**
	 * Update a specified sync task type version
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
	public function update($sync_task_type_id, $i18n_lang_id, $description)
	{
		$routePath = '/api/syncTaskTypeVersion/{syncTaskTypeId},{i18nLangId}';

		$pathReplacements = [
			'{syncTaskTypeId}' => $this->sync_task_type_id,
			'{i18nLangId}' => $this->i18n_lang_id,
		];

		$routeUrl = str_replace(array_keys($pathReplacements), array_values($pathReplacements), $routePath);

		$bodyParameters = [];
		$bodyParameters['sync_task_type_id'] = $sync_task_type_id;
		$bodyParameters['i18n_lang_id'] = $i18n_lang_id;
		$bodyParameters['description'] = $description;

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
	 * @return ErrorResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function delete()
	{
		$routePath = '/api/syncTaskTypeVersion/{syncTaskTypeId},{i18nLangId}';

		$pathReplacements = [
			'{syncTaskTypeId}' => $this->sync_task_type_id,
			'{i18nLangId}' => $this->i18n_lang_id,
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
