<?php

namespace emsearch\Api\Resources;

use emsearch\Api\ApiClient;
use emsearch\Api\Exceptions\UnexpectedResponseException;

/**
 * SyncItem resource class
 * 
 * @package emsearch\Api\Resources
 */
class SyncItem 
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
	 * Format: uuid.
	 * 
	 * @var string
	 */
	public $project_id;

	/**
	 * Format: md5.
	 * 
	 * @var string
	 */
	public $item_signature;

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
	 * SyncItem resource class constructor
	 * 
	 * @param ApiClient $apiClient API Client to use for this manager requests
	 * @param string $id
	 * @param string $project_id Format: uuid.
	 * @param string $item_signature Format: md5.
	 * @param string $created_at Format: date-time.
	 * @param string $updated_at Format: date-time.
	 * @param SearchUseCasePresetResponse $searchUseCase
	 */
	public function __construct(ApiClient $apiClient, $id = null, $project_id = null, $item_signature = null, $created_at = null, $updated_at = null, $searchUseCase = null)
	{
		$this->apiClient = $apiClient;
		$this->id = $id;
		$this->project_id = $project_id;
		$this->item_signature = $item_signature;
		$this->created_at = $created_at;
		$this->updated_at = $updated_at;
		$this->searchUseCase = $searchUseCase;
	}
	/**
	 * Update a specified sync item
	 * 
	 * Excepted HTTP code : 201
	 * 
	 * @param string $item_id
	 * @param string $project_id Format: uuid.
	 * @param string $item_signature
	 * 
	 * @return SyncItemResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function update($item_id, $project_id, $item_signature = null)
	{
		$routePath = '/api/syncItem/{syncItemId},{projectId}';

		$pathReplacements = [
			'{syncItemId}' => $this->id,
			'{projectId}' => $this->project_id,
		];

		$routeUrl = str_replace(array_keys($pathReplacements), array_values($pathReplacements), $routePath);

		$bodyParameters = [];
		$bodyParameters['item_id'] = $item_id;
		$bodyParameters['project_id'] = $project_id;

		if (!is_null($item_signature)) {
			$bodyParameters['item_signature'] = $item_signature;
		}

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

		$response = new SyncItemResponse(
			$this->apiClient, 
			new SyncItem(
				$this->apiClient, 
				(isset($requestBody['data']['id']) ? $requestBody['data']['id'] : null), 
				$requestBody['data']['project_id'], 
				$requestBody['data']['item_signature'], 
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
	 * Delete specified sync item
	 * 
	 * Excepted HTTP code : 204
	 * 
	 * @return ErrorResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function delete()
	{
		$routePath = '/api/syncItem/{syncItemId},{projectId}';

		$pathReplacements = [
			'{syncItemId}' => $this->id,
			'{projectId}' => $this->project_id,
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
