<?php

namespace Emsearch\Api\Resources;

use Emsearch\Api\ApiClient;
use Emsearch\Api\Exceptions\UnexpectedResponseException;

/**
 * Widget resource class
 * 
 * @package Emsearch\Api\Resources
 */
class Widget 
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
	 * Format: uuid.
	 * 
	 * @var string
	 */
	public $search_use_case_id;

	/**
	 * @var string
	 */
	public $name;

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
	 * @var SearchUseCasePresetResponse
	 */
	public $searchUseCase;

	/**
	 * Widget resource class constructor
	 * 
	 * @param ApiClient $apiClient API Client to use for this manager requests
	 * @param string $id Format: uuid.
	 * @param string $search_use_case_id Format: uuid.
	 * @param string $name
	 * @param string $created_at Format: date-time.
	 * @param string $updated_at Format: date-time.
	 * @param SearchUseCasePresetResponse $searchUseCase
	 */
	public function __construct(ApiClient $apiClient, $id = null, $search_use_case_id = null, $name = null, $created_at = null, $updated_at = null, $searchUseCase = null)
	{
		$this->apiClient = $apiClient;
		$this->id = $id;
		$this->search_use_case_id = $search_use_case_id;
		$this->name = $name;
		$this->created_at = $created_at;
		$this->updated_at = $updated_at;
		$this->searchUseCase = $searchUseCase;
	}
	/**
	 * Update a widget
	 * 
	 * Excepted HTTP code : 201
	 * 
	 * @param string $search_use_case_id Format: uuid.
	 * @param string $name
	 * 
	 * @return WidgetResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function update($search_use_case_id, $name)
	{
		$routePath = '/api/widget/{widgetId}';

		$pathReplacements = [
			'{widgetId}' => $this->id,
		];

		$routeUrl = str_replace(array_keys($pathReplacements), array_values($pathReplacements), $routePath);

		$bodyParameters = [];
		$bodyParameters['search_use_case_id'] = $search_use_case_id;
		$bodyParameters['name'] = $name;

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

		$response = new WidgetResponse(
			$this->apiClient, 
			new Widget(
				$this->apiClient, 
				$requestBody['data']['id'], 
				$requestBody['data']['search_use_case_id'], 
				$requestBody['data']['name'], 
				$requestBody['data']['created_at'], 
				$requestBody['data']['updated_at'], 
				((isset($requestBody['data']['searchUseCase']) && !is_null($requestBody['data']['searchUseCase'])) ? (new SearchUseCasePresetResponse(
					$this->apiClient, 
					new SearchUseCasePreset(
						$this->apiClient, 
						$requestBody['data']['searchUseCase']['data']['id'], 
						$requestBody['data']['searchUseCase']['data']['data_stream_preset_id'], 
						$requestBody['data']['searchUseCase']['data']['name'], 
						$requestBody['data']['searchUseCase']['data']['created_at'], 
						$requestBody['data']['searchUseCase']['data']['updated_at'], 
						$requestBody['data']['searchUseCase']['data']['search_use_case_preset_fields_count'], 
						((isset($requestBody['data']['searchUseCase']['data']['dataStreamPreset']) && !is_null($requestBody['data']['searchUseCase']['data']['dataStreamPreset'])) ? (new DataStreamPresetResponse(
							$this->apiClient, 
							new DataStreamPreset(
								$this->apiClient, 
								$requestBody['data']['searchUseCase']['data']['dataStreamPreset']['data']['id'], 
								$requestBody['data']['searchUseCase']['data']['dataStreamPreset']['data']['data_stream_decoder_id'], 
								$requestBody['data']['searchUseCase']['data']['dataStreamPreset']['data']['name'], 
								$requestBody['data']['searchUseCase']['data']['dataStreamPreset']['data']['created_at'], 
								$requestBody['data']['searchUseCase']['data']['dataStreamPreset']['data']['updated_at'], 
								((isset($requestBody['data']['searchUseCase']['data']['dataStreamPreset']['data']['dataStreamDecoder']) && !is_null($requestBody['data']['searchUseCase']['data']['dataStreamPreset']['data']['dataStreamDecoder'])) ? (new DataStreamDecoderResponse(
									$this->apiClient, 
									new DataStreamDecoder(
										$this->apiClient, 
										$requestBody['data']['searchUseCase']['data']['dataStreamPreset']['data']['dataStreamDecoder']['data']['id'], 
										$requestBody['data']['searchUseCase']['data']['dataStreamPreset']['data']['dataStreamDecoder']['data']['name'], 
										$requestBody['data']['searchUseCase']['data']['dataStreamPreset']['data']['dataStreamDecoder']['data']['class_name'], 
										$requestBody['data']['searchUseCase']['data']['dataStreamPreset']['data']['dataStreamDecoder']['data']['file_mime_type'], 
										$requestBody['data']['searchUseCase']['data']['dataStreamPreset']['data']['dataStreamDecoder']['data']['created_at'], 
										$requestBody['data']['searchUseCase']['data']['dataStreamPreset']['data']['dataStreamDecoder']['data']['updated_at']
									)
								)) : null)
							)
						)) : null)
					)
				)) : null)
			)
		);

		return $response;
	}
	
	/**
	 * Delete specified widget
	 * 
	 * Excepted HTTP code : 204
	 * 
	 * @return ErrorResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function delete()
	{
		$routePath = '/api/widget/{widgetId}';

		$pathReplacements = [
			'{widgetId}' => $this->id,
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
