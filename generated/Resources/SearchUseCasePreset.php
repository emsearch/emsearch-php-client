<?php

namespace Emsearch\Api\Resources;

use Emsearch\Api\ApiClient;
use Emsearch\Api\Exceptions\UnexpectedResponseException;

/**
 * SearchUseCasePreset resource class
 * 
 * @package Emsearch\Api\Resources
 */
class SearchUseCasePreset 
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
	 * Format: int32.
	 * 
	 * @var int
	 */
	public $search_use_case_preset_fields_count;

	/**
	 * @var DataStreamPresetResponse
	 */
	public $dataStreamPreset;

	/**
	 * SearchUseCasePreset resource class constructor
	 * 
	 * @param ApiClient $apiClient API Client to use for this manager requests
	 * @param string $id Format: uuid.
	 * @param string $data_stream_preset_id Format: uuid.
	 * @param string $name
	 * @param string $created_at Format: date-time.
	 * @param string $updated_at Format: date-time.
	 * @param int $search_use_case_preset_fields_count Format: int32.
	 * @param DataStreamPresetResponse $dataStreamPreset
	 */
	public function __construct(ApiClient $apiClient, $id = null, $data_stream_preset_id = null, $name = null, $created_at = null, $updated_at = null, $search_use_case_preset_fields_count = null, $dataStreamPreset = null)
	{
		$this->apiClient = $apiClient;
		$this->id = $id;
		$this->data_stream_preset_id = $data_stream_preset_id;
		$this->name = $name;
		$this->created_at = $created_at;
		$this->updated_at = $updated_at;
		$this->search_use_case_preset_fields_count = $search_use_case_preset_fields_count;
		$this->dataStreamPreset = $dataStreamPreset;
	}
	/**
	 * Update a search use case preset
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $data_stream_preset_id Format: uuid.
	 * @param string $name
	 * 
	 * @return SearchUseCasePresetResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function update($data_stream_preset_id, $name)
	{
		$routePath = '/api/searchUseCasePreset/{searchUseCasePresetId}';

		$pathReplacements = [
			'{searchUseCasePresetId}' => $this->id,
		];

		$routeUrl = str_replace(array_keys($pathReplacements), array_values($pathReplacements), $routePath);

		$bodyParameters = [];
		$bodyParameters['data_stream_preset_id'] = $data_stream_preset_id;
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

		$response = new SearchUseCasePresetResponse(
			$this->apiClient, 
			new SearchUseCasePreset(
				$this->apiClient, 
				$requestBody['data']['id'], 
				$requestBody['data']['data_stream_preset_id'], 
				$requestBody['data']['name'], 
				$requestBody['data']['created_at'], 
				$requestBody['data']['updated_at'], 
				$requestBody['data']['search_use_case_preset_fields_count'], 
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
	 * Delete specified search use case preset
	 * 
	 * Excepted HTTP code : 204
	 * 
	 * @return ErrorResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function delete()
	{
		$routePath = '/api/searchUseCasePreset/{searchUseCasePresetId}';

		$pathReplacements = [
			'{searchUseCasePresetId}' => $this->id,
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
	
	/**
	 * Search use case preset search use case preset fields list
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $search Search words
	 * @param int $page Format: int32. Pagination : Page number
	 * @param int $limit Format: int32. Pagination : Maximum entries per page
	 * @param string $order_by Order by : {field},[asc|desc]
	 * 
	 * @return SearchUseCasePresetFieldListResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function getPresetFields($search = null, $page = null, $limit = null, $order_by = null)
	{
		$routePath = '/api/searchUseCasePreset/{searchUseCasePresetId}/searchUseCasePresetField';

		$pathReplacements = [
			'{searchUseCasePresetId}' => $this->id,
		];

		$routeUrl = str_replace(array_keys($pathReplacements), array_values($pathReplacements), $routePath);

		$queryParameters = [];

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

		$response = new SearchUseCasePresetFieldListResponse(
			$this->apiClient, 
			array_map(function($data) {
				return new SearchUseCasePresetField(
					$this->apiClient, 
					$data['search_use_case_preset_id'], 
					$data['data_stream_preset_field_id'], 
					$data['name'], 
					$data['searchable'], 
					$data['to_retrieve'], 
					$data['created_at'], 
					$data['updated_at'], 
					((isset($data['searchUseCasePreset']) && !is_null($data['searchUseCasePreset'])) ? (new SearchUseCasePresetResponse(
						$this->apiClient, 
						new SearchUseCasePreset(
							$this->apiClient, 
							$data['searchUseCasePreset']['data']['id'], 
							$data['searchUseCasePreset']['data']['data_stream_preset_id'], 
							$data['searchUseCasePreset']['data']['name'], 
							$data['searchUseCasePreset']['data']['created_at'], 
							$data['searchUseCasePreset']['data']['updated_at'], 
							$data['searchUseCasePreset']['data']['search_use_case_preset_fields_count'], 
							((isset($data['searchUseCasePreset']['data']['dataStreamPreset']) && !is_null($data['searchUseCasePreset']['data']['dataStreamPreset'])) ? (new DataStreamPresetResponse(
								$this->apiClient, 
								new DataStreamPreset(
									$this->apiClient, 
									$data['searchUseCasePreset']['data']['dataStreamPreset']['data']['id'], 
									$data['searchUseCasePreset']['data']['dataStreamPreset']['data']['data_stream_decoder_id'], 
									$data['searchUseCasePreset']['data']['dataStreamPreset']['data']['name'], 
									$data['searchUseCasePreset']['data']['dataStreamPreset']['data']['created_at'], 
									$data['searchUseCasePreset']['data']['dataStreamPreset']['data']['updated_at'], 
									((isset($data['searchUseCasePreset']['data']['dataStreamPreset']['data']['dataStreamDecoder']) && !is_null($data['searchUseCasePreset']['data']['dataStreamPreset']['data']['dataStreamDecoder'])) ? (new DataStreamDecoderResponse(
										$this->apiClient, 
										new DataStreamDecoder(
											$this->apiClient, 
											$data['searchUseCasePreset']['data']['dataStreamPreset']['data']['dataStreamDecoder']['data']['id'], 
											$data['searchUseCasePreset']['data']['dataStreamPreset']['data']['dataStreamDecoder']['data']['name'], 
											$data['searchUseCasePreset']['data']['dataStreamPreset']['data']['dataStreamDecoder']['data']['class_name'], 
											$data['searchUseCasePreset']['data']['dataStreamPreset']['data']['dataStreamDecoder']['data']['file_mime_type'], 
											$data['searchUseCasePreset']['data']['dataStreamPreset']['data']['dataStreamDecoder']['data']['created_at'], 
											$data['searchUseCasePreset']['data']['dataStreamPreset']['data']['dataStreamDecoder']['data']['updated_at']
										)
									)) : null)
								)
							)) : null)
						)
					)) : null), 
					((isset($data['dataStreamPresetField']) && !is_null($data['dataStreamPresetField'])) ? (new DataStreamPresetFieldResponse(
						$this->apiClient, 
						new DataStreamPresetField(
							$this->apiClient, 
							$data['dataStreamPresetField']['data']['id'], 
							$data['dataStreamPresetField']['data']['data_stream_preset_id'], 
							$data['dataStreamPresetField']['data']['name'], 
							$data['dataStreamPresetField']['data']['path'], 
							$data['dataStreamPresetField']['data']['versioned'], 
							$data['dataStreamPresetField']['data']['searchable'], 
							$data['dataStreamPresetField']['data']['to_retrieve'], 
							$data['dataStreamPresetField']['data']['created_at'], 
							$data['dataStreamPresetField']['data']['updated_at'], 
							((isset($data['dataStreamPresetField']['data']['dataStreamPreset']) && !is_null($data['dataStreamPresetField']['data']['dataStreamPreset'])) ? (new DataStreamPresetResponse(
								$this->apiClient, 
								new DataStreamPreset(
									$this->apiClient, 
									$data['dataStreamPresetField']['data']['dataStreamPreset']['data']['id'], 
									$data['dataStreamPresetField']['data']['dataStreamPreset']['data']['data_stream_decoder_id'], 
									$data['dataStreamPresetField']['data']['dataStreamPreset']['data']['name'], 
									$data['dataStreamPresetField']['data']['dataStreamPreset']['data']['created_at'], 
									$data['dataStreamPresetField']['data']['dataStreamPreset']['data']['updated_at'], 
									((isset($data['dataStreamPresetField']['data']['dataStreamPreset']['data']['dataStreamDecoder']) && !is_null($data['dataStreamPresetField']['data']['dataStreamPreset']['data']['dataStreamDecoder'])) ? (new DataStreamDecoderResponse(
										$this->apiClient, 
										new DataStreamDecoder(
											$this->apiClient, 
											$data['dataStreamPresetField']['data']['dataStreamPreset']['data']['dataStreamDecoder']['data']['id'], 
											$data['dataStreamPresetField']['data']['dataStreamPreset']['data']['dataStreamDecoder']['data']['name'], 
											$data['dataStreamPresetField']['data']['dataStreamPreset']['data']['dataStreamDecoder']['data']['class_name'], 
											$data['dataStreamPresetField']['data']['dataStreamPreset']['data']['dataStreamDecoder']['data']['file_mime_type'], 
											$data['dataStreamPresetField']['data']['dataStreamPreset']['data']['dataStreamDecoder']['data']['created_at'], 
											$data['dataStreamPresetField']['data']['dataStreamPreset']['data']['dataStreamDecoder']['data']['updated_at']
										)
									)) : null)
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
	
	/**
	 * Search use case preset widget presets list
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $search Search words
	 * @param int $page Format: int32. Pagination : Page number
	 * @param int $limit Format: int32. Pagination : Maximum entries per page
	 * @param string $order_by Order by : {field},[asc|desc]
	 * 
	 * @return WidgetPresetListResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function getWidgetPresets($search = null, $page = null, $limit = null, $order_by = null)
	{
		$routePath = '/api/searchUseCasePreset/{searchUseCasePresetId}/widgetPreset';

		$pathReplacements = [
			'{searchUseCasePresetId}' => $this->id,
		];

		$routeUrl = str_replace(array_keys($pathReplacements), array_values($pathReplacements), $routePath);

		$queryParameters = [];

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

		$response = new WidgetPresetListResponse(
			$this->apiClient, 
			array_map(function($data) {
				return new WidgetPreset(
					$this->apiClient, 
					$data['id'], 
					$data['search_use_case_preset_id'], 
					$data['name'], 
					$data['created_at'], 
					$data['updated_at'], 
					((isset($data['searchUseCasePreset']) && !is_null($data['searchUseCasePreset'])) ? (new SearchUseCasePresetResponse(
						$this->apiClient, 
						new SearchUseCasePreset(
							$this->apiClient, 
							$data['searchUseCasePreset']['data']['id'], 
							$data['searchUseCasePreset']['data']['data_stream_preset_id'], 
							$data['searchUseCasePreset']['data']['name'], 
							$data['searchUseCasePreset']['data']['created_at'], 
							$data['searchUseCasePreset']['data']['updated_at'], 
							$data['searchUseCasePreset']['data']['search_use_case_preset_fields_count'], 
							((isset($data['searchUseCasePreset']['data']['dataStreamPreset']) && !is_null($data['searchUseCasePreset']['data']['dataStreamPreset'])) ? (new DataStreamPresetResponse(
								$this->apiClient, 
								new DataStreamPreset(
									$this->apiClient, 
									$data['searchUseCasePreset']['data']['dataStreamPreset']['data']['id'], 
									$data['searchUseCasePreset']['data']['dataStreamPreset']['data']['data_stream_decoder_id'], 
									$data['searchUseCasePreset']['data']['dataStreamPreset']['data']['name'], 
									$data['searchUseCasePreset']['data']['dataStreamPreset']['data']['created_at'], 
									$data['searchUseCasePreset']['data']['dataStreamPreset']['data']['updated_at'], 
									((isset($data['searchUseCasePreset']['data']['dataStreamPreset']['data']['dataStreamDecoder']) && !is_null($data['searchUseCasePreset']['data']['dataStreamPreset']['data']['dataStreamDecoder'])) ? (new DataStreamDecoderResponse(
										$this->apiClient, 
										new DataStreamDecoder(
											$this->apiClient, 
											$data['searchUseCasePreset']['data']['dataStreamPreset']['data']['dataStreamDecoder']['data']['id'], 
											$data['searchUseCasePreset']['data']['dataStreamPreset']['data']['dataStreamDecoder']['data']['name'], 
											$data['searchUseCasePreset']['data']['dataStreamPreset']['data']['dataStreamDecoder']['data']['class_name'], 
											$data['searchUseCasePreset']['data']['dataStreamPreset']['data']['dataStreamDecoder']['data']['file_mime_type'], 
											$data['searchUseCasePreset']['data']['dataStreamPreset']['data']['dataStreamDecoder']['data']['created_at'], 
											$data['searchUseCasePreset']['data']['dataStreamPreset']['data']['dataStreamDecoder']['data']['updated_at']
										)
									)) : null)
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
