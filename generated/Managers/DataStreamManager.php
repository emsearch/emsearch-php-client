<?php

namespace emsearch\Api\Managers;

use emsearch\Api\ApiClient;
use emsearch\Api\Exceptions\UnexpectedResponseException;
use emsearch\Api\Resources\DataStreamListResponse;
use emsearch\Api\Resources\ErrorResponse;
use emsearch\Api\Resources\DataStreamResponse;
use emsearch\Api\Resources\DataStream;
use emsearch\Api\Resources\Meta;
use emsearch\Api\Resources\Pagination;

/**
 * DataStream manager class
 * 
 * @package emsearch\Api\Managers
 */
class DataStreamManager 
{
	/**
	 * API client
	 *
	 * @var ApiClient
	 */
	protected $apiClient;

	/**
	 * DataStream manager class constructor
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
	 * Show data stream list
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @return DataStreamListResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function all()
	{
		$url = '/api/dataStream';

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

		$response = new DataStreamListResponse(
			$this->apiClient, 
			array_map(function($data) {
				return new DataStream(
					$this->apiClient, 
					$data['id'], 
					$data['data_stream_decoder_id'], 
					$data['name'], 
					$data['feed_url'], 
					$data['created_at'], 
					$data['updated_at']
				); 
			}, $requestBody['data']), 
			new Meta(
				$this->apiClient, 
				new Pagination(
					$this->apiClient, 
					$requestBody['meta']['pagination']['total'], 
					$requestBody['meta']['pagination']['count'], 
					$requestBody['meta']['pagination']['per_page'], 
					$requestBody['meta']['pagination']['current_page'], 
					$requestBody['meta']['pagination']['total_pages'], 
					$requestBody['meta']['pagination']['links']
				)
			)
		);

		return $response;
	}
	
	/**
	 * Create and store new data stream
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
	public function create($data_stream_decoder_id, $name, $feed_url)
	{
		$url = '/api/dataStream';

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
	 * Get specified data stream
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $dataStreamId Data stream UUID
	 * 
	 * @return DataStreamResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function get($dataStreamId)
	{
		$path = '/api/dataStream/{dataStreamId}';

		$pathReplacements = [
			'{dataStreamId}' => $dataStreamId,
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
	 * Update a data stream
	 * 
	 * Excepted HTTP code : 201
	 * 
	 * @param string $dataStreamId Data stream UUID
	 * @param string $data_stream_decoder_id Format: uuid.
	 * @param string $name
	 * @param string $feed_url Format: url.
	 * 
	 * @return DataStreamResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function update($dataStreamId, $data_stream_decoder_id, $name, $feed_url)
	{
		$path = '/api/dataStream/{dataStreamId}';

		$pathReplacements = [
			'{dataStreamId}' => $dataStreamId,
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
	 * Delete specified data stream
	 * 
	 * Excepted HTTP code : 204
	 * 
	 * @param string $dataStreamId Data stream UUID
	 * 
	 * @return ErrorResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function delete($dataStreamId)
	{
		$path = '/api/dataStream/{dataStreamId}';

		$pathReplacements = [
			'{dataStreamId}' => $dataStreamId,
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
