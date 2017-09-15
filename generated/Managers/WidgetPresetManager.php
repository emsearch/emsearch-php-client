<?php

namespace emsearch\Api\Managers;

use emsearch\Api\ApiClient;
use emsearch\Api\Exceptions\UnexpectedResponseException;
use emsearch\Api\Resources\WidgetPresetListResponse;
use emsearch\Api\Resources\ErrorResponse;
use emsearch\Api\Resources\WidgetPresetResponse;
use emsearch\Api\Resources\WidgetPreset;
use emsearch\Api\Resources\SearchUseCasePresetResponse;
use emsearch\Api\Resources\SearchUseCasePreset;
use emsearch\Api\Resources\DataStreamPresetResponse;
use emsearch\Api\Resources\DataStreamPreset;
use emsearch\Api\Resources\DataStreamDecoderResponse;
use emsearch\Api\Resources\DataStreamDecoder;
use emsearch\Api\Resources\Meta;
use emsearch\Api\Resources\Pagination;

/**
 * WidgetPreset manager class
 * 
 * @package emsearch\Api\Managers
 */
class WidgetPresetManager 
{
	/**
	 * API client
	 *
	 * @var ApiClient
	 */
	protected $apiClient;

	/**
	 * WidgetPreset manager class constructor
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
	 * Show widget preset list
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $include Include responses : {include1},{include2,{include3}[...]
	 * @param string $search Search words
	 * @param int $page Format: int32. Pagination : Page number
	 * @param int $limit Format: int32. Pagination : Maximum entries per page
	 * @param string $order_by Order by : {field},[asc|desc]
	 * 
	 * @return WidgetPresetListResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function all($include = null, $search = null, $page = null, $limit = null, $order_by = null)
	{
		$routeUrl = '/api/widgetPreset';

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
	
	/**
	 * Create and store new widget preset
	 * 
	 * Excepted HTTP code : 201
	 * 
	 * @param string $search_use_case_preset_id Format: uuid.
	 * @param string $name
	 * 
	 * @return WidgetPresetResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function create($search_use_case_preset_id, $name)
	{
		$routeUrl = '/api/widgetPreset';

		$bodyParameters = [];
		$bodyParameters['search_use_case_preset_id'] = $search_use_case_preset_id;
		$bodyParameters['name'] = $name;

		$requestOptions = [];
		$requestOptions['form_params'] = $bodyParameters;

		$request = $this->apiClient->getHttpClient()->request('post', $routeUrl, $requestOptions);

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
	 * Get specified widget preset
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $widgetPresetId Widget preset UUID
	 * @param string $include Include responses : {include1},{include2,{include3}[...]
	 * 
	 * @return WidgetPresetResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function get($widgetPresetId, $include = null)
	{
		$routePath = '/api/widgetPreset/{widgetPresetId}';

		$pathReplacements = [
			'{widgetPresetId}' => $widgetPresetId,
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
	 * Update a widget preset
	 * 
	 * Excepted HTTP code : 201
	 * 
	 * @param string $widgetPresetId Widget preset UUID
	 * @param string $search_use_case_preset_id Format: uuid.
	 * @param string $name
	 * 
	 * @return WidgetPresetResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function update($widgetPresetId, $search_use_case_preset_id, $name)
	{
		$routePath = '/api/widgetPreset/{widgetPresetId}';

		$pathReplacements = [
			'{widgetPresetId}' => $widgetPresetId,
		];

		$routeUrl = str_replace(array_keys($pathReplacements), array_values($pathReplacements), $routePath);

		$bodyParameters = [];
		$bodyParameters['search_use_case_preset_id'] = $search_use_case_preset_id;
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
	 * @param string $widgetPresetId Widget preset UUID
	 * 
	 * @return ErrorResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function delete($widgetPresetId)
	{
		$routePath = '/api/widgetPreset/{widgetPresetId}';

		$pathReplacements = [
			'{widgetPresetId}' => $widgetPresetId,
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
