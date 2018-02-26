<?php

namespace Emsearch\Api\Managers;

use Emsearch\Api\ApiClient;
use Emsearch\Api\Exceptions\UnexpectedResponseException;
use Emsearch\Api\Resources\DataStreamHasI18nLangListResponse;
use Emsearch\Api\Resources\ErrorResponse;
use Emsearch\Api\Resources\DataStreamHasI18nLangResponse;
use Emsearch\Api\Resources\DataStreamHasI18nLang;
use Emsearch\Api\Resources\Meta;
use Emsearch\Api\Resources\Pagination;

/**
 * DataStreamHasI18nLang manager class
 * 
 * @package Emsearch\Api\Managers
 */
class DataStreamHasI18nLangManager 
{
	/**
	 * API client
	 *
	 * @var ApiClient
	 */
	protected $apiClient;

	/**
	 * DataStreamHasI18nLang manager class constructor
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
	 * List of relationships between data streams and i18n langs
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $include Include responses : {include1},{include2,{include3}[...]
	 * @param string $search Search words
	 * @param int $page Format: int32. Pagination : Page number
	 * @param int $limit Format: int32. Pagination : Maximum entries per page
	 * @param string $order_by Order by : {field},[asc|desc]
	 * 
	 * @return DataStreamHasI18nLangListResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function all($include = null, $search = null, $page = null, $limit = null, $order_by = null)
	{
		$routeUrl = '/api/dataStreamHasI18nLang';

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

		$response = new DataStreamHasI18nLangListResponse(
			$this->apiClient, 
			array_map(function($data) {
				return new DataStreamHasI18nLang(
					$this->apiClient, 
					$data['data_stream_id'], 
					$data['i18n_lang_id'], 
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
	 * Create and store new relationship between a data stream and a i18n lang
	 * 
	 * <aside class="notice">Only one relationship per data stream/i18n lang is allowed.</aside>
	 * 
	 * Excepted HTTP code : 201
	 * 
	 * @param string $data_stream_id Format: uuid.
	 * @param string $i18n_lang_id
	 * 
	 * @return DataStreamHasI18nLangResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function create($data_stream_id, $i18n_lang_id)
	{
		$routeUrl = '/api/dataStreamHasI18nLang';

		$bodyParameters = [];
		$bodyParameters['data_stream_id'] = $data_stream_id;
		$bodyParameters['i18n_lang_id'] = $i18n_lang_id;

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

		$response = new DataStreamHasI18nLangResponse(
			$this->apiClient, 
			new DataStreamHasI18nLang(
				$this->apiClient, 
				$requestBody['data']['data_stream_id'], 
				$requestBody['data']['i18n_lang_id'], 
				$requestBody['data']['created_at'], 
				$requestBody['data']['updated_at']
			)
		);

		return $response;
	}
	
	/**
	 * Get specified relationship between a data stream and a i18n lang
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $dataStreamId Data Stream UUID
	 * @param string $i18nLangId I18n Land Id
	 * @param string $include Include responses : {include1},{include2,{include3}[...]
	 * 
	 * @return DataStreamHasI18nLangResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function get($dataStreamId, $i18nLangId, $include = null)
	{
		$routePath = '/api/dataStreamHasI18nLang/{dataStreamId},{i18nLangId}';

		$pathReplacements = [
			'{dataStreamId}' => $dataStreamId,
			'{i18nLangId}' => $i18nLangId,
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

		$response = new DataStreamHasI18nLangResponse(
			$this->apiClient, 
			new DataStreamHasI18nLang(
				$this->apiClient, 
				$requestBody['data']['data_stream_id'], 
				$requestBody['data']['i18n_lang_id'], 
				$requestBody['data']['created_at'], 
				$requestBody['data']['updated_at']
			)
		);

		return $response;
	}
	
	/**
	 * Update a specified relationship between a data stream and a i18n lang
	 * 
	 * <aside class="notice">Only one relationship per data stream/i18n lang is allowed.</aside>
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $dataStreamId Data Stream UUID
	 * @param string $i18nLangId I18n Land Id
	 * @param string $data_stream_id Format: uuid.
	 * @param string $i18n_lang_id
	 * 
	 * @return DataStreamHasI18nLangResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function update($dataStreamId, $i18nLangId, $data_stream_id, $i18n_lang_id)
	{
		$routePath = '/api/dataStreamHasI18nLang/{dataStreamId},{i18nLangId}';

		$pathReplacements = [
			'{dataStreamId}' => $dataStreamId,
			'{i18nLangId}' => $i18nLangId,
		];

		$routeUrl = str_replace(array_keys($pathReplacements), array_values($pathReplacements), $routePath);

		$bodyParameters = [];
		$bodyParameters['data_stream_id'] = $data_stream_id;
		$bodyParameters['i18n_lang_id'] = $i18n_lang_id;

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

		$response = new DataStreamHasI18nLangResponse(
			$this->apiClient, 
			new DataStreamHasI18nLang(
				$this->apiClient, 
				$requestBody['data']['data_stream_id'], 
				$requestBody['data']['i18n_lang_id'], 
				$requestBody['data']['created_at'], 
				$requestBody['data']['updated_at']
			)
		);

		return $response;
	}
	
	/**
	 * Delete specified relationship between a data stream and a i18n lang
	 * 
	 * Excepted HTTP code : 204
	 * 
	 * @param string $dataStreamId Data Stream UUID
	 * @param string $i18nLangId I18n Land Id
	 * 
	 * @return ErrorResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function delete($dataStreamId, $i18nLangId)
	{
		$routePath = '/api/dataStreamHasI18nLang/{dataStreamId},{i18nLangId}';

		$pathReplacements = [
			'{dataStreamId}' => $dataStreamId,
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
