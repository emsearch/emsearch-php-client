<?php

namespace Emsearch\Api\Resources;

use Emsearch\Api\ApiClient;
use Emsearch\Api\Exceptions\UnexpectedResponseException;

/**
 * SearchUseCasePresetField resource class
 * 
 * @package Emsearch\Api\Resources
 */
class SearchUseCasePresetField 
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
	public $search_use_case_preset_id;

	/**
	 * Format: uuid.
	 * 
	 * @var string
	 */
	public $data_stream_preset_field_id;

	/**
	 * @var string
	 */
	public $name;

	/**
	 * @var boolean
	 */
	public $searchable;

	/**
	 * @var boolean
	 */
	public $to_retrieve;

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
	 * @var DataStreamPresetFieldResponse
	 */
	public $dataStreamPresetField;

	/**
	 * SearchUseCasePresetField resource class constructor
	 * 
	 * @param ApiClient $apiClient API Client to use for this manager requests
	 * @param string $search_use_case_preset_id Format: uuid.
	 * @param string $data_stream_preset_field_id Format: uuid.
	 * @param string $name
	 * @param boolean $searchable
	 * @param boolean $to_retrieve
	 * @param string $created_at Format: date-time.
	 * @param string $updated_at Format: date-time.
	 * @param SearchUseCasePresetResponse $searchUseCasePreset
	 * @param DataStreamPresetFieldResponse $dataStreamPresetField
	 */
	public function __construct(ApiClient $apiClient, $search_use_case_preset_id = null, $data_stream_preset_field_id = null, $name = null, $searchable = null, $to_retrieve = null, $created_at = null, $updated_at = null, $searchUseCasePreset = null, $dataStreamPresetField = null)
	{
		$this->apiClient = $apiClient;
		$this->search_use_case_preset_id = $search_use_case_preset_id;
		$this->data_stream_preset_field_id = $data_stream_preset_field_id;
		$this->name = $name;
		$this->searchable = $searchable;
		$this->to_retrieve = $to_retrieve;
		$this->created_at = $created_at;
		$this->updated_at = $updated_at;
		$this->searchUseCasePreset = $searchUseCasePreset;
		$this->dataStreamPresetField = $dataStreamPresetField;
	}
	/**
	 * Update a specified search use case preset field
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $search_use_case_preset_id Format: uuid.
	 * @param string $data_stream_preset_field_id Format: uuid.
	 * @param string $name
	 * @param boolean $searchable
	 * @param boolean $to_retrieve
	 * 
	 * @return SearchUseCasePresetFieldResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function update($search_use_case_preset_id, $data_stream_preset_field_id, $name, $searchable, $to_retrieve)
	{
		$routePath = '/api/searchUseCasePresetField/{searchUseCasePresetId},{dataStreamPresetFieldId}';

		$pathReplacements = [
			'{searchUseCasePresetId}' => $this->search_use_case_preset_id,
			'{dataStreamPresetFieldId}' => $this->data_stream_preset_field_id,
		];

		$routeUrl = str_replace(array_keys($pathReplacements), array_values($pathReplacements), $routePath);

		$bodyParameters = [];
		$bodyParameters['search_use_case_preset_id'] = $search_use_case_preset_id;
		$bodyParameters['data_stream_preset_field_id'] = $data_stream_preset_field_id;
		$bodyParameters['name'] = $name;
		$bodyParameters['searchable'] = $searchable;
		$bodyParameters['to_retrieve'] = $to_retrieve;

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

		$response = new SearchUseCasePresetFieldResponse(
			$this->apiClient, 
			new SearchUseCasePresetField(
				$this->apiClient, 
				$requestBody['data']['search_use_case_preset_id'], 
				$requestBody['data']['data_stream_preset_field_id'], 
				$requestBody['data']['name'], 
				$requestBody['data']['searchable'], 
				$requestBody['data']['to_retrieve'], 
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
				)) : null), 
				((isset($requestBody['data']['dataStreamPresetField']) && !is_null($requestBody['data']['dataStreamPresetField'])) ? (new DataStreamPresetFieldResponse(
					$this->apiClient, 
					new DataStreamPresetField(
						$this->apiClient, 
						$requestBody['data']['dataStreamPresetField']['data']['id'], 
						$requestBody['data']['dataStreamPresetField']['data']['data_stream_preset_id'], 
						$requestBody['data']['dataStreamPresetField']['data']['name'], 
						$requestBody['data']['dataStreamPresetField']['data']['path'], 
						$requestBody['data']['dataStreamPresetField']['data']['versioned'], 
						$requestBody['data']['dataStreamPresetField']['data']['searchable'], 
						$requestBody['data']['dataStreamPresetField']['data']['to_retrieve'], 
						$requestBody['data']['dataStreamPresetField']['data']['created_at'], 
						$requestBody['data']['dataStreamPresetField']['data']['updated_at'], 
						((isset($requestBody['data']['dataStreamPresetField']['data']['dataStreamPreset']) && !is_null($requestBody['data']['dataStreamPresetField']['data']['dataStreamPreset'])) ? (new DataStreamPresetResponse(
							$this->apiClient, 
							new DataStreamPreset(
								$this->apiClient, 
								$requestBody['data']['dataStreamPresetField']['data']['dataStreamPreset']['data']['id'], 
								$requestBody['data']['dataStreamPresetField']['data']['dataStreamPreset']['data']['data_stream_decoder_id'], 
								$requestBody['data']['dataStreamPresetField']['data']['dataStreamPreset']['data']['name'], 
								$requestBody['data']['dataStreamPresetField']['data']['dataStreamPreset']['data']['created_at'], 
								$requestBody['data']['dataStreamPresetField']['data']['dataStreamPreset']['data']['updated_at'], 
								((isset($requestBody['data']['dataStreamPresetField']['data']['dataStreamPreset']['data']['dataStreamDecoder']) && !is_null($requestBody['data']['dataStreamPresetField']['data']['dataStreamPreset']['data']['dataStreamDecoder'])) ? (new DataStreamDecoderResponse(
									$this->apiClient, 
									new DataStreamDecoder(
										$this->apiClient, 
										$requestBody['data']['dataStreamPresetField']['data']['dataStreamPreset']['data']['dataStreamDecoder']['data']['id'], 
										$requestBody['data']['dataStreamPresetField']['data']['dataStreamPreset']['data']['dataStreamDecoder']['data']['name'], 
										$requestBody['data']['dataStreamPresetField']['data']['dataStreamPreset']['data']['dataStreamDecoder']['data']['class_name'], 
										$requestBody['data']['dataStreamPresetField']['data']['dataStreamPreset']['data']['dataStreamDecoder']['data']['file_mime_type'], 
										$requestBody['data']['dataStreamPresetField']['data']['dataStreamPreset']['data']['dataStreamDecoder']['data']['created_at'], 
										$requestBody['data']['dataStreamPresetField']['data']['dataStreamPreset']['data']['dataStreamDecoder']['data']['updated_at']
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
	 * Delete specified search use case preset field
	 * 
	 * Excepted HTTP code : 204
	 * 
	 * @return ErrorResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function delete()
	{
		$routePath = '/api/searchUseCasePresetField/{searchUseCasePresetId},{dataStreamPresetFieldId}';

		$pathReplacements = [
			'{searchUseCasePresetId}' => $this->search_use_case_preset_id,
			'{dataStreamPresetFieldId}' => $this->data_stream_preset_field_id,
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
