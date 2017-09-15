<?php

namespace emsearch\Api\Managers;

use emsearch\Api\ApiClient;
use emsearch\Api\Exceptions\UnexpectedResponseException;
use emsearch\Api\Resources\SyncItemListResponse;
use emsearch\Api\Resources\ErrorResponse;
use emsearch\Api\Resources\SyncItemResponse;
use emsearch\Api\Resources\SyncItem;
use emsearch\Api\Resources\SearchUseCasePresetResponse;
use emsearch\Api\Resources\SearchUseCasePreset;
use emsearch\Api\Resources\DataStreamPresetResponse;
use emsearch\Api\Resources\DataStreamPreset;
use emsearch\Api\Resources\DataStreamDecoderResponse;
use emsearch\Api\Resources\DataStreamDecoder;
use emsearch\Api\Resources\Meta;
use emsearch\Api\Resources\Pagination;

/**
 * SyncItem manager class
 * 
 * @package emsearch\Api\Managers
 */
class SyncItemManager 
{
	/**
	 * API client
	 *
	 * @var ApiClient
	 */
	protected $apiClient;

	/**
	 * SyncItem manager class constructor
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
	 * Sync item list
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $search Search words
	 * @param int $page Format: int32. Pagination : Page number
	 * @param int $limit Format: int32. Pagination : Maximum entries per page
	 * @param string $order_by Order by : {field},[asc|desc]
	 * 
	 * @return SyncItemListResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function all($search = null, $page = null, $limit = null, $order_by = null)
	{
		$routeUrl = '/api/syncItem';

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
				$requestBody['message'], 
				(isset($requestBody['errors']) ? $requestBody['errors'] : null), 
				(isset($requestBody['status_code']) ? $requestBody['status_code'] : null), 
				(isset($requestBody['debug']) ? $requestBody['debug'] : null)
			);

			throw new UnexpectedResponseException($request->getStatusCode(), 200, $request, $apiExceptionResponse);
		}

		$requestBody = json_decode((string) $request->getBody(), true);

		$response = new SyncItemListResponse(
			$this->apiClient, 
			array_map(function($data) {
				return new SyncItem(
					$this->apiClient, 
					(isset($data['id']) ? $data['id'] : null), 
					$data['project_id'], 
					$data['item_signature'], 
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
	 * Create and store new sync item
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
	public function create($item_id, $project_id, $item_signature = null)
	{
		$routeUrl = '/api/syncItem';

		$bodyParameters = [];
		$bodyParameters['item_id'] = $item_id;
		$bodyParameters['project_id'] = $project_id;

		if (!is_null($item_signature)) {
			$bodyParameters['item_signature'] = $item_signature;
		}

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
	 * Get specified sync item
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $syncItemId Sync Item ID
	 * @param string $projectId Project UUID
	 * 
	 * @return SyncItemResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function get($syncItemId, $projectId)
	{
		$routePath = '/api/syncItem/{syncItemId},{projectId}';

		$pathReplacements = [
			'{syncItemId}' => $syncItemId,
			'{projectId}' => $projectId,
		];

		$routeUrl = str_replace(array_keys($pathReplacements), array_values($pathReplacements), $routePath);

		$requestOptions = [];

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
	 * Update a specified sync item
	 * 
	 * Excepted HTTP code : 201
	 * 
	 * @param string $syncItemId Sync Item ID
	 * @param string $projectId Project UUID
	 * @param string $item_id
	 * @param string $project_id Format: uuid.
	 * @param string $item_signature
	 * 
	 * @return SyncItemResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function update($syncItemId, $projectId, $item_id, $project_id, $item_signature = null)
	{
		$routePath = '/api/syncItem/{syncItemId},{projectId}';

		$pathReplacements = [
			'{syncItemId}' => $syncItemId,
			'{projectId}' => $projectId,
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
	 * @param string $syncItemId Sync Item ID
	 * @param string $projectId Project UUID
	 * 
	 * @return ErrorResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function delete($syncItemId, $projectId)
	{
		$routePath = '/api/syncItem/{syncItemId},{projectId}';

		$pathReplacements = [
			'{syncItemId}' => $syncItemId,
			'{projectId}' => $projectId,
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
