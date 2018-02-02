<?php

namespace Emsearch\Api\Resources;

use Emsearch\Api\ApiClient;
use Emsearch\Api\Exceptions\UnexpectedResponseException;

/**
 * DataStream resource class
 * 
 * @package Emsearch\Api\Resources
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
	 * Format: url.
	 * 
	 * @var string
	 */
	public $basic_auth_user;

	/**
	 * Format: url.
	 * 
	 * @var string
	 */
	public $basic_auth_password;

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
	 * @var ProjectResponse
	 */
	public $project;

	/**
	 * @var DataStreamDecoderResponse
	 */
	public $dataStreamDecoder;

	/**
	 * DataStream resource class constructor
	 * 
	 * @param ApiClient $apiClient API Client to use for this manager requests
	 * @param string $id Format: uuid.
	 * @param string $data_stream_decoder_id Format: uuid.
	 * @param string $name
	 * @param string $feed_url Format: url.
	 * @param string $basic_auth_user Format: url.
	 * @param string $basic_auth_password Format: url.
	 * @param string $created_at Format: date-time.
	 * @param string $updated_at Format: date-time.
	 * @param ProjectResponse $project
	 * @param DataStreamDecoderResponse $dataStreamDecoder
	 */
	public function __construct(ApiClient $apiClient, $id = null, $data_stream_decoder_id = null, $name = null, $feed_url = null, $basic_auth_user = null, $basic_auth_password = null, $created_at = null, $updated_at = null, $project = null, $dataStreamDecoder = null)
	{
		$this->apiClient = $apiClient;
		$this->id = $id;
		$this->data_stream_decoder_id = $data_stream_decoder_id;
		$this->name = $name;
		$this->feed_url = $feed_url;
		$this->basic_auth_user = $basic_auth_user;
		$this->basic_auth_password = $basic_auth_password;
		$this->created_at = $created_at;
		$this->updated_at = $updated_at;
		$this->project = $project;
		$this->dataStreamDecoder = $dataStreamDecoder;
	}
	/**
	 * Update a data stream
	 * 
	 * Excepted HTTP code : 201
	 * 
	 * @param string $data_stream_decoder_id Format: uuid.
	 * @param string $name
	 * @param string $feed_url Format: url.
	 * @param string $basic_auth_user
	 * @param string $basic_auth_password
	 * 
	 * @return DataStreamResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function update($data_stream_decoder_id, $name, $feed_url, $basic_auth_user = null, $basic_auth_password = null)
	{
		$routePath = '/api/dataStream/{dataStreamId}';

		$pathReplacements = [
			'{dataStreamId}' => $this->id,
		];

		$routeUrl = str_replace(array_keys($pathReplacements), array_values($pathReplacements), $routePath);

		$bodyParameters = [];
		$bodyParameters['data_stream_decoder_id'] = $data_stream_decoder_id;
		$bodyParameters['name'] = $name;
		$bodyParameters['feed_url'] = $feed_url;

		if (!is_null($basic_auth_user)) {
			$bodyParameters['basic_auth_user'] = $basic_auth_user;
		}

		if (!is_null($basic_auth_password)) {
			$bodyParameters['basic_auth_password'] = $basic_auth_password;
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

		$response = new DataStreamResponse(
			$this->apiClient, 
			new DataStream(
				$this->apiClient, 
				$requestBody['data']['id'], 
				$requestBody['data']['data_stream_decoder_id'], 
				$requestBody['data']['name'], 
				$requestBody['data']['feed_url'], 
				$requestBody['data']['basic_auth_user'], 
				$requestBody['data']['basic_auth_password'], 
				$requestBody['data']['created_at'], 
				$requestBody['data']['updated_at'], 
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
						null, 
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
				)) : null), 
				((isset($requestBody['data']['dataStreamDecoder']) && !is_null($requestBody['data']['dataStreamDecoder'])) ? (new DataStreamDecoderResponse(
					$this->apiClient, 
					new DataStreamDecoder(
						$this->apiClient, 
						$requestBody['data']['dataStreamDecoder']['data']['id'], 
						$requestBody['data']['dataStreamDecoder']['data']['name'], 
						$requestBody['data']['dataStreamDecoder']['data']['class_name'], 
						$requestBody['data']['dataStreamDecoder']['data']['file_mime_type'], 
						$requestBody['data']['dataStreamDecoder']['data']['created_at'], 
						$requestBody['data']['dataStreamDecoder']['data']['updated_at']
					)
				)) : null)
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
		$routePath = '/api/dataStream/{dataStreamId}';

		$pathReplacements = [
			'{dataStreamId}' => $this->id,
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
	
	/**
	 * Data stream data stream field list
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $include Include responses : {include1},{include2,{include3}[...]
	 * @param string $search Search words
	 * @param int $page Format: int32. Pagination : Page number
	 * @param int $limit Format: int32. Pagination : Maximum entries per page
	 * @param string $order_by Order by : {field},[asc|desc]
	 * 
	 * @return DataStreamFieldListResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function getFields($include = null, $search = null, $page = null, $limit = null, $order_by = null)
	{
		$routePath = '/api/dataStream/{dataStreamPresetId}/dataStreamField';

		$pathReplacements = [
			'{dataStreamId}' => $this->id,
		];

		$routeUrl = str_replace(array_keys($pathReplacements), array_values($pathReplacements), $routePath);

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
				$requestBody['message'], 
				(isset($requestBody['errors']) ? $requestBody['errors'] : null), 
				(isset($requestBody['status_code']) ? $requestBody['status_code'] : null), 
				(isset($requestBody['debug']) ? $requestBody['debug'] : null)
			);

			throw new UnexpectedResponseException($request->getStatusCode(), 200, $request, $apiExceptionResponse);
		}

		$requestBody = json_decode((string) $request->getBody(), true);

		$response = new DataStreamFieldListResponse(
			$this->apiClient, 
			array_map(function($data) {
				return new DataStreamField(
					$this->apiClient, 
					$data['id'], 
					$data['data_stream_id'], 
					$data['name'], 
					$data['path'], 
					$data['versioned'], 
					$data['searchable'], 
					$data['to_retrieve'], 
					$data['created_at'], 
					$data['updated_at'], 
					((isset($data['dataStream']) && !is_null($data['dataStream'])) ? (new DataStreamResponse(
						$this->apiClient, 
						new DataStream(
							$this->apiClient, 
							$data['dataStream']['data']['id'], 
							$data['dataStream']['data']['data_stream_decoder_id'], 
							$data['dataStream']['data']['name'], 
							$data['dataStream']['data']['feed_url'], 
							$data['dataStream']['data']['basic_auth_user'], 
							$data['dataStream']['data']['basic_auth_password'], 
							$data['dataStream']['data']['created_at'], 
							$data['dataStream']['data']['updated_at'], 
							((isset($data['dataStream']['data']['project']) && !is_null($data['dataStream']['data']['project'])) ? (new ProjectResponse(
								$this->apiClient, 
								new Project(
									$this->apiClient, 
									$data['dataStream']['data']['project']['data']['id'], 
									$data['dataStream']['data']['project']['data']['search_engine_id'], 
									$data['dataStream']['data']['project']['data']['data_stream_id'], 
									$data['dataStream']['data']['project']['data']['name'], 
									$data['dataStream']['data']['project']['data']['created_at'], 
									$data['dataStream']['data']['project']['data']['updated_at'], 
									null, 
									((isset($data['dataStream']['data']['project']['data']['searchEngine']) && !is_null($data['dataStream']['data']['project']['data']['searchEngine'])) ? (new SearchEngineResponse(
										$this->apiClient, 
										new SearchEngine(
											$this->apiClient, 
											$data['dataStream']['data']['project']['data']['searchEngine']['data']['id'], 
											$data['dataStream']['data']['project']['data']['searchEngine']['data']['name'], 
											$data['dataStream']['data']['project']['data']['searchEngine']['data']['class_name'], 
											$data['dataStream']['data']['project']['data']['searchEngine']['data']['created_at'], 
											$data['dataStream']['data']['project']['data']['searchEngine']['data']['updated_at'], 
											(isset($data['dataStream']['data']['project']['data']['searchEngine']['data']['projects_count']) ? $data['projects_count'] : null)
										)
									)) : null)
								)
							)) : null), 
							((isset($data['dataStream']['data']['dataStreamDecoder']) && !is_null($data['dataStream']['data']['dataStreamDecoder'])) ? (new DataStreamDecoderResponse(
								$this->apiClient, 
								new DataStreamDecoder(
									$this->apiClient, 
									$data['dataStream']['data']['dataStreamDecoder']['data']['id'], 
									$data['dataStream']['data']['dataStreamDecoder']['data']['name'], 
									$data['dataStream']['data']['dataStreamDecoder']['data']['class_name'], 
									$data['dataStream']['data']['dataStreamDecoder']['data']['file_mime_type'], 
									$data['dataStream']['data']['dataStreamDecoder']['data']['created_at'], 
									$data['dataStream']['data']['dataStreamDecoder']['data']['updated_at']
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
	 * Data stream relationship between data stream and i18n langs list
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $include Include responses : {include1},{include2,{include3}[...]
	 * @param string $search Search words
	 * @param int $page Format: int32. Pagination : Page number
	 * @param int $limit Format: int32. Pagination : Maximum entries per page
	 * @param string $order_by Order by : {field},[asc|desc]
	 * 
	 * @return DataStreamHasI18nLangListResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function getDataStreamHasI18nLangs($include = null, $search = null, $page = null, $limit = null, $order_by = null)
	{
		$routePath = '/api/dataStream/{dataStreamId}/dataStreamHasI18nLang';

		$pathReplacements = [
			'{dataStreamId}' => $this->id,
		];

		$routeUrl = str_replace(array_keys($pathReplacements), array_values($pathReplacements), $routePath);

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
				$requestBody['message'], 
				(isset($requestBody['errors']) ? $requestBody['errors'] : null), 
				(isset($requestBody['status_code']) ? $requestBody['status_code'] : null), 
				(isset($requestBody['debug']) ? $requestBody['debug'] : null)
			);

			throw new UnexpectedResponseException($request->getStatusCode(), 200, $request, $apiExceptionResponse);
		}

		$requestBody = json_decode((string) $request->getBody(), true);

		$response = new DataStreamHasI18nLangListResponse(
			$this->apiClient, 
			array_map(function($data) {
				return new DataStreamHasI18nLang(
					$this->apiClient, 
					$data['data_stream_id'], 
					$data['i18n_lang_id'], 
					$data['created_at'], 
					$data['updated_at']
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
	 * Data stream i18n lang list
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $search Search words
	 * @param int $page Format: int32. Pagination : Page number
	 * @param int $limit Format: int32. Pagination : Maximum entries per page
	 * @param string $order_by Order by : {field},[asc|desc]
	 * 
	 * @return I18nLangListResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function getI18nLangs($search = null, $page = null, $limit = null, $order_by = null)
	{
		$routePath = '/api/dataStream/{dataStreamId}/i18nLang';

		$pathReplacements = [
			'{dataStreamId}' => $this->id,
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
				$requestBody['message'], 
				(isset($requestBody['errors']) ? $requestBody['errors'] : null), 
				(isset($requestBody['status_code']) ? $requestBody['status_code'] : null), 
				(isset($requestBody['debug']) ? $requestBody['debug'] : null)
			);

			throw new UnexpectedResponseException($request->getStatusCode(), 200, $request, $apiExceptionResponse);
		}

		$requestBody = json_decode((string) $request->getBody(), true);

		$response = new I18nLangListResponse(
			$this->apiClient, 
			array_map(function($data) {
				return new I18nLang(
					$this->apiClient, 
					$data['id'], 
					$data['description']
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
	 * I18n lang data streams list
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $search Search words
	 * @param int $page Format: int32. Pagination : Page number
	 * @param int $limit Format: int32. Pagination : Maximum entries per page
	 * @param string $order_by Order by : {field},[asc|desc]
	 * 
	 * @return DataStreamListResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function getDataStreams($search = null, $page = null, $limit = null, $order_by = null)
	{
		$routePath = '/api/i18nLang/{i18nLangId}/dataStream';

		$pathReplacements = [
			'{i18nLangId}' => $i18nLangId,
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
				$requestBody['message'], 
				(isset($requestBody['errors']) ? $requestBody['errors'] : null), 
				(isset($requestBody['status_code']) ? $requestBody['status_code'] : null), 
				(isset($requestBody['debug']) ? $requestBody['debug'] : null)
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
					$data['basic_auth_user'], 
					$data['basic_auth_password'], 
					$data['created_at'], 
					$data['updated_at'], 
					((isset($data['project']) && !is_null($data['project'])) ? (new ProjectResponse(
						$this->apiClient, 
						new Project(
							$this->apiClient, 
							$data['project']['data']['id'], 
							$data['project']['data']['search_engine_id'], 
							$data['project']['data']['data_stream_id'], 
							$data['project']['data']['name'], 
							$data['project']['data']['created_at'], 
							$data['project']['data']['updated_at'], 
							((isset($data['project']['data']['dataStream']) && !is_null($data['project']['data']['dataStream'])) ? (new DataStreamResponse(
								$this->apiClient, 
								null
							)) : null), 
							((isset($data['project']['data']['searchEngine']) && !is_null($data['project']['data']['searchEngine'])) ? (new SearchEngineResponse(
								$this->apiClient, 
								new SearchEngine(
									$this->apiClient, 
									$data['project']['data']['searchEngine']['data']['id'], 
									$data['project']['data']['searchEngine']['data']['name'], 
									$data['project']['data']['searchEngine']['data']['class_name'], 
									$data['project']['data']['searchEngine']['data']['created_at'], 
									$data['project']['data']['searchEngine']['data']['updated_at'], 
									(isset($data['project']['data']['searchEngine']['data']['projects_count']) ? $data['projects_count'] : null)
								)
							)) : null)
						)
					)) : null), 
					((isset($data['dataStreamDecoder']) && !is_null($data['dataStreamDecoder'])) ? (new DataStreamDecoderResponse(
						$this->apiClient, 
						new DataStreamDecoder(
							$this->apiClient, 
							$data['dataStreamDecoder']['data']['id'], 
							$data['dataStreamDecoder']['data']['name'], 
							$data['dataStreamDecoder']['data']['class_name'], 
							$data['dataStreamDecoder']['data']['file_mime_type'], 
							$data['dataStreamDecoder']['data']['created_at'], 
							$data['dataStreamDecoder']['data']['updated_at']
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
	 * Data stream preset search use case preset list
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $search Search words
	 * @param int $page Format: int32. Pagination : Page number
	 * @param int $limit Format: int32. Pagination : Maximum entries per page
	 * @param string $order_by Order by : {field},[asc|desc]
	 * 
	 * @return SearchUseCasePresetListResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function getSearchUseCasePresets($search = null, $page = null, $limit = null, $order_by = null)
	{
		$routePath = '/api/dataStreamPreset/{dataStreamPresetId}/searchUseCasePreset';

		$pathReplacements = [
			'{dataStreamPresetId}' => $dataStreamPresetId,
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
	 * Data stream preset widget preset list
	 * 
	 * (Through the related search use case presets)
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $search Search words
	 * @param int $page Format: int32. Pagination : Page number
	 * @param int $limit Format: int32. Pagination : Maximum entries per page
	 * @param string $order_by Order by : {field},[asc|desc]
	 * 
	 * @return WidgetPresetListResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function getWidgetPresets($search = null, $page = null, $limit = null, $order_by = null)
	{
		$routePath = '/api/dataStreamPreset/{dataStreamPresetId}/widgetPreset';

		$pathReplacements = [
			'{dataStreamPresetId}' => $dataStreamPresetId,
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
				$requestBody['message'], 
				(isset($requestBody['errors']) ? $requestBody['errors'] : null), 
				(isset($requestBody['status_code']) ? $requestBody['status_code'] : null), 
				(isset($requestBody['debug']) ? $requestBody['debug'] : null)
			);

			throw new UnexpectedResponseException($request->getStatusCode(), 200, $request, $apiExceptionResponse);
		}

		$requestBody = json_decode((string) $request->getBody(), true);

		$response = new WidgetPresetListResponse(
			$this->apiClient, 
			array_map(function($data) {
				return new WidgetPreset(
					$this->apiClient, 
					$data['id'], 
					$data['search_use_case_preset_id'], 
					$data['name'], 
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
