<?php

namespace emsearch\Api\Resources;

use emsearch\Api\ApiClient;
use emsearch\Api\Exceptions\UnexpectedResponseException;

/**
 * DataStreamPresetField resource class
 * 
 * @package emsearch\Api\Resources
 */
class DataStreamPresetField 
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
	public $data_stream_preset_id;

	/**
	 * @var string
	 */
	public $name;

	/**
	 * @var string
	 */
	public $path;

	/**
	 * @var boolean
	 */
	public $versioned;

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
	 * @var DataStreamPresetResponse
	 */
	public $dataStreamPreset;

	/**
	 * DataStreamPresetField resource class constructor
	 * 
	 * @param ApiClient $apiClient API Client to use for this manager requests
	 * @param string $id Format: uuid.
	 * @param string $data_stream_preset_id Format: uuid.
	 * @param string $name
	 * @param string $path
	 * @param boolean $versioned
	 * @param boolean $searchable
	 * @param boolean $to_retrieve
	 * @param string $created_at Format: date-time.
	 * @param string $updated_at Format: date-time.
	 * @param DataStreamPresetResponse $dataStreamPreset
	 */
	public function __construct(ApiClient $apiClient, $id = null, $data_stream_preset_id = null, $name = null, $path = null, $versioned = null, $searchable = null, $to_retrieve = null, $created_at = null, $updated_at = null, $dataStreamPreset = null)
	{
		$this->apiClient = $apiClient;
		$this->id = $id;
		$this->data_stream_preset_id = $data_stream_preset_id;
		$this->name = $name;
		$this->path = $path;
		$this->versioned = $versioned;
		$this->searchable = $searchable;
		$this->to_retrieve = $to_retrieve;
		$this->created_at = $created_at;
		$this->updated_at = $updated_at;
		$this->dataStreamPreset = $dataStreamPreset;
	}
	/**
	 * Update a data stream preset field
	 * 
	 * Excepted HTTP code : 201
	 * 
	 * @param string $data_stream_preset_id Format: uuid.
	 * @param string $name
	 * @param string $path
	 * @param boolean $versioned
	 * @param boolean $searchable
	 * @param boolean $to_retrieve
	 * 
	 * @return DataStreamPresetFieldResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function update($data_stream_preset_id, $name, $path, $versioned, $searchable, $to_retrieve)
	{
		$routePath = '/api/dataStreamPresetField/{dataStreamPresetFieldId}';

		$pathReplacements = [
			'{dataStreamPresetFieldId}' => $this->id,
		];

		$routeUrl = str_replace(array_keys($pathReplacements), array_values($pathReplacements), $routePath);

		$bodyParameters = [];
		$bodyParameters['data_stream_preset_id'] = $data_stream_preset_id;
		$bodyParameters['name'] = $name;
		$bodyParameters['path'] = $path;
		$bodyParameters['versioned'] = $versioned;
		$bodyParameters['searchable'] = $searchable;
		$bodyParameters['to_retrieve'] = $to_retrieve;

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

		$response = new DataStreamPresetFieldResponse(
			$this->apiClient, 
			new DataStreamPresetField(
				$this->apiClient, 
				$requestBody['data']['id'], 
				$requestBody['data']['data_stream_preset_id'], 
				$requestBody['data']['name'], 
				$requestBody['data']['path'], 
				$requestBody['data']['versioned'], 
				$requestBody['data']['searchable'], 
				$requestBody['data']['to_retrieve'], 
				$requestBody['data']['created_at'], 
				$requestBody['data']['updated_at'], 
				((isset($requestBody['data']['dataStreamPreset']) && !is_null($requestBody['data']['dataStreamPreset'])) ? (new DataStreamPresetResponse(
					$this->apiClient, 
					new DataStreamPreset(
						$this->apiClient, 
						$requestBody['data']['dataStreamPreset']['data']['id'], 
						$requestBody['data']['dataStreamPreset']['data']['data_stream_decoder_id'], 
						$requestBody['data']['dataStreamPreset']['data']['name'], 
						$requestBody['data']['dataStreamPreset']['data']['created_at'], 
						$requestBody['data']['dataStreamPreset']['data']['updated_at'], 
						((isset($requestBody['data']['dataStreamPreset']['data']['dataStreamDecoder']) && !is_null($requestBody['data']['dataStreamPreset']['data']['dataStreamDecoder'])) ? (new DataStreamDecoderResponse(
							$this->apiClient, 
							new DataStreamDecoder(
								$this->apiClient, 
								$requestBody['data']['dataStreamPreset']['data']['dataStreamDecoder']['data']['id'], 
								$requestBody['data']['dataStreamPreset']['data']['dataStreamDecoder']['data']['name'], 
								$requestBody['data']['dataStreamPreset']['data']['dataStreamDecoder']['data']['class_name'], 
								$requestBody['data']['dataStreamPreset']['data']['dataStreamDecoder']['data']['file_mime_type'], 
								$requestBody['data']['dataStreamPreset']['data']['dataStreamDecoder']['data']['created_at'], 
								$requestBody['data']['dataStreamPreset']['data']['dataStreamDecoder']['data']['updated_at']
							)
						)) : null)
					)
				)) : null)
			)
		);

		return $response;
	}
	
	/**
	 * Delete specified data stream preset field
	 * 
	 * Excepted HTTP code : 204
	 * 
	 * @return ErrorResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function delete()
	{
		$routePath = '/api/dataStreamPresetField/{dataStreamPresetFieldId}';

		$pathReplacements = [
			'{dataStreamPresetFieldId}' => $this->id,
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
