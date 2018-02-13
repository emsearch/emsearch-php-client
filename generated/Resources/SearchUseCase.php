<?php

namespace Emsearch\Api\Resources;

use Emsearch\Api\ApiClient;
use Emsearch\Api\Exceptions\UnexpectedResponseException;

/**
 * SearchUseCase resource class
 * 
 * @package Emsearch\Api\Resources
 */
class SearchUseCase 
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
	public $project_id;

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
	public $search_use_case_fields_count;

	/**
	 * @var ProjectResponse
	 */
	public $project;

	/**
	 * SearchUseCase resource class constructor
	 * 
	 * @param ApiClient $apiClient API Client to use for this manager requests
	 * @param string $id Format: uuid.
	 * @param string $project_id Format: uuid.
	 * @param string $name
	 * @param string $created_at Format: date-time.
	 * @param string $updated_at Format: date-time.
	 * @param int $search_use_case_fields_count Format: int32.
	 * @param ProjectResponse $project
	 */
	public function __construct(ApiClient $apiClient, $id = null, $project_id = null, $name = null, $created_at = null, $updated_at = null, $search_use_case_fields_count = null, $project = null)
	{
		$this->apiClient = $apiClient;
		$this->id = $id;
		$this->project_id = $project_id;
		$this->name = $name;
		$this->created_at = $created_at;
		$this->updated_at = $updated_at;
		$this->search_use_case_fields_count = $search_use_case_fields_count;
		$this->project = $project;
	}
	/**
	 * Perform search with the specified search use case
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $query_string
	 * @param string $i18n_lang_id
	 * @param int $page
	 * @param int $limit
	 * 
	 * @return SearchUseCaseSearchResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function search($query_string, $i18n_lang_id = null, $page = null, $limit = null)
	{
		$routePath = '/api/searchUseCase/{searchUseCaseId}/search';

		$pathReplacements = [
			'{searchUseCaseId}' => $this->id,
		];

		$routeUrl = str_replace(array_keys($pathReplacements), array_values($pathReplacements), $routePath);

		$queryParameters = [];
		$queryParameters['query_string'] = $query_string;

		if (!is_null($i18n_lang_id)) {
			$queryParameters['i18n_lang_id'] = $i18n_lang_id;
		}

		if (!is_null($page)) {
			$queryParameters['page'] = $page;
		}

		if (!is_null($limit)) {
			$queryParameters['limit'] = $limit;
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

		$response = new SearchUseCaseSearchResponse(
			$this->apiClient, 
			$requestBody['data'], 
			new SearchMeta(
				$this->apiClient, 
				((isset($requestBody['meta']['pagination']) && !is_null($requestBody['meta']['pagination'])) ? (new SearchPagination(
					$this->apiClient, 
					$requestBody['meta']['pagination']['total'], 
					$requestBody['meta']['pagination']['count'], 
					$requestBody['meta']['pagination']['per_page'], 
					$requestBody['meta']['pagination']['current_page'], 
					$requestBody['meta']['pagination']['total_pages']
				)) : null)
			)
		);

		return $response;
	}
	
	/**
	 * Update a search use case
	 * 
	 * Excepted HTTP code : 201
	 * 
	 * @param string $project_id Format: uuid.
	 * @param string $name
	 * 
	 * @return SearchUseCaseResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function update($project_id, $name)
	{
		$routePath = '/api/searchUseCase/{searchUseCaseId}';

		$pathReplacements = [
			'{searchUseCaseId}' => $this->id,
		];

		$routeUrl = str_replace(array_keys($pathReplacements), array_values($pathReplacements), $routePath);

		$bodyParameters = [];
		$bodyParameters['project_id'] = $project_id;
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

		$response = new SearchUseCaseResponse(
			$this->apiClient, 
			new SearchUseCase(
				$this->apiClient, 
				$requestBody['data']['id'], 
				(isset($requestBody['data']['project_id']) ? $requestBody['data']['project_id'] : null), 
				$requestBody['data']['name'], 
				$requestBody['data']['created_at'], 
				$requestBody['data']['updated_at'], 
				(isset($requestBody['data']['search_use_case_fields_count']) ? $requestBody['data']['search_use_case_fields_count'] : null), 
				((isset($requestBody['data']['project']) && !is_null($requestBody['data']['project'])) ? (new ProjectResponse(
					$this->apiClient, 
					new Project(
						$this->apiClient, 
						$requestBody['data']['project']['data']['id'], 
						$requestBody['data']['project']['data']['search_engine_id'], 
						$requestBody['data']['project']['data']['data_stream_id'], 
						$requestBody['data']['project']['data']['name'], 
						$requestBody['data']['project']['data']['created_at'], 
						$requestBody['data']['project']['data']['updated_at'], 
						((isset($requestBody['data']['project']['data']['dataStream']) && !is_null($requestBody['data']['project']['data']['dataStream'])) ? (new DataStreamResponse(
							$this->apiClient, 
							new DataStream(
								$this->apiClient, 
								$requestBody['data']['project']['data']['dataStream']['data']['id'], 
								$requestBody['data']['project']['data']['dataStream']['data']['data_stream_decoder_id'], 
								$requestBody['data']['project']['data']['dataStream']['data']['name'], 
								$requestBody['data']['project']['data']['dataStream']['data']['feed_url'], 
								$requestBody['data']['project']['data']['dataStream']['data']['basic_auth_user'], 
								$requestBody['data']['project']['data']['dataStream']['data']['basic_auth_password'], 
								$requestBody['data']['project']['data']['dataStream']['data']['created_at'], 
								$requestBody['data']['project']['data']['dataStream']['data']['updated_at'], 
								null, 
								((isset($requestBody['data']['project']['data']['dataStream']['data']['dataStreamDecoder']) && !is_null($requestBody['data']['project']['data']['dataStream']['data']['dataStreamDecoder'])) ? (new DataStreamDecoderResponse(
									$this->apiClient, 
									new DataStreamDecoder(
										$this->apiClient, 
										$requestBody['data']['project']['data']['dataStream']['data']['dataStreamDecoder']['data']['id'], 
										$requestBody['data']['project']['data']['dataStream']['data']['dataStreamDecoder']['data']['name'], 
										$requestBody['data']['project']['data']['dataStream']['data']['dataStreamDecoder']['data']['class_name'], 
										$requestBody['data']['project']['data']['dataStream']['data']['dataStreamDecoder']['data']['file_mime_type'], 
										$requestBody['data']['project']['data']['dataStream']['data']['dataStreamDecoder']['data']['created_at'], 
										$requestBody['data']['project']['data']['dataStream']['data']['dataStreamDecoder']['data']['updated_at']
									)
								)) : null)
							)
						)) : null), 
						((isset($requestBody['data']['project']['data']['searchEngine']) && !is_null($requestBody['data']['project']['data']['searchEngine'])) ? (new SearchEngineResponse(
							$this->apiClient, 
							new SearchEngine(
								$this->apiClient, 
								$requestBody['data']['project']['data']['searchEngine']['data']['id'], 
								$requestBody['data']['project']['data']['searchEngine']['data']['name'], 
								$requestBody['data']['project']['data']['searchEngine']['data']['class_name'], 
								$requestBody['data']['project']['data']['searchEngine']['data']['created_at'], 
								$requestBody['data']['project']['data']['searchEngine']['data']['updated_at'], 
								(isset($requestBody['data']['project']['data']['searchEngine']['data']['projects_count']) ? $requestBody['data']['project']['data']['searchEngine']['data']['projects_count'] : null)
							)
						)) : null)
					)
				)) : null)
			)
		);

		return $response;
	}
	
	/**
	 * Delete specified search use case
	 * 
	 * Excepted HTTP code : 204
	 * 
	 * @return ErrorResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function delete()
	{
		$routePath = '/api/searchUseCase/{searchUseCaseId}';

		$pathReplacements = [
			'{searchUseCaseId}' => $this->id,
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
	 * Search use case search use case fields list
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $search Search words
	 * @param int $page Format: int32. Pagination : Page number
	 * @param int $limit Format: int32. Pagination : Maximum entries per page
	 * @param string $order_by Order by : {field},[asc|desc]
	 * 
	 * @return SearchUseCaseFieldListResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function getFields($search = null, $page = null, $limit = null, $order_by = null)
	{
		$routePath = '/api/searchUseCase/{searchUseCaseId}/searchUseCaseField';

		$pathReplacements = [
			'{searchUseCaseId}' => $this->id,
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

		$response = new SearchUseCaseFieldListResponse(
			$this->apiClient, 
			array_map(function($data) {
				return new SearchUseCaseField(
					$this->apiClient, 
					$data['search_use_case_id'], 
					$data['data_stream_field_id'], 
					$data['name'], 
					$data['searchable'], 
					$data['to_retrieve'], 
					$data['created_at'], 
					$data['updated_at'], 
					((isset($data['searchUseCase']) && !is_null($data['searchUseCase'])) ? (new SearchUseCaseResponse(
						$this->apiClient, 
						new SearchUseCase(
							$this->apiClient, 
							$data['searchUseCase']['data']['id'], 
							(isset($data['searchUseCase']['data']['project_id']) ? $data['project_id'] : null), 
							$data['searchUseCase']['data']['name'], 
							$data['searchUseCase']['data']['created_at'], 
							$data['searchUseCase']['data']['updated_at'], 
							(isset($data['searchUseCase']['data']['search_use_case_fields_count']) ? $data['search_use_case_fields_count'] : null), 
							((isset($data['searchUseCase']['data']['project']) && !is_null($data['searchUseCase']['data']['project'])) ? (new ProjectResponse(
								$this->apiClient, 
								new Project(
									$this->apiClient, 
									$data['searchUseCase']['data']['project']['data']['id'], 
									$data['searchUseCase']['data']['project']['data']['search_engine_id'], 
									$data['searchUseCase']['data']['project']['data']['data_stream_id'], 
									$data['searchUseCase']['data']['project']['data']['name'], 
									$data['searchUseCase']['data']['project']['data']['created_at'], 
									$data['searchUseCase']['data']['project']['data']['updated_at'], 
									((isset($data['searchUseCase']['data']['project']['data']['dataStream']) && !is_null($data['searchUseCase']['data']['project']['data']['dataStream'])) ? (new DataStreamResponse(
										$this->apiClient, 
										new DataStream(
											$this->apiClient, 
											$data['searchUseCase']['data']['project']['data']['dataStream']['data']['id'], 
											$data['searchUseCase']['data']['project']['data']['dataStream']['data']['data_stream_decoder_id'], 
											$data['searchUseCase']['data']['project']['data']['dataStream']['data']['name'], 
											$data['searchUseCase']['data']['project']['data']['dataStream']['data']['feed_url'], 
											$data['searchUseCase']['data']['project']['data']['dataStream']['data']['basic_auth_user'], 
											$data['searchUseCase']['data']['project']['data']['dataStream']['data']['basic_auth_password'], 
											$data['searchUseCase']['data']['project']['data']['dataStream']['data']['created_at'], 
											$data['searchUseCase']['data']['project']['data']['dataStream']['data']['updated_at'], 
											null, 
											((isset($data['searchUseCase']['data']['project']['data']['dataStream']['data']['dataStreamDecoder']) && !is_null($data['searchUseCase']['data']['project']['data']['dataStream']['data']['dataStreamDecoder'])) ? (new DataStreamDecoderResponse(
												$this->apiClient, 
												new DataStreamDecoder(
													$this->apiClient, 
													$data['searchUseCase']['data']['project']['data']['dataStream']['data']['dataStreamDecoder']['data']['id'], 
													$data['searchUseCase']['data']['project']['data']['dataStream']['data']['dataStreamDecoder']['data']['name'], 
													$data['searchUseCase']['data']['project']['data']['dataStream']['data']['dataStreamDecoder']['data']['class_name'], 
													$data['searchUseCase']['data']['project']['data']['dataStream']['data']['dataStreamDecoder']['data']['file_mime_type'], 
													$data['searchUseCase']['data']['project']['data']['dataStream']['data']['dataStreamDecoder']['data']['created_at'], 
													$data['searchUseCase']['data']['project']['data']['dataStream']['data']['dataStreamDecoder']['data']['updated_at']
												)
											)) : null)
										)
									)) : null), 
									((isset($data['searchUseCase']['data']['project']['data']['searchEngine']) && !is_null($data['searchUseCase']['data']['project']['data']['searchEngine'])) ? (new SearchEngineResponse(
										$this->apiClient, 
										new SearchEngine(
											$this->apiClient, 
											$data['searchUseCase']['data']['project']['data']['searchEngine']['data']['id'], 
											$data['searchUseCase']['data']['project']['data']['searchEngine']['data']['name'], 
											$data['searchUseCase']['data']['project']['data']['searchEngine']['data']['class_name'], 
											$data['searchUseCase']['data']['project']['data']['searchEngine']['data']['created_at'], 
											$data['searchUseCase']['data']['project']['data']['searchEngine']['data']['updated_at'], 
											(isset($data['searchUseCase']['data']['project']['data']['searchEngine']['data']['projects_count']) ? $data['projects_count'] : null)
										)
									)) : null)
								)
							)) : null)
						)
					)) : null), 
					((isset($data['dataStreamField']) && !is_null($data['dataStreamField'])) ? (new DataStreamFieldResponse(
						$this->apiClient, 
						new DataStreamField(
							$this->apiClient, 
							$data['dataStreamField']['data']['id'], 
							$data['dataStreamField']['data']['data_stream_id'], 
							$data['dataStreamField']['data']['name'], 
							$data['dataStreamField']['data']['path'], 
							$data['dataStreamField']['data']['versioned'], 
							$data['dataStreamField']['data']['searchable'], 
							$data['dataStreamField']['data']['to_retrieve'], 
							$data['dataStreamField']['data']['created_at'], 
							$data['dataStreamField']['data']['updated_at'], 
							((isset($data['dataStreamField']['data']['dataStream']) && !is_null($data['dataStreamField']['data']['dataStream'])) ? (new DataStreamResponse(
								$this->apiClient, 
								new DataStream(
									$this->apiClient, 
									$data['dataStreamField']['data']['dataStream']['data']['id'], 
									$data['dataStreamField']['data']['dataStream']['data']['data_stream_decoder_id'], 
									$data['dataStreamField']['data']['dataStream']['data']['name'], 
									$data['dataStreamField']['data']['dataStream']['data']['feed_url'], 
									$data['dataStreamField']['data']['dataStream']['data']['basic_auth_user'], 
									$data['dataStreamField']['data']['dataStream']['data']['basic_auth_password'], 
									$data['dataStreamField']['data']['dataStream']['data']['created_at'], 
									$data['dataStreamField']['data']['dataStream']['data']['updated_at'], 
									((isset($data['dataStreamField']['data']['dataStream']['data']['project']) && !is_null($data['dataStreamField']['data']['dataStream']['data']['project'])) ? (new ProjectResponse(
										$this->apiClient, 
										new Project(
											$this->apiClient, 
											$data['dataStreamField']['data']['dataStream']['data']['project']['data']['id'], 
											$data['dataStreamField']['data']['dataStream']['data']['project']['data']['search_engine_id'], 
											$data['dataStreamField']['data']['dataStream']['data']['project']['data']['data_stream_id'], 
											$data['dataStreamField']['data']['dataStream']['data']['project']['data']['name'], 
											$data['dataStreamField']['data']['dataStream']['data']['project']['data']['created_at'], 
											$data['dataStreamField']['data']['dataStream']['data']['project']['data']['updated_at'], 
											null, 
											((isset($data['dataStreamField']['data']['dataStream']['data']['project']['data']['searchEngine']) && !is_null($data['dataStreamField']['data']['dataStream']['data']['project']['data']['searchEngine'])) ? (new SearchEngineResponse(
												$this->apiClient, 
												new SearchEngine(
													$this->apiClient, 
													$data['dataStreamField']['data']['dataStream']['data']['project']['data']['searchEngine']['data']['id'], 
													$data['dataStreamField']['data']['dataStream']['data']['project']['data']['searchEngine']['data']['name'], 
													$data['dataStreamField']['data']['dataStream']['data']['project']['data']['searchEngine']['data']['class_name'], 
													$data['dataStreamField']['data']['dataStream']['data']['project']['data']['searchEngine']['data']['created_at'], 
													$data['dataStreamField']['data']['dataStream']['data']['project']['data']['searchEngine']['data']['updated_at'], 
													(isset($data['dataStreamField']['data']['dataStream']['data']['project']['data']['searchEngine']['data']['projects_count']) ? $data['projects_count'] : null)
												)
											)) : null)
										)
									)) : null), 
									((isset($data['dataStreamField']['data']['dataStream']['data']['dataStreamDecoder']) && !is_null($data['dataStreamField']['data']['dataStream']['data']['dataStreamDecoder'])) ? (new DataStreamDecoderResponse(
										$this->apiClient, 
										new DataStreamDecoder(
											$this->apiClient, 
											$data['dataStreamField']['data']['dataStream']['data']['dataStreamDecoder']['data']['id'], 
											$data['dataStreamField']['data']['dataStream']['data']['dataStreamDecoder']['data']['name'], 
											$data['dataStreamField']['data']['dataStream']['data']['dataStreamDecoder']['data']['class_name'], 
											$data['dataStreamField']['data']['dataStream']['data']['dataStreamDecoder']['data']['file_mime_type'], 
											$data['dataStreamField']['data']['dataStream']['data']['dataStreamDecoder']['data']['created_at'], 
											$data['dataStreamField']['data']['dataStream']['data']['dataStreamDecoder']['data']['updated_at']
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
	 * Search use case widget list
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $search Search words
	 * @param int $page Format: int32. Pagination : Page number
	 * @param int $limit Format: int32. Pagination : Maximum entries per page
	 * @param string $order_by Order by : {field},[asc|desc]
	 * 
	 * @return WidgetListResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function getWidgets($search = null, $page = null, $limit = null, $order_by = null)
	{
		$routePath = '/api/searchUseCase/{searchUseCaseId}/widget';

		$pathReplacements = [
			'{searchUseCaseId}' => $this->id,
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
}
