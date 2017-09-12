<?php

namespace emsearch\Api\Resources;

use emsearch\Api\ApiClient;
use emsearch\Api\Exceptions\UnexpectedResponseException;

/**
 * DataStream resource class
 * 
 * @package emsearch\Api\Resources
 */
class DataStream 
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
	public $data_stream_decoder_id;

	/**
	 * @var string
	 */
	public $name;

	/**
	 * Format: url.
	 * 
	 * @var string
	 */
	public $feed_url;

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
	 * DataStream resource class constructor
	 * 
	 * @param ApiClient $apiClient API Client to use for this manager requests
	 * @param string $id Format: uuid.
	 * @param string $data_stream_decoder_id Format: uuid.
	 * @param string $name
	 * @param string $feed_url Format: url.
	 * @param string $created_at Format: date-time.
	 * @param string $updated_at Format: date-time.
	 */
	public function __construct(ApiClient $apiClient, $id = null, $data_stream_decoder_id = null, $name = null, $feed_url = null, $created_at = null, $updated_at = null)
	{
		$this->apiClient = $apiClient;
		$this->id = $id;
		$this->data_stream_decoder_id = $data_stream_decoder_id;
		$this->name = $name;
		$this->feed_url = $feed_url;
		$this->created_at = $created_at;
		$this->updated_at = $updated_at;
	}
	/**
	 * Update a data stream
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
	public function update($data_stream_decoder_id, $name, $feed_url)
	{
		$path = '/api/dataStream/{dataStreamId}';

		$pathReplacements = [
			'{dataStreamId}' => $this->id,
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
	 * @return ErrorResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function delete()
	{
		$path = '/api/dataStream/{dataStreamId}';

		$pathReplacements = [
			'{dataStreamId}' => $this->id,
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
