<?php

namespace emsearch\Api\Resources;

use emsearch\Api\ApiClient;
use emsearch\Api\Exceptions\UnexpectedResponseException;

/**
 * DataStreamPreset resource class
 * 
 * @package emsearch\Api\Resources
 */
class DataStreamPreset 
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
	public $data_stream_decoder_id;

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
	 * @var DataStreamDecoderResponse
	 */
	public $dataStreamDecoder;

	/**
	 * DataStreamPreset resource class constructor
	 * 
	 * @param ApiClient $apiClient API Client to use for this manager requests
	 * @param string $id Format: uuid.
	 * @param string $data_stream_decoder_id Format: uuid.
	 * @param string $name
	 * @param string $created_at Format: date-time.
	 * @param string $updated_at Format: date-time.
	 * @param DataStreamDecoderResponse $dataStreamDecoder
	 */
	public function __construct(ApiClient $apiClient, $id = null, $data_stream_decoder_id = null, $name = null, $created_at = null, $updated_at = null, $dataStreamDecoder = null)
	{
		$this->apiClient = $apiClient;
		$this->id = $id;
		$this->data_stream_decoder_id = $data_stream_decoder_id;
		$this->name = $name;
		$this->created_at = $created_at;
		$this->updated_at = $updated_at;
		$this->dataStreamDecoder = $dataStreamDecoder;
	}
	/**
	 * Update a data stream preset
	 * 
	 * Excepted HTTP code : 201
	 * 
	 * @param string $data_stream_decoder_id Format: uuid.
	 * @param string $name
	 * 
	 * @return DataStreamPresetResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function update($data_stream_decoder_id, $name)
	{
		$routePath = '/api/dataStreamPreset/{dataStreamPresetId}';

		$pathReplacements = [
			'{dataStreamPresetId}' => $this->id,
		];

		$routeUrl = str_replace(array_keys($pathReplacements), array_values($pathReplacements), $routePath);

		$bodyParameters = [];
		$bodyParameters['data_stream_decoder_id'] = $data_stream_decoder_id;
		$bodyParameters['name'] = $name;

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

		$response = new DataStreamPresetResponse(
			$this->apiClient, 
			new DataStreamPreset(
				$this->apiClient, 
				$requestBody['data']['id'], 
				$requestBody['data']['data_stream_decoder_id'], 
				$requestBody['data']['name'], 
				$requestBody['data']['created_at'], 
				$requestBody['data']['updated_at'], 
				((isset($requestBody['data']['dataStreamDecoder']) && !is_null($requestBody['data']['dataStreamDecoder'])) ? (new DataStreamDecoderResponse(
					$this->apiClient, 
					new DataStreamDecoder(
						$this->apiClient, 
						$requestBody['data']['dataStreamDecoder']['data']['id'], 
						$requestBody['data']['dataStreamDecoder']['data']['name'], 
						$requestBody['data']['dataStreamDecoder']['data']['class_name'], 
						$requestBody['data']['dataStreamDecoder']['data']['file_mime_type'], 
						$requestBody['data']['dataStreamDecoder']['data']['created_at'], 
						$requestBody['data']['dataStreamDecoder']['data']['updated_at']
					)
				)) : null)
			)
		);

		return $response;
	}
	
	/**
	 * Delete specified data stream preset
	 * 
	 * Excepted HTTP code : 204
	 * 
	 * @return ErrorResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function delete()
	{
		$routePath = '/api/dataStreamPreset/{dataStreamPresetId}';

		$pathReplacements = [
			'{dataStreamPresetId}' => $this->id,
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
	
	/**
	 * Data stream preset data stream preset field list
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $include Include responses : {include1},{include2,{include3}[...]
	 * @param string $search Search words
	 * @param int $page Format: int32. Pagination : Page number
	 * @param int $limit Format: int32. Pagination : Maximum entries per page
	 * @param string $order_by Order by : {field},[asc|desc]
	 * 
	 * @return DataStreamPresetFieldListResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function getFields($include = null, $search = null, $page = null, $limit = null, $order_by = null)
	{
		$routePath = '/api/dataStreamPreset/{dataStreamPresetId}/dataStreamPresetField';

		$pathReplacements = [
			'{dataStreamPresetId}' => $this->id,
		];

		$routeUrl = str_replace(array_keys($pathReplacements), array_values($pathReplacements), $routePath);

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
				$requestBody['message'], 
				(isset($requestBody['errors']) ? $requestBody['errors'] : null), 
				(isset($requestBody['status_code']) ? $requestBody['status_code'] : null), 
				(isset($requestBody['debug']) ? $requestBody['debug'] : null)
			);

			throw new UnexpectedResponseException($request->getStatusCode(), 200, $request, $apiExceptionResponse);
		}

		$requestBody = json_decode((string) $request->getBody(), true);

		$response = new DataStreamPresetFieldListResponse(
			$this->apiClient, 
			array_map(function($data) {
				return new DataStreamPresetField(
					$this->apiClient, 
					$data['id'], 
					$data['data_stream_preset_id'], 
					$data['name'], 
					$data['path'], 
					$data['versioned'], 
					$data['searchable'], 
					$data['to_retrieve'], 
					$data['created_at'], 
					$data['updated_at'], 
					((isset($data['dataStreamPreset']) && !is_null($data['dataStreamPreset'])) ? (new DataStreamPresetResponse(
						$this->apiClient, 
						new DataStreamPreset(
							$this->apiClient, 
							$data['dataStreamPreset']['data']['id'], 
							$data['dataStreamPreset']['data']['data_stream_decoder_id'], 
							$data['dataStreamPreset']['data']['name'], 
							$data['dataStreamPreset']['data']['created_at'], 
							$data['dataStreamPreset']['data']['updated_at'], 
							((isset($data['dataStreamPreset']['data']['dataStreamDecoder']) && !is_null($data['dataStreamPreset']['data']['dataStreamDecoder'])) ? (new DataStreamDecoderResponse(
								$this->apiClient, 
								new DataStreamDecoder(
									$this->apiClient, 
									$data['dataStreamPreset']['data']['dataStreamDecoder']['data']['id'], 
									$data['dataStreamPreset']['data']['dataStreamDecoder']['data']['name'], 
									$data['dataStreamPreset']['data']['dataStreamDecoder']['data']['class_name'], 
									$data['dataStreamPreset']['data']['dataStreamDecoder']['data']['file_mime_type'], 
									$data['dataStreamPreset']['data']['dataStreamDecoder']['data']['created_at'], 
									$data['dataStreamPreset']['data']['dataStreamDecoder']['data']['updated_at']
								)
							)) : null)
						)
					)) : null)
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
}
