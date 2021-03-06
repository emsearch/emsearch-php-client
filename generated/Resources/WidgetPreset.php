<?php

namespace Emsearch\Api\Resources;

use Emsearch\Api\ApiClient;
use Emsearch\Api\Exceptions\UnexpectedResponseException;

/**
 * WidgetPreset resource class
 * 
 * @package Emsearch\Api\Resources
 */
class WidgetPreset 
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
	public $search_use_case_preset_id;

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
	public $searchUseCasePreset;

	/**
	 * WidgetPreset resource class constructor
	 * 
	 * @param ApiClient $apiClient API Client to use for this manager requests
	 * @param string $id Format: uuid.
	 * @param string $search_use_case_preset_id Format: uuid.
	 * @param string $name
	 * @param string $created_at Format: date-time.
	 * @param string $updated_at Format: date-time.
	 * @param SearchUseCasePresetResponse $searchUseCasePreset
	 */
	public function __construct(ApiClient $apiClient, $id = null, $search_use_case_preset_id = null, $name = null, $created_at = null, $updated_at = null, $searchUseCasePreset = null)
	{
		$this->apiClient = $apiClient;
		$this->id = $id;
		$this->search_use_case_preset_id = $search_use_case_preset_id;
		$this->name = $name;
		$this->created_at = $created_at;
		$this->updated_at = $updated_at;
		$this->searchUseCasePreset = $searchUseCasePreset;
	}
	/**
	 * Update a widget preset
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $search_use_case_preset_id Format: uuid.
	 * @param string $name
	 * 
	 * @return WidgetPresetResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function update($search_use_case_preset_id, $name)
	{
		$routePath = '/api/widgetPreset/{widgetPresetId}';

		$pathReplacements = [
			'{widgetPresetId}' => $this->id,
		];

		$routeUrl = str_replace(array_keys($pathReplacements), array_values($pathReplacements), $routePath);

		$bodyParameters = [];
		$bodyParameters['search_use_case_preset_id'] = $search_use_case_preset_id;
		$bodyParameters['name'] = $name;

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

		$response = new WidgetPresetResponse(
			$this->apiClient, 
			new WidgetPreset(
				$this->apiClient, 
				$requestBody['data']['id'], 
				$requestBody['data']['search_use_case_preset_id'], 
				$requestBody['data']['name'], 
				$requestBody['data']['created_at'], 
				$requestBody['data']['updated_at'], 
				((isset($requestBody['data']['searchUseCasePreset']) && !is_null($requestBody['data']['searchUseCasePreset'])) ? (new SearchUseCasePresetResponse(
					$this->apiClient, 
					new SearchUseCasePreset(
						$this->apiClient, 
						$requestBody['data']['searchUseCasePreset']['data']['id'], 
						$requestBody['data']['searchUseCasePreset']['data']['data_stream_preset_id'], 
						$requestBody['data']['searchUseCasePreset']['data']['name'], 
						$requestBody['data']['searchUseCasePreset']['data']['created_at'], 
						$requestBody['data']['searchUseCasePreset']['data']['updated_at'], 
						$requestBody['data']['searchUseCasePreset']['data']['search_use_case_preset_fields_count'], 
						((isset($requestBody['data']['searchUseCasePreset']['data']['dataStreamPreset']) && !is_null($requestBody['data']['searchUseCasePreset']['data']['dataStreamPreset'])) ? (new DataStreamPresetResponse(
							$this->apiClient, 
							new DataStreamPreset(
								$this->apiClient, 
								$requestBody['data']['searchUseCasePreset']['data']['dataStreamPreset']['data']['id'], 
								$requestBody['data']['searchUseCasePreset']['data']['dataStreamPreset']['data']['data_stream_decoder_id'], 
								$requestBody['data']['searchUseCasePreset']['data']['dataStreamPreset']['data']['name'], 
								$requestBody['data']['searchUseCasePreset']['data']['dataStreamPreset']['data']['created_at'], 
								$requestBody['data']['searchUseCasePreset']['data']['dataStreamPreset']['data']['updated_at'], 
								((isset($requestBody['data']['searchUseCasePreset']['data']['dataStreamPreset']['data']['dataStreamDecoder']) && !is_null($requestBody['data']['searchUseCasePreset']['data']['dataStreamPreset']['data']['dataStreamDecoder'])) ? (new DataStreamDecoderResponse(
									$this->apiClient, 
									new DataStreamDecoder(
										$this->apiClient, 
										$requestBody['data']['searchUseCasePreset']['data']['dataStreamPreset']['data']['dataStreamDecoder']['data']['id'], 
										$requestBody['data']['searchUseCasePreset']['data']['dataStreamPreset']['data']['dataStreamDecoder']['data']['name'], 
										$requestBody['data']['searchUseCasePreset']['data']['dataStreamPreset']['data']['dataStreamDecoder']['data']['class_name'], 
										$requestBody['data']['searchUseCasePreset']['data']['dataStreamPreset']['data']['dataStreamDecoder']['data']['file_mime_type'], 
										$requestBody['data']['searchUseCasePreset']['data']['dataStreamPreset']['data']['dataStreamDecoder']['data']['created_at'], 
										$requestBody['data']['searchUseCasePreset']['data']['dataStreamPreset']['data']['dataStreamDecoder']['data']['updated_at']
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
	 * Delete specified widget preset
	 * 
	 * Excepted HTTP code : 204
	 * 
	 * @return ErrorResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function delete()
	{
		$routePath = '/api/widgetPreset/{widgetPresetId}';

		$pathReplacements = [
			'{widgetPresetId}' => $this->id,
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
