<?php

namespace Emsearch\Api\Resources;

use Emsearch\Api\ApiClient;
use Emsearch\Api\Exceptions\UnexpectedResponseException;

/**
 * SearchUseCaseField resource class
 * 
 * @package Emsearch\Api\Resources
 */
class SearchUseCaseField 
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
	public $search_use_case_id;

	/**
	 * Format: uuid.
	 * 
	 * @var string
	 */
	public $data_stream_field_id;

	/**
	 * @var string
	 */
	public $name;

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
	 * @var SearchUseCaseResponse
	 */
	public $searchUseCase;

	/**
	 * @var DataStreamFieldResponse
	 */
	public $dataStreamField;

	/**
	 * SearchUseCaseField resource class constructor
	 * 
	 * @param ApiClient $apiClient API Client to use for this manager requests
	 * @param string $search_use_case_id Format: uuid.
	 * @param string $data_stream_field_id Format: uuid.
	 * @param string $name
	 * @param boolean $searchable
	 * @param boolean $to_retrieve
	 * @param string $created_at Format: date-time.
	 * @param string $updated_at Format: date-time.
	 * @param SearchUseCaseResponse $searchUseCase
	 * @param DataStreamFieldResponse $dataStreamField
	 */
	public function __construct(ApiClient $apiClient, $search_use_case_id = null, $data_stream_field_id = null, $name = null, $searchable = null, $to_retrieve = null, $created_at = null, $updated_at = null, $searchUseCase = null, $dataStreamField = null)
	{
		$this->apiClient = $apiClient;
		$this->search_use_case_id = $search_use_case_id;
		$this->data_stream_field_id = $data_stream_field_id;
		$this->name = $name;
		$this->searchable = $searchable;
		$this->to_retrieve = $to_retrieve;
		$this->created_at = $created_at;
		$this->updated_at = $updated_at;
		$this->searchUseCase = $searchUseCase;
		$this->dataStreamField = $dataStreamField;
	}
	/**
	 * Update a specified search use case field
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
	public function update($search_use_case_id, $data_stream_field_id, $name, $searchable, $to_retrieve)
	{
		$routePath = '/api/searchUseCaseField/{searchUseCaseId},{dataStreamFieldId}';

		$pathReplacements = [
			'{searchUseCaseId}' => $this->search_use_case_id,
			'{dataStreamFieldId}' => $this->data_stream_field_id,
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
	 * @return ErrorResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function delete()
	{
		$routePath = '/api/searchUseCaseField/{searchUseCaseId},{dataStreamFieldId}';

		$pathReplacements = [
			'{searchUseCaseId}' => $this->search_use_case_id,
			'{dataStreamFieldId}' => $this->data_stream_field_id,
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
