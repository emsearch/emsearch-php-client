<?php

namespace emsearch\Api\Managers;

use emsearch\Api\ApiClient;
use emsearch\Api\Exceptions\UnexpectedResponseException;
use emsearch\Api\Resources\WidgetListResponse;
use emsearch\Api\Resources\ErrorResponse;
use emsearch\Api\Resources\WidgetResponse;
use emsearch\Api\Resources\Widget;
use emsearch\Api\Resources\SearchUseCasePresetResponse;
use emsearch\Api\Resources\SearchUseCasePreset;
use emsearch\Api\Resources\DataStreamPresetResponse;
use emsearch\Api\Resources\DataStreamPreset;
use emsearch\Api\Resources\DataStreamDecoderResponse;
use emsearch\Api\Resources\DataStreamDecoder;
use emsearch\Api\Resources\Meta;
use emsearch\Api\Resources\Pagination;

/**
 * Widget manager class
 * 
 * @package emsearch\Api\Managers
 */
class WidgetManager 
{
	/**
	 * API client
	 *
	 * @var ApiClient
	 */
	protected $apiClient;

	/**
	 * Widget manager class constructor
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
	 * Show widget list
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $include Include responses : {include1},{include2,{include3}[...]
	 * @param string $search Search words
	 * @param int $page Format: int32. Pagination : Page number
	 * @param int $limit Format: int32. Pagination : Maximum entries per page
	 * @param string $order_by Order by : {field},[asc|desc]
	 * 
	 * @return WidgetListResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function all($include = null, $search = null, $page = null, $limit = null, $order_by = null)
	{
		$routeUrl = '/api/widget';

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

		$response = new WidgetListResponse(
			$this->apiClient, 
			array_map(function($data) {
				return new Widget(
					$this->apiClient, 
					$data['id'], 
					$data['search_use_case_id'], 
					$data['name'], 
					$data['created_at'], 
					$data['updated_at'], 
					((isset($data['searchUseCase']) && !is_null($data['searchUseCase'])) ? (new SearchUseCasePresetResponse(
						$this->apiClient, 
						new SearchUseCasePreset(
							$this->apiClient, 
							$data['searchUseCase']['data']['id'], 
							$data['searchUseCase']['data']['data_stream_preset_id'], 
							$data['searchUseCase']['data']['name'], 
							$data['searchUseCase']['data']['created_at'], 
							$data['searchUseCase']['data']['updated_at'], 
							$data['searchUseCase']['data']['search_use_case_preset_fields_count'], 
							((isset($data['searchUseCase']['data']['dataStreamPreset']) && !is_null($data['searchUseCase']['data']['dataStreamPreset'])) ? (new DataStreamPresetResponse(
								$this->apiClient, 
								new DataStreamPreset(
									$this->apiClient, 
									$data['searchUseCase']['data']['dataStreamPreset']['data']['id'], 
									$data['searchUseCase']['data']['dataStreamPreset']['data']['data_stream_decoder_id'], 
									$data['searchUseCase']['data']['dataStreamPreset']['data']['name'], 
									$data['searchUseCase']['data']['dataStreamPreset']['data']['created_at'], 
									$data['searchUseCase']['data']['dataStreamPreset']['data']['updated_at'], 
									((isset($data['searchUseCase']['data']['dataStreamPreset']['data']['dataStreamDecoder']) && !is_null($data['searchUseCase']['data']['dataStreamPreset']['data']['dataStreamDecoder'])) ? (new DataStreamDecoderResponse(
										$this->apiClient, 
										new DataStreamDecoder(
											$this->apiClient, 
											$data['searchUseCase']['data']['dataStreamPreset']['data']['dataStreamDecoder']['data']['id'], 
											$data['searchUseCase']['data']['dataStreamPreset']['data']['dataStreamDecoder']['data']['name'], 
											$data['searchUseCase']['data']['dataStreamPreset']['data']['dataStreamDecoder']['data']['class_name'], 
											$data['searchUseCase']['data']['dataStreamPreset']['data']['dataStreamDecoder']['data']['file_mime_type'], 
											$data['searchUseCase']['data']['dataStreamPreset']['data']['dataStreamDecoder']['data']['created_at'], 
											$data['searchUseCase']['data']['dataStreamPreset']['data']['dataStreamDecoder']['data']['updated_at']
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
	 * Create and store new widget
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
	public function create($search_use_case_id, $name)
	{
		$routeUrl = '/api/widget';

		$bodyParameters = [];
		$bodyParameters['search_use_case_id'] = $search_use_case_id;
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
	 * Get specified widget
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $widgetId Widget UUID
	 * @param string $include Include responses : {include1},{include2,{include3}[...]
	 * 
	 * @return WidgetResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function get($widgetId, $include = null)
	{
		$routePath = '/api/widget/{widgetId}';

		$pathReplacements = [
			'{widgetId}' => $widgetId,
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
	 * Update a widget
	 * 
	 * Excepted HTTP code : 201
	 * 
	 * @param string $widgetId Widget UUID
	 * @param string $search_use_case_id Format: uuid.
	 * @param string $name
	 * 
	 * @return WidgetResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function update($widgetId, $search_use_case_id, $name)
	{
		$routePath = '/api/widget/{widgetId}';

		$pathReplacements = [
			'{widgetId}' => $widgetId,
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
	 * @param string $widgetId Widget UUID
	 * 
	 * @return ErrorResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function delete($widgetId)
	{
		$routePath = '/api/widget/{widgetId}';

		$pathReplacements = [
			'{widgetId}' => $widgetId,
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
