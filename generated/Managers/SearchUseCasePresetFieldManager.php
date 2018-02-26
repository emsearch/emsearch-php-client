<?php

namespace Emsearch\Api\Managers;

use Emsearch\Api\ApiClient;
use Emsearch\Api\Exceptions\UnexpectedResponseException;
use Emsearch\Api\Resources\SearchUseCasePresetFieldListResponse;
use Emsearch\Api\Resources\ErrorResponse;
use Emsearch\Api\Resources\SearchUseCasePresetFieldResponse;
use Emsearch\Api\Resources\SearchUseCasePresetField;
use Emsearch\Api\Resources\SearchUseCasePresetResponse;
use Emsearch\Api\Resources\SearchUseCasePreset;
use Emsearch\Api\Resources\DataStreamPresetResponse;
use Emsearch\Api\Resources\DataStreamPreset;
use Emsearch\Api\Resources\DataStreamDecoderResponse;
use Emsearch\Api\Resources\DataStreamDecoder;
use Emsearch\Api\Resources\DataStreamPresetFieldResponse;
use Emsearch\Api\Resources\DataStreamPresetField;
use Emsearch\Api\Resources\Meta;
use Emsearch\Api\Resources\Pagination;

/**
 * SearchUseCasePresetField manager class
 * 
 * @package Emsearch\Api\Managers
 */
class SearchUseCasePresetFieldManager 
{
	/**
	 * API client
	 *
	 * @var ApiClient
	 */
	protected $apiClient;

	/**
	 * SearchUseCasePresetField manager class constructor
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
	 * Show search use case preset field list
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $include Include responses : {include1},{include2,{include3}[...]
	 * @param string $search Search words
	 * @param int $page Format: int32. Pagination : Page number
	 * @param int $limit Format: int32. Pagination : Maximum entries per page
	 * @param string $order_by Order by : {field},[asc|desc]
	 * 
	 * @return SearchUseCasePresetFieldListResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function all($include = null, $search = null, $page = null, $limit = null, $order_by = null)
	{
		$routeUrl = '/api/searchUseCasePresetField';

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
	 * Create and store new search use case preset field
	 * 
	 * Excepted HTTP code : 201
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
	public function create($search_use_case_preset_id, $data_stream_preset_field_id, $name, $searchable, $to_retrieve)
	{
		$routeUrl = '/api/searchUseCasePresetField';

		$bodyParameters = [];
		$bodyParameters['search_use_case_preset_id'] = $search_use_case_preset_id;
		$bodyParameters['data_stream_preset_field_id'] = $data_stream_preset_field_id;
		$bodyParameters['name'] = $name;
		$bodyParameters['searchable'] = $searchable;
		$bodyParameters['to_retrieve'] = $to_retrieve;

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
	 * Get specified search use case preset field
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $searchUseCasePresetId Search use case preset UUID
	 * @param string $dataStreamPresetFieldId Data stream preset field UUID
	 * @param string $include Include responses : {include1},{include2,{include3}[...]
	 * 
	 * @return SearchUseCasePresetFieldResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function get($searchUseCasePresetId, $dataStreamPresetFieldId, $include = null)
	{
		$routePath = '/api/searchUseCasePresetField/{searchUseCasePresetId},{dataStreamPresetFieldId}';

		$pathReplacements = [
			'{searchUseCasePresetId}' => $searchUseCasePresetId,
			'{dataStreamPresetFieldId}' => $dataStreamPresetFieldId,
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
	 * Update a specified search use case preset field
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $searchUseCasePresetId Search use case preset UUID
	 * @param string $dataStreamPresetFieldId Data stream preset field UUID
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
	public function update($searchUseCasePresetId, $dataStreamPresetFieldId, $search_use_case_preset_id, $data_stream_preset_field_id, $name, $searchable, $to_retrieve)
	{
		$routePath = '/api/searchUseCasePresetField/{searchUseCasePresetId},{dataStreamPresetFieldId}';

		$pathReplacements = [
			'{searchUseCasePresetId}' => $searchUseCasePresetId,
			'{dataStreamPresetFieldId}' => $dataStreamPresetFieldId,
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
	 * @param string $searchUseCasePresetId Search use case preset UUID
	 * @param string $dataStreamPresetFieldId Data stream preset field UUID
	 * 
	 * @return ErrorResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function delete($searchUseCasePresetId, $dataStreamPresetFieldId)
	{
		$routePath = '/api/searchUseCasePresetField/{searchUseCasePresetId},{dataStreamPresetFieldId}';

		$pathReplacements = [
			'{searchUseCasePresetId}' => $searchUseCasePresetId,
			'{dataStreamPresetFieldId}' => $dataStreamPresetFieldId,
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
