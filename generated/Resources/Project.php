<?php

namespace emsearch\Api\Resources;

use emsearch\Api\ApiClient;
use emsearch\Api\Exceptions\UnexpectedResponseException;

/**
 * Project resource class
 * 
 * @package emsearch\Api\Resources
 */
class Project 
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
	public $search_engine_id;

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
	 * Project resource class constructor
	 * 
	 * @param ApiClient $apiClient API Client to use for this manager requests
	 * @param string $id Format: uuid.
	 * @param string $search_engine_id Format: uuid.
	 * @param string $data_stream_id Format: uuid.
	 * @param string $name
	 * @param string $created_at Format: date-time.
	 * @param string $updated_at Format: date-time.
	 */
	public function __construct(ApiClient $apiClient, $id = null, $search_engine_id = null, $data_stream_id = null, $name = null, $created_at = null, $updated_at = null)
	{
		$this->apiClient = $apiClient;
		$this->id = $id;
		$this->search_engine_id = $search_engine_id;
		$this->data_stream_id = $data_stream_id;
		$this->name = $name;
		$this->created_at = $created_at;
		$this->updated_at = $updated_at;
	}
	/**
	 * Update a specified project
	 * 
	 * Excepted HTTP code : 201
	 * 
	 * @param string $search_engine_id Format: uuid.
	 * @param string $name
	 * @param string $data_stream_id Format: uuid.
	 * 
	 * @return ProjectResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function update($search_engine_id, $name, $data_stream_id = null)
	{
		$path = '/api/project/{projectId}';

		$pathReplacements = [
			'{projectId}' => $this->id,
		];

		$url = str_replace(array_keys($pathReplacements), array_values($pathReplacements), $path);

		$bodyParameters = [];
		$bodyParameters['search_engine_id'] = $search_engine_id;
		$bodyParameters['name'] = $name;

		if (!is_null($data_stream_id)) {
			$bodyParameters['data_stream_id'] = $data_stream_id;
		}

		$requestOptions = [];
		$requestOptions['form_params'] = $bodyParameters;

		$request = $this->apiClient->getHttpClient()->request('patch', $url, $requestOptions);

		if ($request->getStatusCode() != 201) {
			$requestBody = json_decode((string) $request->getBody(), true);

			$apiExceptionResponse = new ErrorResponse(
				$this->apiClient, 
				$requestBody['message'], 
				$requestBody['errors'], 
				$requestBody['status_code'], 
				$requestBody['debug']
			);
	
			throw new UnexpectedResponseException($request->getStatusCode(), 201, $request, $apiExceptionResponse);
		}

		$requestBody = json_decode((string) $request->getBody(), true);

		$response = new ProjectResponse(
			$this->apiClient, 
			new Project(
				$this->apiClient, 
				$requestBody['data']['id'], 
				$requestBody['data']['search_engine_id'], 
				$requestBody['data']['data_stream_id'], 
				$requestBody['data']['name'], 
				$requestBody['data']['created_at'], 
				$requestBody['data']['updated_at']
			)
		);

		return $response;
	}
	
	/**
	 * Delete specified project
	 * 
	 * All relationships between the project and his users will be automatically deleted too.<br />
	 * The project sync items will be automatically deleted too.<br />
	 * The project data stream will be automatically deleted too, if exists.
	 * <aside class="notice">Only <code>Owner</code> of project is allowed to delete it.</aside>
	 * 
	 * Excepted HTTP code : 204
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function delete()
	{
		$path = '/api/project/{projectId}';

		$pathReplacements = [
			'{projectId}' => $this->id,
		];

		$url = str_replace(array_keys($pathReplacements), array_values($pathReplacements), $path);

		$requestOptions = [];

		$request = $this->apiClient->getHttpClient()->request('delete', $url, $requestOptions);

		if ($request->getStatusCode() != 204) {
			throw new UnexpectedResponseException($request->getStatusCode(), 204, $request);
		}
	}
	
	/**
	 * Show project data stream
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @return DataStreamResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function getDataStream()
	{
		$path = '/api/project/{projectId}/dataStream';

		$pathReplacements = [
			'{projectId}' => $this->id,
		];

		$url = str_replace(array_keys($pathReplacements), array_values($pathReplacements), $path);

		$requestOptions = [];

		$request = $this->apiClient->getHttpClient()->request('get', $url, $requestOptions);

		if ($request->getStatusCode() != 200) {
			$requestBody = json_decode((string) $request->getBody(), true);

			$apiExceptionResponse = new ErrorResponse(
				$this->apiClient, 
				$requestBody['message'], 
				$requestBody['errors'], 
				$requestBody['status_code'], 
				$requestBody['debug']
			);
	
			throw new UnexpectedResponseException($request->getStatusCode(), 200, $request, $apiExceptionResponse);
		}

		$requestBody = json_decode((string) $request->getBody(), true);

		$response = new DataStreamResponse(
			$this->apiClient, 
			new DataStream(
				$this->apiClient, 
				$requestBody['data']['id'], 
				$requestBody['data']['data_stream_decoder_id'], 
				$requestBody['data']['name'], 
				$requestBody['data']['feed_url'], 
				$requestBody['data']['created_at'], 
				$requestBody['data']['updated_at']
			)
		);

		return $response;
	}
	
	/**
	 * Update the project data stream
	 * 
	 * Excepted HTTP code : 201
	 * 
	 * @param string $data_stream_decoder_id Format: uuid.
	 * @param string $name
	 * @param string $feed_url Format: url.
	 * 
	 * @return DataStreamResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function updateDataStream($data_stream_decoder_id, $name, $feed_url)
	{
		$path = '/api/project/{projectId}/dataStream';

		$pathReplacements = [
			'{projectId}' => $this->id,
		];

		$url = str_replace(array_keys($pathReplacements), array_values($pathReplacements), $path);

		$bodyParameters = [];
		$bodyParameters['data_stream_decoder_id'] = $data_stream_decoder_id;
		$bodyParameters['name'] = $name;
		$bodyParameters['feed_url'] = $feed_url;

		$requestOptions = [];
		$requestOptions['form_params'] = $bodyParameters;

		$request = $this->apiClient->getHttpClient()->request('patch', $url, $requestOptions);

		if ($request->getStatusCode() != 201) {
			$requestBody = json_decode((string) $request->getBody(), true);

			$apiExceptionResponse = new ErrorResponse(
				$this->apiClient, 
				$requestBody['message'], 
				$requestBody['errors'], 
				$requestBody['status_code'], 
				$requestBody['debug']
			);
	
			throw new UnexpectedResponseException($request->getStatusCode(), 201, $request, $apiExceptionResponse);
		}

		$requestBody = json_decode((string) $request->getBody(), true);

		$response = new DataStreamResponse(
			$this->apiClient, 
			new DataStream(
				$this->apiClient, 
				$requestBody['data']['id'], 
				$requestBody['data']['data_stream_decoder_id'], 
				$requestBody['data']['name'], 
				$requestBody['data']['feed_url'], 
				$requestBody['data']['created_at'], 
				$requestBody['data']['updated_at']
			)
		);

		return $response;
	}
	
	/**
	 * Create and store the project data stream
	 * 
	 * Only one data stream per project is allowed.
	 * 
	 * Excepted HTTP code : 201
	 * 
	 * @param string $data_stream_decoder_id Format: uuid.
	 * @param string $name
	 * @param string $feed_url Format: url.
	 * 
	 * @return DataStreamResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function createDataStream($data_stream_decoder_id, $name, $feed_url)
	{
		$path = '/api/project/{projectId}/dataStream';

		$pathReplacements = [
			'{projectId}' => $this->id,
		];

		$url = str_replace(array_keys($pathReplacements), array_values($pathReplacements), $path);

		$bodyParameters = [];
		$bodyParameters['data_stream_decoder_id'] = $data_stream_decoder_id;
		$bodyParameters['name'] = $name;
		$bodyParameters['feed_url'] = $feed_url;

		$requestOptions = [];
		$requestOptions['form_params'] = $bodyParameters;

		$request = $this->apiClient->getHttpClient()->request('post', $url, $requestOptions);

		if ($request->getStatusCode() != 201) {
			$requestBody = json_decode((string) $request->getBody(), true);

			$apiExceptionResponse = new ErrorResponse(
				$this->apiClient, 
				$requestBody['message'], 
				$requestBody['errors'], 
				$requestBody['status_code'], 
				$requestBody['debug']
			);
	
			throw new UnexpectedResponseException($request->getStatusCode(), 201, $request, $apiExceptionResponse);
		}

		$requestBody = json_decode((string) $request->getBody(), true);

		$response = new DataStreamResponse(
			$this->apiClient, 
			new DataStream(
				$this->apiClient, 
				$requestBody['data']['id'], 
				$requestBody['data']['data_stream_decoder_id'], 
				$requestBody['data']['name'], 
				$requestBody['data']['feed_url'], 
				$requestBody['data']['created_at'], 
				$requestBody['data']['updated_at']
			)
		);

		return $response;
	}
	
	/**
	 * Delete the project data stream
	 * 
	 * Excepted HTTP code : 204
	 * 
	 * @return ErrorResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function deleteDataStream()
	{
		$path = '/api/project/{projectId}/dataStream';

		$pathReplacements = [
			'{projectId}' => $this->id,
		];

		$url = str_replace(array_keys($pathReplacements), array_values($pathReplacements), $path);

		$requestOptions = [];

		$request = $this->apiClient->getHttpClient()->request('delete', $url, $requestOptions);

		if ($request->getStatusCode() != 204) {
			$requestBody = json_decode((string) $request->getBody(), true);

			$apiExceptionResponse = new ErrorResponse(
				$this->apiClient, 
				$requestBody['message'], 
				$requestBody['errors'], 
				$requestBody['status_code'], 
				$requestBody['debug']
			);
	
			throw new UnexpectedResponseException($request->getStatusCode(), 204, $request, $apiExceptionResponse);
		}

		$requestBody = json_decode((string) $request->getBody(), true);

		$response = new ErrorResponse(
			$this->apiClient, 
			$requestBody['message'], 
			$requestBody['errors'], 
			$requestBody['status_code'], 
			$requestBody['debug']
		);

		return $response;
	}
}
