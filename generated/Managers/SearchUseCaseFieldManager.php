<?php

namespace Emsearch\Api\Managers;

use Emsearch\Api\ApiClient;
use Emsearch\Api\Exceptions\UnexpectedResponseException;
use Emsearch\Api\Resources\SearchUseCaseFieldListResponse;
use Emsearch\Api\Resources\ErrorResponse;
use Emsearch\Api\Resources\SearchUseCaseFieldResponse;
use Emsearch\Api\Resources\SearchUseCaseField;
use Emsearch\Api\Resources\SearchUseCaseResponse;
use Emsearch\Api\Resources\SearchUseCase;
use Emsearch\Api\Resources\ProjectResponse;
use Emsearch\Api\Resources\Project;
use Emsearch\Api\Resources\DataStreamResponse;
use Emsearch\Api\Resources\DataStream;
use Emsearch\Api\Resources\DataStreamDecoderResponse;
use Emsearch\Api\Resources\DataStreamDecoder;
use Emsearch\Api\Resources\SearchEngineResponse;
use Emsearch\Api\Resources\SearchEngine;
use Emsearch\Api\Resources\DataStreamFieldResponse;
use Emsearch\Api\Resources\DataStreamField;
use Emsearch\Api\Resources\Meta;
use Emsearch\Api\Resources\Pagination;

/**
 * SearchUseCaseField manager class
 * 
 * @package Emsearch\Api\Managers
 */
class SearchUseCaseFieldManager 
{
	/**
	 * API client
	 *
	 * @var ApiClient
	 */
	protected $apiClient;

	/**
	 * SearchUseCaseField manager class constructor
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
	 * Show search use case field list
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $include Include responses : {include1},{include2,{include3}[...]
	 * @param string $search Search words
	 * @param int $page Format: int32. Pagination : Page number
	 * @param int $limit Format: int32. Pagination : Maximum entries per page
	 * @param string $order_by Order by : {field},[asc|desc]
	 * 
	 * @return SearchUseCaseFieldListResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function all($include = null, $search = null, $page = null, $limit = null, $order_by = null)
	{
		$routeUrl = '/api/searchUseCaseField';

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
	 * Create and store new search use case field
	 * 
	 * Excepted HTTP code : 201
	 * 
	 * @param string $search_use_case_id Format: uuid.
	 * @param string $data_stream_field_id Format: uuid.
	 * @param string $name
	 * @param boolean $searchable
	 * @param boolean $to_retrieve
	 * 
	 * @return SearchUseCaseFieldResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function create($search_use_case_id, $data_stream_field_id, $name, $searchable, $to_retrieve)
	{
		$routeUrl = '/api/searchUseCaseField';

		$bodyParameters = [];
		$bodyParameters['search_use_case_id'] = $search_use_case_id;
		$bodyParameters['data_stream_field_id'] = $data_stream_field_id;
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

		$response = new SearchUseCaseFieldResponse(
			$this->apiClient, 
			new SearchUseCaseField(
				$this->apiClient, 
				$requestBody['data']['search_use_case_id'], 
				$requestBody['data']['data_stream_field_id'], 
				$requestBody['data']['name'], 
				$requestBody['data']['searchable'], 
				$requestBody['data']['to_retrieve'], 
				$requestBody['data']['created_at'], 
				$requestBody['data']['updated_at'], 
				((isset($requestBody['data']['searchUseCase']) && !is_null($requestBody['data']['searchUseCase'])) ? (new SearchUseCaseResponse(
					$this->apiClient, 
					new SearchUseCase(
						$this->apiClient, 
						$requestBody['data']['searchUseCase']['data']['id'], 
						(isset($requestBody['data']['searchUseCase']['data']['project_id']) ? $requestBody['data']['searchUseCase']['data']['project_id'] : null), 
						$requestBody['data']['searchUseCase']['data']['name'], 
						$requestBody['data']['searchUseCase']['data']['created_at'], 
						$requestBody['data']['searchUseCase']['data']['updated_at'], 
						(isset($requestBody['data']['searchUseCase']['data']['search_use_case_fields_count']) ? $requestBody['data']['searchUseCase']['data']['search_use_case_fields_count'] : null), 
						((isset($requestBody['data']['searchUseCase']['data']['project']) && !is_null($requestBody['data']['searchUseCase']['data']['project'])) ? (new ProjectResponse(
							$this->apiClient, 
							new Project(
								$this->apiClient, 
								$requestBody['data']['searchUseCase']['data']['project']['data']['id'], 
								$requestBody['data']['searchUseCase']['data']['project']['data']['search_engine_id'], 
								$requestBody['data']['searchUseCase']['data']['project']['data']['data_stream_id'], 
								$requestBody['data']['searchUseCase']['data']['project']['data']['name'], 
								$requestBody['data']['searchUseCase']['data']['project']['data']['created_at'], 
								$requestBody['data']['searchUseCase']['data']['project']['data']['updated_at'], 
								((isset($requestBody['data']['searchUseCase']['data']['project']['data']['dataStream']) && !is_null($requestBody['data']['searchUseCase']['data']['project']['data']['dataStream'])) ? (new DataStreamResponse(
									$this->apiClient, 
									new DataStream(
										$this->apiClient, 
										$requestBody['data']['searchUseCase']['data']['project']['data']['dataStream']['data']['id'], 
										$requestBody['data']['searchUseCase']['data']['project']['data']['dataStream']['data']['data_stream_decoder_id'], 
										$requestBody['data']['searchUseCase']['data']['project']['data']['dataStream']['data']['name'], 
										$requestBody['data']['searchUseCase']['data']['project']['data']['dataStream']['data']['feed_url'], 
										$requestBody['data']['searchUseCase']['data']['project']['data']['dataStream']['data']['basic_auth_user'], 
										$requestBody['data']['searchUseCase']['data']['project']['data']['dataStream']['data']['basic_auth_password'], 
										$requestBody['data']['searchUseCase']['data']['project']['data']['dataStream']['data']['created_at'], 
										$requestBody['data']['searchUseCase']['data']['project']['data']['dataStream']['data']['updated_at'], 
										null, 
										((isset($requestBody['data']['searchUseCase']['data']['project']['data']['dataStream']['data']['dataStreamDecoder']) && !is_null($requestBody['data']['searchUseCase']['data']['project']['data']['dataStream']['data']['dataStreamDecoder'])) ? (new DataStreamDecoderResponse(
											$this->apiClient, 
											new DataStreamDecoder(
												$this->apiClient, 
												$requestBody['data']['searchUseCase']['data']['project']['data']['dataStream']['data']['dataStreamDecoder']['data']['id'], 
												$requestBody['data']['searchUseCase']['data']['project']['data']['dataStream']['data']['dataStreamDecoder']['data']['name'], 
												$requestBody['data']['searchUseCase']['data']['project']['data']['dataStream']['data']['dataStreamDecoder']['data']['class_name'], 
												$requestBody['data']['searchUseCase']['data']['project']['data']['dataStream']['data']['dataStreamDecoder']['data']['file_mime_type'], 
												$requestBody['data']['searchUseCase']['data']['project']['data']['dataStream']['data']['dataStreamDecoder']['data']['created_at'], 
												$requestBody['data']['searchUseCase']['data']['project']['data']['dataStream']['data']['dataStreamDecoder']['data']['updated_at']
											)
										)) : null)
									)
								)) : null), 
								((isset($requestBody['data']['searchUseCase']['data']['project']['data']['searchEngine']) && !is_null($requestBody['data']['searchUseCase']['data']['project']['data']['searchEngine'])) ? (new SearchEngineResponse(
									$this->apiClient, 
									new SearchEngine(
										$this->apiClient, 
										$requestBody['data']['searchUseCase']['data']['project']['data']['searchEngine']['data']['id'], 
										$requestBody['data']['searchUseCase']['data']['project']['data']['searchEngine']['data']['name'], 
										$requestBody['data']['searchUseCase']['data']['project']['data']['searchEngine']['data']['class_name'], 
										$requestBody['data']['searchUseCase']['data']['project']['data']['searchEngine']['data']['created_at'], 
										$requestBody['data']['searchUseCase']['data']['project']['data']['searchEngine']['data']['updated_at'], 
										(isset($requestBody['data']['searchUseCase']['data']['project']['data']['searchEngine']['data']['projects_count']) ? $requestBody['data']['searchUseCase']['data']['project']['data']['searchEngine']['data']['projects_count'] : null)
									)
								)) : null)
							)
						)) : null)
					)
				)) : null), 
				((isset($requestBody['data']['dataStreamField']) && !is_null($requestBody['data']['dataStreamField'])) ? (new DataStreamFieldResponse(
					$this->apiClient, 
					new DataStreamField(
						$this->apiClient, 
						$requestBody['data']['dataStreamField']['data']['id'], 
						$requestBody['data']['dataStreamField']['data']['data_stream_id'], 
						$requestBody['data']['dataStreamField']['data']['name'], 
						$requestBody['data']['dataStreamField']['data']['path'], 
						$requestBody['data']['dataStreamField']['data']['versioned'], 
						$requestBody['data']['dataStreamField']['data']['searchable'], 
						$requestBody['data']['dataStreamField']['data']['to_retrieve'], 
						$requestBody['data']['dataStreamField']['data']['created_at'], 
						$requestBody['data']['dataStreamField']['data']['updated_at'], 
						((isset($requestBody['data']['dataStreamField']['data']['dataStream']) && !is_null($requestBody['data']['dataStreamField']['data']['dataStream'])) ? (new DataStreamResponse(
							$this->apiClient, 
							new DataStream(
								$this->apiClient, 
								$requestBody['data']['dataStreamField']['data']['dataStream']['data']['id'], 
								$requestBody['data']['dataStreamField']['data']['dataStream']['data']['data_stream_decoder_id'], 
								$requestBody['data']['dataStreamField']['data']['dataStream']['data']['name'], 
								$requestBody['data']['dataStreamField']['data']['dataStream']['data']['feed_url'], 
								$requestBody['data']['dataStreamField']['data']['dataStream']['data']['basic_auth_user'], 
								$requestBody['data']['dataStreamField']['data']['dataStream']['data']['basic_auth_password'], 
								$requestBody['data']['dataStreamField']['data']['dataStream']['data']['created_at'], 
								$requestBody['data']['dataStreamField']['data']['dataStream']['data']['updated_at'], 
								((isset($requestBody['data']['dataStreamField']['data']['dataStream']['data']['project']) && !is_null($requestBody['data']['dataStreamField']['data']['dataStream']['data']['project'])) ? (new ProjectResponse(
									$this->apiClient, 
									new Project(
										$this->apiClient, 
										$requestBody['data']['dataStreamField']['data']['dataStream']['data']['project']['data']['id'], 
										$requestBody['data']['dataStreamField']['data']['dataStream']['data']['project']['data']['search_engine_id'], 
										$requestBody['data']['dataStreamField']['data']['dataStream']['data']['project']['data']['data_stream_id'], 
										$requestBody['data']['dataStreamField']['data']['dataStream']['data']['project']['data']['name'], 
										$requestBody['data']['dataStreamField']['data']['dataStream']['data']['project']['data']['created_at'], 
										$requestBody['data']['dataStreamField']['data']['dataStream']['data']['project']['data']['updated_at'], 
										null, 
										((isset($requestBody['data']['dataStreamField']['data']['dataStream']['data']['project']['data']['searchEngine']) && !is_null($requestBody['data']['dataStreamField']['data']['dataStream']['data']['project']['data']['searchEngine'])) ? (new SearchEngineResponse(
											$this->apiClient, 
											new SearchEngine(
												$this->apiClient, 
												$requestBody['data']['dataStreamField']['data']['dataStream']['data']['project']['data']['searchEngine']['data']['id'], 
												$requestBody['data']['dataStreamField']['data']['dataStream']['data']['project']['data']['searchEngine']['data']['name'], 
												$requestBody['data']['dataStreamField']['data']['dataStream']['data']['project']['data']['searchEngine']['data']['class_name'], 
												$requestBody['data']['dataStreamField']['data']['dataStream']['data']['project']['data']['searchEngine']['data']['created_at'], 
												$requestBody['data']['dataStreamField']['data']['dataStream']['data']['project']['data']['searchEngine']['data']['updated_at'], 
												(isset($requestBody['data']['dataStreamField']['data']['dataStream']['data']['project']['data']['searchEngine']['data']['projects_count']) ? $requestBody['data']['dataStreamField']['data']['dataStream']['data']['project']['data']['searchEngine']['data']['projects_count'] : null)
											)
										)) : null)
									)
								)) : null), 
								((isset($requestBody['data']['dataStreamField']['data']['dataStream']['data']['dataStreamDecoder']) && !is_null($requestBody['data']['dataStreamField']['data']['dataStream']['data']['dataStreamDecoder'])) ? (new DataStreamDecoderResponse(
									$this->apiClient, 
									new DataStreamDecoder(
										$this->apiClient, 
										$requestBody['data']['dataStreamField']['data']['dataStream']['data']['dataStreamDecoder']['data']['id'], 
										$requestBody['data']['dataStreamField']['data']['dataStream']['data']['dataStreamDecoder']['data']['name'], 
										$requestBody['data']['dataStreamField']['data']['dataStream']['data']['dataStreamDecoder']['data']['class_name'], 
										$requestBody['data']['dataStreamField']['data']['dataStream']['data']['dataStreamDecoder']['data']['file_mime_type'], 
										$requestBody['data']['dataStreamField']['data']['dataStream']['data']['dataStreamDecoder']['data']['created_at'], 
										$requestBody['data']['dataStreamField']['data']['dataStream']['data']['dataStreamDecoder']['data']['updated_at']
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
	 * Get specified search use case field
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $searchUseCaseId Search use case UUID
	 * @param string $dataStreamFieldId Data stream field UUID
	 * @param string $include Include responses : {include1},{include2,{include3}[...]
	 * 
	 * @return SearchUseCaseFieldResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function get($searchUseCaseId, $dataStreamFieldId, $include = null)
	{
		$routePath = '/api/searchUseCaseField/{searchUseCaseId},{dataStreamFieldId}';

		$pathReplacements = [
			'{searchUseCaseId}' => $searchUseCaseId,
			'{dataStreamFieldId}' => $dataStreamFieldId,
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

		$response = new SearchUseCaseFieldResponse(
			$this->apiClient, 
			new SearchUseCaseField(
				$this->apiClient, 
				$requestBody['data']['search_use_case_id'], 
				$requestBody['data']['data_stream_field_id'], 
				$requestBody['data']['name'], 
				$requestBody['data']['searchable'], 
				$requestBody['data']['to_retrieve'], 
				$requestBody['data']['created_at'], 
				$requestBody['data']['updated_at'], 
				((isset($requestBody['data']['searchUseCase']) && !is_null($requestBody['data']['searchUseCase'])) ? (new SearchUseCaseResponse(
					$this->apiClient, 
					new SearchUseCase(
						$this->apiClient, 
						$requestBody['data']['searchUseCase']['data']['id'], 
						(isset($requestBody['data']['searchUseCase']['data']['project_id']) ? $requestBody['data']['searchUseCase']['data']['project_id'] : null), 
						$requestBody['data']['searchUseCase']['data']['name'], 
						$requestBody['data']['searchUseCase']['data']['created_at'], 
						$requestBody['data']['searchUseCase']['data']['updated_at'], 
						(isset($requestBody['data']['searchUseCase']['data']['search_use_case_fields_count']) ? $requestBody['data']['searchUseCase']['data']['search_use_case_fields_count'] : null), 
						((isset($requestBody['data']['searchUseCase']['data']['project']) && !is_null($requestBody['data']['searchUseCase']['data']['project'])) ? (new ProjectResponse(
							$this->apiClient, 
							new Project(
								$this->apiClient, 
								$requestBody['data']['searchUseCase']['data']['project']['data']['id'], 
								$requestBody['data']['searchUseCase']['data']['project']['data']['search_engine_id'], 
								$requestBody['data']['searchUseCase']['data']['project']['data']['data_stream_id'], 
								$requestBody['data']['searchUseCase']['data']['project']['data']['name'], 
								$requestBody['data']['searchUseCase']['data']['project']['data']['created_at'], 
								$requestBody['data']['searchUseCase']['data']['project']['data']['updated_at'], 
								((isset($requestBody['data']['searchUseCase']['data']['project']['data']['dataStream']) && !is_null($requestBody['data']['searchUseCase']['data']['project']['data']['dataStream'])) ? (new DataStreamResponse(
									$this->apiClient, 
									new DataStream(
										$this->apiClient, 
										$requestBody['data']['searchUseCase']['data']['project']['data']['dataStream']['data']['id'], 
										$requestBody['data']['searchUseCase']['data']['project']['data']['dataStream']['data']['data_stream_decoder_id'], 
										$requestBody['data']['searchUseCase']['data']['project']['data']['dataStream']['data']['name'], 
										$requestBody['data']['searchUseCase']['data']['project']['data']['dataStream']['data']['feed_url'], 
										$requestBody['data']['searchUseCase']['data']['project']['data']['dataStream']['data']['basic_auth_user'], 
										$requestBody['data']['searchUseCase']['data']['project']['data']['dataStream']['data']['basic_auth_password'], 
										$requestBody['data']['searchUseCase']['data']['project']['data']['dataStream']['data']['created_at'], 
										$requestBody['data']['searchUseCase']['data']['project']['data']['dataStream']['data']['updated_at'], 
										null, 
										((isset($requestBody['data']['searchUseCase']['data']['project']['data']['dataStream']['data']['dataStreamDecoder']) && !is_null($requestBody['data']['searchUseCase']['data']['project']['data']['dataStream']['data']['dataStreamDecoder'])) ? (new DataStreamDecoderResponse(
											$this->apiClient, 
											new DataStreamDecoder(
												$this->apiClient, 
												$requestBody['data']['searchUseCase']['data']['project']['data']['dataStream']['data']['dataStreamDecoder']['data']['id'], 
												$requestBody['data']['searchUseCase']['data']['project']['data']['dataStream']['data']['dataStreamDecoder']['data']['name'], 
												$requestBody['data']['searchUseCase']['data']['project']['data']['dataStream']['data']['dataStreamDecoder']['data']['class_name'], 
												$requestBody['data']['searchUseCase']['data']['project']['data']['dataStream']['data']['dataStreamDecoder']['data']['file_mime_type'], 
												$requestBody['data']['searchUseCase']['data']['project']['data']['dataStream']['data']['dataStreamDecoder']['data']['created_at'], 
												$requestBody['data']['searchUseCase']['data']['project']['data']['dataStream']['data']['dataStreamDecoder']['data']['updated_at']
											)
										)) : null)
									)
								)) : null), 
								((isset($requestBody['data']['searchUseCase']['data']['project']['data']['searchEngine']) && !is_null($requestBody['data']['searchUseCase']['data']['project']['data']['searchEngine'])) ? (new SearchEngineResponse(
									$this->apiClient, 
									new SearchEngine(
										$this->apiClient, 
										$requestBody['data']['searchUseCase']['data']['project']['data']['searchEngine']['data']['id'], 
										$requestBody['data']['searchUseCase']['data']['project']['data']['searchEngine']['data']['name'], 
										$requestBody['data']['searchUseCase']['data']['project']['data']['searchEngine']['data']['class_name'], 
										$requestBody['data']['searchUseCase']['data']['project']['data']['searchEngine']['data']['created_at'], 
										$requestBody['data']['searchUseCase']['data']['project']['data']['searchEngine']['data']['updated_at'], 
										(isset($requestBody['data']['searchUseCase']['data']['project']['data']['searchEngine']['data']['projects_count']) ? $requestBody['data']['searchUseCase']['data']['project']['data']['searchEngine']['data']['projects_count'] : null)
									)
								)) : null)
							)
						)) : null)
					)
				)) : null), 
				((isset($requestBody['data']['dataStreamField']) && !is_null($requestBody['data']['dataStreamField'])) ? (new DataStreamFieldResponse(
					$this->apiClient, 
					new DataStreamField(
						$this->apiClient, 
						$requestBody['data']['dataStreamField']['data']['id'], 
						$requestBody['data']['dataStreamField']['data']['data_stream_id'], 
						$requestBody['data']['dataStreamField']['data']['name'], 
						$requestBody['data']['dataStreamField']['data']['path'], 
						$requestBody['data']['dataStreamField']['data']['versioned'], 
						$requestBody['data']['dataStreamField']['data']['searchable'], 
						$requestBody['data']['dataStreamField']['data']['to_retrieve'], 
						$requestBody['data']['dataStreamField']['data']['created_at'], 
						$requestBody['data']['dataStreamField']['data']['updated_at'], 
						((isset($requestBody['data']['dataStreamField']['data']['dataStream']) && !is_null($requestBody['data']['dataStreamField']['data']['dataStream'])) ? (new DataStreamResponse(
							$this->apiClient, 
							new DataStream(
								$this->apiClient, 
								$requestBody['data']['dataStreamField']['data']['dataStream']['data']['id'], 
								$requestBody['data']['dataStreamField']['data']['dataStream']['data']['data_stream_decoder_id'], 
								$requestBody['data']['dataStreamField']['data']['dataStream']['data']['name'], 
								$requestBody['data']['dataStreamField']['data']['dataStream']['data']['feed_url'], 
								$requestBody['data']['dataStreamField']['data']['dataStream']['data']['basic_auth_user'], 
								$requestBody['data']['dataStreamField']['data']['dataStream']['data']['basic_auth_password'], 
								$requestBody['data']['dataStreamField']['data']['dataStream']['data']['created_at'], 
								$requestBody['data']['dataStreamField']['data']['dataStream']['data']['updated_at'], 
								((isset($requestBody['data']['dataStreamField']['data']['dataStream']['data']['project']) && !is_null($requestBody['data']['dataStreamField']['data']['dataStream']['data']['project'])) ? (new ProjectResponse(
									$this->apiClient, 
									new Project(
										$this->apiClient, 
										$requestBody['data']['dataStreamField']['data']['dataStream']['data']['project']['data']['id'], 
										$requestBody['data']['dataStreamField']['data']['dataStream']['data']['project']['data']['search_engine_id'], 
										$requestBody['data']['dataStreamField']['data']['dataStream']['data']['project']['data']['data_stream_id'], 
										$requestBody['data']['dataStreamField']['data']['dataStream']['data']['project']['data']['name'], 
										$requestBody['data']['dataStreamField']['data']['dataStream']['data']['project']['data']['created_at'], 
										$requestBody['data']['dataStreamField']['data']['dataStream']['data']['project']['data']['updated_at'], 
										null, 
										((isset($requestBody['data']['dataStreamField']['data']['dataStream']['data']['project']['data']['searchEngine']) && !is_null($requestBody['data']['dataStreamField']['data']['dataStream']['data']['project']['data']['searchEngine'])) ? (new SearchEngineResponse(
											$this->apiClient, 
											new SearchEngine(
												$this->apiClient, 
												$requestBody['data']['dataStreamField']['data']['dataStream']['data']['project']['data']['searchEngine']['data']['id'], 
												$requestBody['data']['dataStreamField']['data']['dataStream']['data']['project']['data']['searchEngine']['data']['name'], 
												$requestBody['data']['dataStreamField']['data']['dataStream']['data']['project']['data']['searchEngine']['data']['class_name'], 
												$requestBody['data']['dataStreamField']['data']['dataStream']['data']['project']['data']['searchEngine']['data']['created_at'], 
												$requestBody['data']['dataStreamField']['data']['dataStream']['data']['project']['data']['searchEngine']['data']['updated_at'], 
												(isset($requestBody['data']['dataStreamField']['data']['dataStream']['data']['project']['data']['searchEngine']['data']['projects_count']) ? $requestBody['data']['dataStreamField']['data']['dataStream']['data']['project']['data']['searchEngine']['data']['projects_count'] : null)
											)
										)) : null)
									)
								)) : null), 
								((isset($requestBody['data']['dataStreamField']['data']['dataStream']['data']['dataStreamDecoder']) && !is_null($requestBody['data']['dataStreamField']['data']['dataStream']['data']['dataStreamDecoder'])) ? (new DataStreamDecoderResponse(
									$this->apiClient, 
									new DataStreamDecoder(
										$this->apiClient, 
										$requestBody['data']['dataStreamField']['data']['dataStream']['data']['dataStreamDecoder']['data']['id'], 
										$requestBody['data']['dataStreamField']['data']['dataStream']['data']['dataStreamDecoder']['data']['name'], 
										$requestBody['data']['dataStreamField']['data']['dataStream']['data']['dataStreamDecoder']['data']['class_name'], 
										$requestBody['data']['dataStreamField']['data']['dataStream']['data']['dataStreamDecoder']['data']['file_mime_type'], 
										$requestBody['data']['dataStreamField']['data']['dataStream']['data']['dataStreamDecoder']['data']['created_at'], 
										$requestBody['data']['dataStreamField']['data']['dataStream']['data']['dataStreamDecoder']['data']['updated_at']
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
	 * Update a specified search use case field
	 * 
	 * Excepted HTTP code : 201
	 * 
	 * @param string $searchUseCaseId Search use case UUID
	 * @param string $dataStreamFieldId Data stream field UUID
	 * @param string $search_use_case_id Format: uuid.
	 * @param string $data_stream_field_id Format: uuid.
	 * @param string $name
	 * @param boolean $searchable
	 * @param boolean $to_retrieve
	 * 
	 * @return SearchUseCaseFieldResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function update($searchUseCaseId, $dataStreamFieldId, $search_use_case_id, $data_stream_field_id, $name, $searchable, $to_retrieve)
	{
		$routePath = '/api/searchUseCaseField/{searchUseCaseId},{dataStreamFieldId}';

		$pathReplacements = [
			'{searchUseCaseId}' => $searchUseCaseId,
			'{dataStreamFieldId}' => $dataStreamFieldId,
		];

		$routeUrl = str_replace(array_keys($pathReplacements), array_values($pathReplacements), $routePath);

		$bodyParameters = [];
		$bodyParameters['search_use_case_id'] = $search_use_case_id;
		$bodyParameters['data_stream_field_id'] = $data_stream_field_id;
		$bodyParameters['name'] = $name;
		$bodyParameters['searchable'] = $searchable;
		$bodyParameters['to_retrieve'] = $to_retrieve;

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

		$response = new SearchUseCaseFieldResponse(
			$this->apiClient, 
			new SearchUseCaseField(
				$this->apiClient, 
				$requestBody['data']['search_use_case_id'], 
				$requestBody['data']['data_stream_field_id'], 
				$requestBody['data']['name'], 
				$requestBody['data']['searchable'], 
				$requestBody['data']['to_retrieve'], 
				$requestBody['data']['created_at'], 
				$requestBody['data']['updated_at'], 
				((isset($requestBody['data']['searchUseCase']) && !is_null($requestBody['data']['searchUseCase'])) ? (new SearchUseCaseResponse(
					$this->apiClient, 
					new SearchUseCase(
						$this->apiClient, 
						$requestBody['data']['searchUseCase']['data']['id'], 
						(isset($requestBody['data']['searchUseCase']['data']['project_id']) ? $requestBody['data']['searchUseCase']['data']['project_id'] : null), 
						$requestBody['data']['searchUseCase']['data']['name'], 
						$requestBody['data']['searchUseCase']['data']['created_at'], 
						$requestBody['data']['searchUseCase']['data']['updated_at'], 
						(isset($requestBody['data']['searchUseCase']['data']['search_use_case_fields_count']) ? $requestBody['data']['searchUseCase']['data']['search_use_case_fields_count'] : null), 
						((isset($requestBody['data']['searchUseCase']['data']['project']) && !is_null($requestBody['data']['searchUseCase']['data']['project'])) ? (new ProjectResponse(
							$this->apiClient, 
							new Project(
								$this->apiClient, 
								$requestBody['data']['searchUseCase']['data']['project']['data']['id'], 
								$requestBody['data']['searchUseCase']['data']['project']['data']['search_engine_id'], 
								$requestBody['data']['searchUseCase']['data']['project']['data']['data_stream_id'], 
								$requestBody['data']['searchUseCase']['data']['project']['data']['name'], 
								$requestBody['data']['searchUseCase']['data']['project']['data']['created_at'], 
								$requestBody['data']['searchUseCase']['data']['project']['data']['updated_at'], 
								((isset($requestBody['data']['searchUseCase']['data']['project']['data']['dataStream']) && !is_null($requestBody['data']['searchUseCase']['data']['project']['data']['dataStream'])) ? (new DataStreamResponse(
									$this->apiClient, 
									new DataStream(
										$this->apiClient, 
										$requestBody['data']['searchUseCase']['data']['project']['data']['dataStream']['data']['id'], 
										$requestBody['data']['searchUseCase']['data']['project']['data']['dataStream']['data']['data_stream_decoder_id'], 
										$requestBody['data']['searchUseCase']['data']['project']['data']['dataStream']['data']['name'], 
										$requestBody['data']['searchUseCase']['data']['project']['data']['dataStream']['data']['feed_url'], 
										$requestBody['data']['searchUseCase']['data']['project']['data']['dataStream']['data']['basic_auth_user'], 
										$requestBody['data']['searchUseCase']['data']['project']['data']['dataStream']['data']['basic_auth_password'], 
										$requestBody['data']['searchUseCase']['data']['project']['data']['dataStream']['data']['created_at'], 
										$requestBody['data']['searchUseCase']['data']['project']['data']['dataStream']['data']['updated_at'], 
										null, 
										((isset($requestBody['data']['searchUseCase']['data']['project']['data']['dataStream']['data']['dataStreamDecoder']) && !is_null($requestBody['data']['searchUseCase']['data']['project']['data']['dataStream']['data']['dataStreamDecoder'])) ? (new DataStreamDecoderResponse(
											$this->apiClient, 
											new DataStreamDecoder(
												$this->apiClient, 
												$requestBody['data']['searchUseCase']['data']['project']['data']['dataStream']['data']['dataStreamDecoder']['data']['id'], 
												$requestBody['data']['searchUseCase']['data']['project']['data']['dataStream']['data']['dataStreamDecoder']['data']['name'], 
												$requestBody['data']['searchUseCase']['data']['project']['data']['dataStream']['data']['dataStreamDecoder']['data']['class_name'], 
												$requestBody['data']['searchUseCase']['data']['project']['data']['dataStream']['data']['dataStreamDecoder']['data']['file_mime_type'], 
												$requestBody['data']['searchUseCase']['data']['project']['data']['dataStream']['data']['dataStreamDecoder']['data']['created_at'], 
												$requestBody['data']['searchUseCase']['data']['project']['data']['dataStream']['data']['dataStreamDecoder']['data']['updated_at']
											)
										)) : null)
									)
								)) : null), 
								((isset($requestBody['data']['searchUseCase']['data']['project']['data']['searchEngine']) && !is_null($requestBody['data']['searchUseCase']['data']['project']['data']['searchEngine'])) ? (new SearchEngineResponse(
									$this->apiClient, 
									new SearchEngine(
										$this->apiClient, 
										$requestBody['data']['searchUseCase']['data']['project']['data']['searchEngine']['data']['id'], 
										$requestBody['data']['searchUseCase']['data']['project']['data']['searchEngine']['data']['name'], 
										$requestBody['data']['searchUseCase']['data']['project']['data']['searchEngine']['data']['class_name'], 
										$requestBody['data']['searchUseCase']['data']['project']['data']['searchEngine']['data']['created_at'], 
										$requestBody['data']['searchUseCase']['data']['project']['data']['searchEngine']['data']['updated_at'], 
										(isset($requestBody['data']['searchUseCase']['data']['project']['data']['searchEngine']['data']['projects_count']) ? $requestBody['data']['searchUseCase']['data']['project']['data']['searchEngine']['data']['projects_count'] : null)
									)
								)) : null)
							)
						)) : null)
					)
				)) : null), 
				((isset($requestBody['data']['dataStreamField']) && !is_null($requestBody['data']['dataStreamField'])) ? (new DataStreamFieldResponse(
					$this->apiClient, 
					new DataStreamField(
						$this->apiClient, 
						$requestBody['data']['dataStreamField']['data']['id'], 
						$requestBody['data']['dataStreamField']['data']['data_stream_id'], 
						$requestBody['data']['dataStreamField']['data']['name'], 
						$requestBody['data']['dataStreamField']['data']['path'], 
						$requestBody['data']['dataStreamField']['data']['versioned'], 
						$requestBody['data']['dataStreamField']['data']['searchable'], 
						$requestBody['data']['dataStreamField']['data']['to_retrieve'], 
						$requestBody['data']['dataStreamField']['data']['created_at'], 
						$requestBody['data']['dataStreamField']['data']['updated_at'], 
						((isset($requestBody['data']['dataStreamField']['data']['dataStream']) && !is_null($requestBody['data']['dataStreamField']['data']['dataStream'])) ? (new DataStreamResponse(
							$this->apiClient, 
							new DataStream(
								$this->apiClient, 
								$requestBody['data']['dataStreamField']['data']['dataStream']['data']['id'], 
								$requestBody['data']['dataStreamField']['data']['dataStream']['data']['data_stream_decoder_id'], 
								$requestBody['data']['dataStreamField']['data']['dataStream']['data']['name'], 
								$requestBody['data']['dataStreamField']['data']['dataStream']['data']['feed_url'], 
								$requestBody['data']['dataStreamField']['data']['dataStream']['data']['basic_auth_user'], 
								$requestBody['data']['dataStreamField']['data']['dataStream']['data']['basic_auth_password'], 
								$requestBody['data']['dataStreamField']['data']['dataStream']['data']['created_at'], 
								$requestBody['data']['dataStreamField']['data']['dataStream']['data']['updated_at'], 
								((isset($requestBody['data']['dataStreamField']['data']['dataStream']['data']['project']) && !is_null($requestBody['data']['dataStreamField']['data']['dataStream']['data']['project'])) ? (new ProjectResponse(
									$this->apiClient, 
									new Project(
										$this->apiClient, 
										$requestBody['data']['dataStreamField']['data']['dataStream']['data']['project']['data']['id'], 
										$requestBody['data']['dataStreamField']['data']['dataStream']['data']['project']['data']['search_engine_id'], 
										$requestBody['data']['dataStreamField']['data']['dataStream']['data']['project']['data']['data_stream_id'], 
										$requestBody['data']['dataStreamField']['data']['dataStream']['data']['project']['data']['name'], 
										$requestBody['data']['dataStreamField']['data']['dataStream']['data']['project']['data']['created_at'], 
										$requestBody['data']['dataStreamField']['data']['dataStream']['data']['project']['data']['updated_at'], 
										null, 
										((isset($requestBody['data']['dataStreamField']['data']['dataStream']['data']['project']['data']['searchEngine']) && !is_null($requestBody['data']['dataStreamField']['data']['dataStream']['data']['project']['data']['searchEngine'])) ? (new SearchEngineResponse(
											$this->apiClient, 
											new SearchEngine(
												$this->apiClient, 
												$requestBody['data']['dataStreamField']['data']['dataStream']['data']['project']['data']['searchEngine']['data']['id'], 
												$requestBody['data']['dataStreamField']['data']['dataStream']['data']['project']['data']['searchEngine']['data']['name'], 
												$requestBody['data']['dataStreamField']['data']['dataStream']['data']['project']['data']['searchEngine']['data']['class_name'], 
												$requestBody['data']['dataStreamField']['data']['dataStream']['data']['project']['data']['searchEngine']['data']['created_at'], 
												$requestBody['data']['dataStreamField']['data']['dataStream']['data']['project']['data']['searchEngine']['data']['updated_at'], 
												(isset($requestBody['data']['dataStreamField']['data']['dataStream']['data']['project']['data']['searchEngine']['data']['projects_count']) ? $requestBody['data']['dataStreamField']['data']['dataStream']['data']['project']['data']['searchEngine']['data']['projects_count'] : null)
											)
										)) : null)
									)
								)) : null), 
								((isset($requestBody['data']['dataStreamField']['data']['dataStream']['data']['dataStreamDecoder']) && !is_null($requestBody['data']['dataStreamField']['data']['dataStream']['data']['dataStreamDecoder'])) ? (new DataStreamDecoderResponse(
									$this->apiClient, 
									new DataStreamDecoder(
										$this->apiClient, 
										$requestBody['data']['dataStreamField']['data']['dataStream']['data']['dataStreamDecoder']['data']['id'], 
										$requestBody['data']['dataStreamField']['data']['dataStream']['data']['dataStreamDecoder']['data']['name'], 
										$requestBody['data']['dataStreamField']['data']['dataStream']['data']['dataStreamDecoder']['data']['class_name'], 
										$requestBody['data']['dataStreamField']['data']['dataStream']['data']['dataStreamDecoder']['data']['file_mime_type'], 
										$requestBody['data']['dataStreamField']['data']['dataStream']['data']['dataStreamDecoder']['data']['created_at'], 
										$requestBody['data']['dataStreamField']['data']['dataStream']['data']['dataStreamDecoder']['data']['updated_at']
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
	 * Delete specified search use case field
	 * 
	 * Excepted HTTP code : 204
	 * 
	 * @param string $searchUseCaseId Search use case UUID
	 * @param string $dataStreamFieldId Data stream field UUID
	 * 
	 * @return ErrorResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function delete($searchUseCaseId, $dataStreamFieldId)
	{
		$routePath = '/api/searchUseCaseField/{searchUseCaseId},{dataStreamFieldId}';

		$pathReplacements = [
			'{searchUseCaseId}' => $searchUseCaseId,
			'{dataStreamFieldId}' => $dataStreamFieldId,
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
