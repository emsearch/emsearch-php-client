<?php

namespace emsearch\Api\Resources;

use emsearch\Api\ApiClient;
use emsearch\Api\Exceptions\UnexpectedResponseException;

/**
 * DataStreamField resource class
 * 
 * @package emsearch\Api\Resources
 */
class DataStreamField 
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
	public $data_stream_id;

	/**
	 * @var string
	 */
	public $name;

	/**
	 * @var string
	 */
	public $path;

	/**
	 * @var boolean
	 */
	public $versioned;

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
	 * @var DataStreamResponse
	 */
	public $dataStream;

	/**
	 * DataStreamField resource class constructor
	 * 
	 * @param ApiClient $apiClient API Client to use for this manager requests
	 * @param string $id Format: uuid.
	 * @param string $data_stream_id Format: uuid.
	 * @param string $name
	 * @param string $path
	 * @param boolean $versioned
	 * @param boolean $searchable
	 * @param boolean $to_retrieve
	 * @param string $created_at Format: date-time.
	 * @param string $updated_at Format: date-time.
	 * @param DataStreamResponse $dataStream
	 */
	public function __construct(ApiClient $apiClient, $id = null, $data_stream_id = null, $name = null, $path = null, $versioned = null, $searchable = null, $to_retrieve = null, $created_at = null, $updated_at = null, $dataStream = null)
	{
		$this->apiClient = $apiClient;
		$this->id = $id;
		$this->data_stream_id = $data_stream_id;
		$this->name = $name;
		$this->path = $path;
		$this->versioned = $versioned;
		$this->searchable = $searchable;
		$this->to_retrieve = $to_retrieve;
		$this->created_at = $created_at;
		$this->updated_at = $updated_at;
		$this->dataStream = $dataStream;
	}
	/**
	 * Update a data stream field
	 * 
	 * Excepted HTTP code : 201
	 * 
	 * @param string $data_stream_id Format: uuid.
	 * @param string $name
	 * @param string $path
	 * @param boolean $versioned
	 * @param boolean $searchable
	 * @param boolean $to_retrieve
	 * 
	 * @return DataStreamFieldResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function update($data_stream_id, $name, $path, $versioned, $searchable, $to_retrieve)
	{
		$routePath = '/api/dataStreamField/{dataStreamFieldId}';

		$pathReplacements = [
			'{dataStreamFieldId}' => $this->id,
		];

		$routeUrl = str_replace(array_keys($pathReplacements), array_values($pathReplacements), $routePath);

		$bodyParameters = [];
		$bodyParameters['data_stream_id'] = $data_stream_id;
		$bodyParameters['name'] = $name;
		$bodyParameters['path'] = $path;
		$bodyParameters['versioned'] = $versioned;
		$bodyParameters['searchable'] = $searchable;
		$bodyParameters['to_retrieve'] = $to_retrieve;

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

		$response = new DataStreamFieldResponse(
			$this->apiClient, 
			new DataStreamField(
				$this->apiClient, 
				$requestBody['data']['id'], 
				$requestBody['data']['data_stream_id'], 
				$requestBody['data']['name'], 
				$requestBody['data']['path'], 
				$requestBody['data']['versioned'], 
				$requestBody['data']['searchable'], 
				$requestBody['data']['to_retrieve'], 
				$requestBody['data']['created_at'], 
				$requestBody['data']['updated_at'], 
				((isset($requestBody['data']['dataStream']) && !is_null($requestBody['data']['dataStream'])) ? (new DataStreamResponse(
					$this->apiClient, 
					new DataStream(
						$this->apiClient, 
						$requestBody['data']['dataStream']['data']['id'], 
						$requestBody['data']['dataStream']['data']['data_stream_decoder_id'], 
						$requestBody['data']['dataStream']['data']['name'], 
						$requestBody['data']['dataStream']['data']['feed_url'], 
						$requestBody['data']['dataStream']['data']['created_at'], 
						$requestBody['data']['dataStream']['data']['updated_at'], 
						((isset($requestBody['data']['dataStream']['data']['project']) && !is_null($requestBody['data']['dataStream']['data']['project'])) ? (new ProjectResponse(
							$this->apiClient, 
							new Project(
								$this->apiClient, 
								$requestBody['data']['dataStream']['data']['project']['data']['id'], 
								$requestBody['data']['dataStream']['data']['project']['data']['search_engine_id'], 
								$requestBody['data']['dataStream']['data']['project']['data']['data_stream_id'], 
								$requestBody['data']['dataStream']['data']['project']['data']['name'], 
								$requestBody['data']['dataStream']['data']['project']['data']['created_at'], 
								$requestBody['data']['dataStream']['data']['project']['data']['updated_at'], 
								null, 
								((isset($requestBody['data']['dataStream']['data']['project']['data']['searchEngine']) && !is_null($requestBody['data']['dataStream']['data']['project']['data']['searchEngine'])) ? (new SearchEngineResponse(
									$this->apiClient, 
									new SearchEngine(
										$this->apiClient, 
										$requestBody['data']['dataStream']['data']['project']['data']['searchEngine']['data']['id'], 
										$requestBody['data']['dataStream']['data']['project']['data']['searchEngine']['data']['name'], 
										$requestBody['data']['dataStream']['data']['project']['data']['searchEngine']['data']['class_name'], 
										$requestBody['data']['dataStream']['data']['project']['data']['searchEngine']['data']['created_at'], 
										$requestBody['data']['dataStream']['data']['project']['data']['searchEngine']['data']['updated_at'], 
										(isset($requestBody['data']['dataStream']['data']['project']['data']['searchEngine']['data']['projects_count']) ? $requestBody['data']['dataStream']['data']['project']['data']['searchEngine']['data']['projects_count'] : null)
									)
								)) : null)
							)
						)) : null), 
						((isset($requestBody['data']['dataStream']['data']['dataStreamDecoder']) && !is_null($requestBody['data']['dataStream']['data']['dataStreamDecoder'])) ? (new DataStreamDecoderResponse(
							$this->apiClient, 
							new DataStreamDecoder(
								$this->apiClient, 
								$requestBody['data']['dataStream']['data']['dataStreamDecoder']['data']['id'], 
								$requestBody['data']['dataStream']['data']['dataStreamDecoder']['data']['name'], 
								$requestBody['data']['dataStream']['data']['dataStreamDecoder']['data']['class_name'], 
								$requestBody['data']['dataStream']['data']['dataStreamDecoder']['data']['file_mime_type'], 
								$requestBody['data']['dataStream']['data']['dataStreamDecoder']['data']['created_at'], 
								$requestBody['data']['dataStream']['data']['dataStreamDecoder']['data']['updated_at']
							)
						)) : null)
					)
				)) : null)
			)
		);

		return $response;
	}
	
	/**
	 * Delete specified data stream field
	 * 
	 * Excepted HTTP code : 204
	 * 
	 * @return ErrorResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function delete()
	{
		$routePath = '/api/dataStreamField/{dataStreamFieldId}';

		$pathReplacements = [
			'{dataStreamFieldId}' => $this->id,
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
