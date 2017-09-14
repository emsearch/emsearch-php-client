<?php

namespace emsearch\Api\Resources;

use emsearch\Api\ApiClient;
use emsearch\Api\Exceptions\UnexpectedResponseException;

/**
 * DataStreamHasI18nLang resource class
 * 
 * @package emsearch\Api\Resources
 */
class DataStreamHasI18nLang 
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
	public $data_stream_id;

	/**
	 * @var string
	 */
	public $i18n_lang_id;

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
	 * DataStreamHasI18nLang resource class constructor
	 * 
	 * @param ApiClient $apiClient API Client to use for this manager requests
	 * @param string $data_stream_id Format: uuid.
	 * @param string $i18n_lang_id
	 * @param string $created_at Format: date-time.
	 * @param string $updated_at Format: date-time.
	 */
	public function __construct(ApiClient $apiClient, $data_stream_id = null, $i18n_lang_id = null, $created_at = null, $updated_at = null)
	{
		$this->apiClient = $apiClient;
		$this->data_stream_id = $data_stream_id;
		$this->i18n_lang_id = $i18n_lang_id;
		$this->created_at = $created_at;
		$this->updated_at = $updated_at;
	}
	/**
	 * Update a specified relationship between a data stream and a i18n lang
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
	public function update($data_stream_id, $i18n_lang_id)
	{
		$routePath = '/api/dataStreamHasI18nLang/{dataStreamId},{i18nLangId}';

		$pathReplacements = [
			'{dataStreamId}' => $this->data_stream_id,
			'{i18nLangId}' => $this->i18n_lang_id,
		];

		$routeUrl = str_replace(array_keys($pathReplacements), array_values($pathReplacements), $routePath);

		$bodyParameters = [];
		$bodyParameters['data_stream_id'] = $data_stream_id;
		$bodyParameters['i18n_lang_id'] = $i18n_lang_id;

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

		$response = new DataStreamHasI18nLangResponse(
			$this->apiClient, 
			((isset($requestBody['data']) && !is_null($requestBody['data'])) ? (new DataStreamHasI18nLang(
				$this->apiClient, 
				$requestBody['data']['data_stream_id'], 
				$requestBody['data']['i18n_lang_id'], 
				$requestBody['data']['created_at'], 
				$requestBody['data']['updated_at']
			)) : null)
		);

		return $response;
	}
	
	/**
	 * Delete specified relationship between a data stream and a i18n lang
	 * 
	 * Excepted HTTP code : 204
	 * 
	 * @return ErrorResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function delete()
	{
		$routePath = '/api/dataStreamHasI18nLang/{dataStreamId},{i18nLangId}';

		$pathReplacements = [
			'{dataStreamId}' => $this->data_stream_id,
			'{i18nLangId}' => $this->i18n_lang_id,
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
