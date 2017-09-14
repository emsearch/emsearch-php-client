<?php

namespace emsearch\Api\Resources;

use emsearch\Api\ApiClient;
use emsearch\Api\Exceptions\UnexpectedResponseException;

/**
 * DataStreamDecoder resource class
 * 
 * @package emsearch\Api\Resources
 */
class DataStreamDecoder 
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
	public $id;

	/**
	 * @var string
	 */
	public $name;

	/**
	 * @var string
	 */
	public $class_name;

	/**
	 * @var string
	 */
	public $file_mime_type;

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
	 * DataStreamDecoder resource class constructor
	 * 
	 * @param ApiClient $apiClient API Client to use for this manager requests
	 * @param string $id Format: uuid.
	 * @param string $name
	 * @param string $class_name
	 * @param string $file_mime_type
	 * @param string $created_at Format: date-time.
	 * @param string $updated_at Format: date-time.
	 */
	public function __construct(ApiClient $apiClient, $id = null, $name = null, $class_name = null, $file_mime_type = null, $created_at = null, $updated_at = null)
	{
		$this->apiClient = $apiClient;
		$this->id = $id;
		$this->name = $name;
		$this->class_name = $class_name;
		$this->file_mime_type = $file_mime_type;
		$this->created_at = $created_at;
		$this->updated_at = $updated_at;
	}
	/**
	 * Update a data stream decoder
	 * 
	 * Excepted HTTP code : 201
	 * 
	 * @param string $name
	 * @param string $class_name
	 * @param string $file_mime_type
	 * 
	 * @return DataStreamDecoderResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function update($name, $class_name, $file_mime_type)
	{
		$routePath = '/api/dataStreamDecoder/{dataStreamDecoderId}';

		$pathReplacements = [
			'{dataStreamDecoderId}' => $this->id,
		];

		$routeUrl = str_replace(array_keys($pathReplacements), array_values($pathReplacements), $routePath);

		$bodyParameters = [];
		$bodyParameters['name'] = $name;
		$bodyParameters['class_name'] = $class_name;
		$bodyParameters['file_mime_type'] = $file_mime_type;

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

		$response = new DataStreamDecoderResponse(
			$this->apiClient, 
			((isset($requestBody['data']) && !is_null($requestBody['data'])) ? (new DataStreamDecoder(
				$this->apiClient, 
				$requestBody['data']['id'], 
				$requestBody['data']['name'], 
				$requestBody['data']['class_name'], 
				$requestBody['data']['file_mime_type'], 
				$requestBody['data']['created_at'], 
				$requestBody['data']['updated_at']
			)) : null)
		);

		return $response;
	}
	
	/**
	 * Delete specified data stream decoder
	 * 
	 * Excepted HTTP code : 204
	 * 
	 * @return ErrorResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function delete()
	{
		$routePath = '/api/dataStreamDecoder/{dataStreamDecoderId}';

		$pathReplacements = [
			'{dataStreamDecoderId}' => $this->id,
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
