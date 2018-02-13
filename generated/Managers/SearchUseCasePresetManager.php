<?php

namespace Emsearch\Api\Managers;

use Emsearch\Api\ApiClient;
use Emsearch\Api\Exceptions\UnexpectedResponseException;
use Emsearch\Api\Resources\SearchUseCasePresetListResponse;
use Emsearch\Api\Resources\ErrorResponse;
use Emsearch\Api\Resources\SearchUseCasePresetResponse;
use Emsearch\Api\Resources\SearchUseCasePreset;
use Emsearch\Api\Resources\DataStreamPresetResponse;
use Emsearch\Api\Resources\DataStreamPreset;
use Emsearch\Api\Resources\DataStreamDecoderResponse;
use Emsearch\Api\Resources\DataStreamDecoder;
use Emsearch\Api\Resources\Meta;
use Emsearch\Api\Resources\Pagination;

/**
 * SearchUseCasePreset manager class
 * 
 * @package Emsearch\Api\Managers
 */
class SearchUseCasePresetManager 
{
	/**
	 * API client
	 *
	 * @var ApiClient
	 */
	protected $apiClient;

	/**
	 * SearchUseCasePreset manager class constructor
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
	 * Show search use case preset list
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $include Include responses : {include1},{include2,{include3}[...]
	 * @param string $search Search words
	 * @param int $page Format: int32. Pagination : Page number
	 * @param int $limit Format: int32. Pagination : Maximum entries per page
	 * @param string $order_by Order by : {field},[asc|desc]
	 * 
	 * @return SearchUseCasePresetListResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function all($include = null, $search = null, $page = null, $limit = null, $order_by = null)
	{
		$routeUrl = '/api/searchUseCasePreset';

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
				(isset($requestBody['app_error_code']) ? $requestBody['app_error_code'] : null), 
				$requestBody['message'], 
				(isset($requestBody['errors']) ? $requestBody['errors'] : null), 
				(isset($requestBody['status_code']) ? $requestBody['status_code'] : null), 
				(isset($requestBody['debug']) ? $requestBody['debug'] : null)
			);

			throw new UnexpectedResponseException($request->getStatusCode(), 200, $request, $apiExceptionResponse);
		}

		$requestBody = json_decode((string) $request->getBody(), true);

		$response = new SearchUseCasePresetListResponse(
			$this->apiClient, 
			array_map(function($data) {
				return new SearchUseCasePreset(
					$this->apiClient, 
					$data['id'], 
					$data['data_stream_preset_id'], 
					$data['name'], 
					$data['created_at'], 
					$data['updated_at'], 
					$data['search_use_case_preset_fields_count'], 
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
	
	/**
	 * Create and store new search use case preset
	 * 
	 * Excepted HTTP code : 201
	 * 
	 * @param string $data_stream_preset_id Format: uuid.
	 * @param string $name
	 * 
	 * @return SearchUseCasePresetResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function create($data_stream_preset_id, $name)
	{
		$routeUrl = '/api/searchUseCasePreset';

		$bodyParameters = [];
		$bodyParameters['data_stream_preset_id'] = $data_stream_preset_id;
		$bodyParameters['name'] = $name;

		$requestOptions = [];
		$requestOptions['form_params'] = $bodyParameters;

		$request = $this->apiClient->getHttpClient()->request('post', $routeUrl, $requestOptions);

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
	 * Get specified search use case preset
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $searchUseCasePresetId Search use case preset UUID
	 * @param string $include Include responses : {include1},{include2,{include3}[...]
	 * 
	 * @return SearchUseCasePresetResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function get($searchUseCasePresetId, $include = null)
	{
		$routePath = '/api/searchUseCasePreset/{searchUseCasePresetId}';

		$pathReplacements = [
			'{searchUseCasePresetId}' => $searchUseCasePresetId,
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
	 * Update a search use case preset
	 * 
	 * Excepted HTTP code : 201
	 * 
	 * @param string $searchUseCasePresetId Search use case preset UUID
	 * @param string $data_stream_preset_id Format: uuid.
	 * @param string $name
	 * 
	 * @return SearchUseCasePresetResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function update($searchUseCasePresetId, $data_stream_preset_id, $name)
	{
		$routePath = '/api/searchUseCasePreset/{searchUseCasePresetId}';

		$pathReplacements = [
			'{searchUseCasePresetId}' => $searchUseCasePresetId,
		];

		$routeUrl = str_replace(array_keys($pathReplacements), array_values($pathReplacements), $routePath);

		$bodyParameters = [];
		$bodyParameters['data_stream_preset_id'] = $data_stream_preset_id;
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
	 * @param string $searchUseCasePresetId Search use case preset UUID
	 * 
	 * @return ErrorResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function delete($searchUseCasePresetId)
	{
		$routePath = '/api/searchUseCasePreset/{searchUseCasePresetId}';

		$pathReplacements = [
			'{searchUseCasePresetId}' => $searchUseCasePresetId,
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
