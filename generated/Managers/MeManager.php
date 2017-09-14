<?php

namespace emsearch\Api\Managers;

use emsearch\Api\ApiClient;
use emsearch\Api\Exceptions\UnexpectedResponseException;
use emsearch\Api\Resources\ProjectListResponse;
use emsearch\Api\Resources\ErrorResponse;
use emsearch\Api\Resources\DataStreamResponse;
use emsearch\Api\Resources\DataStream;
use emsearch\Api\Resources\ProjectResponse;
use emsearch\Api\Resources\Project;
use emsearch\Api\Resources\Meta;
use emsearch\Api\Resources\Pagination;

/**
 * Me manager class
 * 
 * @package emsearch\Api\Managers
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
	 * @throws UnexpectedResponseException
	 */
	public function getUser()
	{
		$routeUrl = '/api/me';

		$requestOptions = [];

		$request = $this->apiClient->getHttpClient()->request('get', $routeUrl, $requestOptions);

		if ($request->getStatusCode() != 200) {
			throw new UnexpectedResponseException($request->getStatusCode(), 200, $request);
		}
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
