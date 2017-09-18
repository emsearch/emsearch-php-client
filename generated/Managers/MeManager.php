<?php

namespace Emsearch\Api\Managers;

use Emsearch\Api\ApiClient;
use Emsearch\Api\Exceptions\UnexpectedResponseException;
use Emsearch\Api\Resources\UserResponse;
use Emsearch\Api\Resources\ErrorResponse;
use Emsearch\Api\Resources\ProjectListResponse;
use Emsearch\Api\Resources\User;
use Emsearch\Api\Resources\DataStreamResponse;
use Emsearch\Api\Resources\DataStream;
use Emsearch\Api\Resources\ProjectResponse;
use Emsearch\Api\Resources\DataStreamDecoderResponse;
use Emsearch\Api\Resources\DataStreamDecoder;
use Emsearch\Api\Resources\SearchEngineResponse;
use Emsearch\Api\Resources\SearchEngine;
use Emsearch\Api\Resources\Project;
use Emsearch\Api\Resources\Meta;
use Emsearch\Api\Resources\Pagination;

/**
 * Me manager class
 * 
 * @package Emsearch\Api\Managers
 */
class MeManager 
{
	/**
	 * API client
	 *
	 * @var ApiClient
	 */
	protected $apiClient;

	/**
	 * Me manager class constructor
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
	 * Get current user
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @return UserResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function getUser()
	{
		$routeUrl = '/api/me';

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

		$response = new UserResponse(
			$this->apiClient, 
			new User(
				$this->apiClient, 
				$requestBody['data']['id'], 
				$requestBody['data']['user_group_id'], 
				$requestBody['data']['name'], 
				$requestBody['data']['email'], 
				$requestBody['data']['created_at'], 
				$requestBody['data']['updated_at']
			)
		);

		return $response;
	}
	
	/**
	 * Current user project list
	 * 
	 * You can specify a GET parameter `user_role_id` to filter results.
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $user_role_id
	 * @param string $include Include responses : {include1},{include2,{include3}[...]
	 * @param string $search Search words
	 * @param int $page Format: int32. Pagination : Page number
	 * @param int $limit Format: int32. Pagination : Maximum entries per page
	 * @param string $order_by Order by : {field},[asc|desc]
	 * 
	 * @return ProjectListResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function getProjects($user_role_id = null, $include = null, $search = null, $page = null, $limit = null, $order_by = null)
	{
		$routeUrl = '/api/me/project';

		$queryParameters = [];

		if (!is_null($user_role_id)) {
			$queryParameters['user_role_id'] = $user_role_id;
		}

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

		$response = new ProjectListResponse(
			$this->apiClient, 
			array_map(function($data) {
				return new Project(
					$this->apiClient, 
					$data['id'], 
					$data['search_engine_id'], 
					$data['data_stream_id'], 
					$data['name'], 
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
							$data['dataStream']['data']['created_at'], 
							$data['dataStream']['data']['updated_at'], 
							((isset($data['dataStream']['data']['project']) && !is_null($data['dataStream']['data']['project'])) ? (new ProjectResponse(
								$this->apiClient, 
								null
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
					)) : null), 
					((isset($data['searchEngine']) && !is_null($data['searchEngine'])) ? (new SearchEngineResponse(
						$this->apiClient, 
						new SearchEngine(
							$this->apiClient, 
							$data['searchEngine']['data']['id'], 
							$data['searchEngine']['data']['name'], 
							$data['searchEngine']['data']['class_name'], 
							$data['searchEngine']['data']['created_at'], 
							$data['searchEngine']['data']['updated_at'], 
							(isset($data['searchEngine']['data']['projects_count']) ? $data['projects_count'] : null)
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
