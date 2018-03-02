<?php

namespace Emsearch\Api\Resources;

use Emsearch\Api\ApiClient;
use Emsearch\Api\Exceptions\UnexpectedResponseException;

/**
 * SyncTask resource class
 * 
 * @package Emsearch\Api\Resources
 */
class SyncTask 
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
	public $sync_task_id;

	/**
	 * @var string
	 */
	public $sync_task_type_id;

	/**
	 * @var string
	 */
	public $sync_task_status_id;

	/**
	 * Format: uuid.
	 * 
	 * @var string
	 */
	public $created_by_user_id;

	/**
	 * Format: uuid.
	 * 
	 * @var string
	 */
	public $project_id;

	/**
	 * Format: date-time.
	 * 
	 * @var string
	 */
	public $planned_at;

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
	public $sync_task_logs_count;

	/**
	 * Format: int32.
	 * 
	 * @var int
	 */
	public $children_sync_tasks_count;

	/**
	 * @var UserResponse
	 */
	public $createdByUser;

	/**
	 * @var ProjectResponse
	 */
	public $project;

	/**
	 * SyncTask resource class constructor
	 * 
	 * @param ApiClient $apiClient API Client to use for this manager requests
	 * @param string $id Format: uuid.
	 * @param string $sync_task_id Format: uuid.
	 * @param string $sync_task_type_id
	 * @param string $sync_task_status_id
	 * @param string $created_by_user_id Format: uuid.
	 * @param string $project_id Format: uuid.
	 * @param string $planned_at Format: date-time.
	 * @param string $created_at Format: date-time.
	 * @param string $updated_at Format: date-time.
	 * @param int $sync_task_logs_count Format: int32.
	 * @param int $children_sync_tasks_count Format: int32.
	 * @param UserResponse $createdByUser
	 * @param ProjectResponse $project
	 */
	public function __construct(ApiClient $apiClient, $id = null, $sync_task_id = null, $sync_task_type_id = null, $sync_task_status_id = null, $created_by_user_id = null, $project_id = null, $planned_at = null, $created_at = null, $updated_at = null, $sync_task_logs_count = null, $children_sync_tasks_count = null, $createdByUser = null, $project = null)
	{
		$this->apiClient = $apiClient;
		$this->id = $id;
		$this->sync_task_id = $sync_task_id;
		$this->sync_task_type_id = $sync_task_type_id;
		$this->sync_task_status_id = $sync_task_status_id;
		$this->created_by_user_id = $created_by_user_id;
		$this->project_id = $project_id;
		$this->planned_at = $planned_at;
		$this->created_at = $created_at;
		$this->updated_at = $updated_at;
		$this->sync_task_logs_count = $sync_task_logs_count;
		$this->children_sync_tasks_count = $children_sync_tasks_count;
		$this->createdByUser = $createdByUser;
		$this->project = $project;
	}
	/**
	 * Update a specified sync task
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $sync_task_type_id
	 * @param string $sync_task_status_id
	 * @param string $created_by_user_id Format: uuid.
	 * @param string $project_id Format: uuid.
	 * @param string $sync_task_id Format: uuid.
	 * @param string $planned_at Must be a valid date according to the strtotime PHP function.
	 * 
	 * @return SyncTaskResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function update($sync_task_type_id, $sync_task_status_id, $created_by_user_id, $project_id, $sync_task_id = null, $planned_at = null)
	{
		$routePath = '/api/syncTask/{syncTaskId}';

		$pathReplacements = [
			'{syncTaskId}' => $this->id,
		];

		$routeUrl = str_replace(array_keys($pathReplacements), array_values($pathReplacements), $routePath);

		$bodyParameters = [];
		$bodyParameters['sync_task_type_id'] = $sync_task_type_id;
		$bodyParameters['sync_task_status_id'] = $sync_task_status_id;
		$bodyParameters['created_by_user_id'] = $created_by_user_id;
		$bodyParameters['project_id'] = $project_id;

		if (!is_null($sync_task_id)) {
			$bodyParameters['sync_task_id'] = $sync_task_id;
		}

		if (!is_null($planned_at)) {
			$bodyParameters['planned_at'] = $planned_at;
		}

		$requestOptions = [];
		$requestOptions['form_params'] = $bodyParameters;

		$request = $this->apiClient->getHttpClient()->request('patch', $routeUrl, $requestOptions);

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

		$response = new SyncTaskResponse(
			$this->apiClient, 
			new SyncTask(
				$this->apiClient, 
				$requestBody['data']['id'], 
				(isset($requestBody['data']['sync_task_id']) ? $requestBody['data']['sync_task_id'] : null), 
				$requestBody['data']['sync_task_type_id'], 
				$requestBody['data']['sync_task_status_id'], 
				$requestBody['data']['created_by_user_id'], 
				$requestBody['data']['project_id'], 
				$requestBody['data']['planned_at'], 
				$requestBody['data']['created_at'], 
				$requestBody['data']['updated_at'], 
				$requestBody['data']['sync_task_logs_count'], 
				$requestBody['data']['children_sync_tasks_count'], 
				((isset($requestBody['data']['createdByUser']) && !is_null($requestBody['data']['createdByUser'])) ? (new UserResponse(
					$this->apiClient, 
					new User(
						$this->apiClient, 
						$requestBody['data']['createdByUser']['data']['id'], 
						$requestBody['data']['createdByUser']['data']['user_group_id'], 
						$requestBody['data']['createdByUser']['data']['name'], 
						$requestBody['data']['createdByUser']['data']['email'], 
						(isset($requestBody['data']['createdByUser']['data']['password']) ? $requestBody['data']['createdByUser']['data']['password'] : null), 
						$requestBody['data']['createdByUser']['data']['preferred_language'], 
						$requestBody['data']['createdByUser']['data']['created_at'], 
						$requestBody['data']['createdByUser']['data']['updated_at']
					)
				)) : null), 
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
	 * Delete specified sync task
	 * 
	 * Excepted HTTP code : 204
	 * 
	 * @return ErrorResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function delete()
	{
		$routePath = '/api/syncTask/{syncTaskId}';

		$pathReplacements = [
			'{syncTaskId}' => $this->id,
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
	 * Sync task sync task children list
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $search Search words
	 * @param int $page Format: int32. Pagination : Page number
	 * @param int $limit Format: int32. Pagination : Maximum entries per page
	 * @param string $order_by Order by : {field},[asc|desc]
	 * 
	 * @return SyncTaskListResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function getChildrenSyncTasks($search = null, $page = null, $limit = null, $order_by = null)
	{
		$routePath = '/api/syncTask/{syncTaskId}/children';

		$pathReplacements = [
			'{syncTaskId}' => $this->id,
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

		$response = new SyncTaskListResponse(
			$this->apiClient, 
			array_map(function($data) {
				return new SyncTask(
					$this->apiClient, 
					$data['id'], 
					(isset($data['sync_task_id']) ? $data['sync_task_id'] : null), 
					$data['sync_task_type_id'], 
					$data['sync_task_status_id'], 
					$data['created_by_user_id'], 
					$data['project_id'], 
					$data['planned_at'], 
					$data['created_at'], 
					$data['updated_at'], 
					$data['sync_task_logs_count'], 
					$data['children_sync_tasks_count'], 
					((isset($data['createdByUser']) && !is_null($data['createdByUser'])) ? (new UserResponse(
						$this->apiClient, 
						new User(
							$this->apiClient, 
							$data['createdByUser']['data']['id'], 
							$data['createdByUser']['data']['user_group_id'], 
							$data['createdByUser']['data']['name'], 
							$data['createdByUser']['data']['email'], 
							(isset($data['createdByUser']['data']['password']) ? $data['password'] : null), 
							$data['createdByUser']['data']['preferred_language'], 
							$data['createdByUser']['data']['created_at'], 
							$data['createdByUser']['data']['updated_at']
						)
					)) : null), 
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
								new DataStream(
									$this->apiClient, 
									$data['project']['data']['dataStream']['data']['id'], 
									$data['project']['data']['dataStream']['data']['data_stream_decoder_id'], 
									$data['project']['data']['dataStream']['data']['name'], 
									$data['project']['data']['dataStream']['data']['feed_url'], 
									$data['project']['data']['dataStream']['data']['basic_auth_user'], 
									$data['project']['data']['dataStream']['data']['basic_auth_password'], 
									$data['project']['data']['dataStream']['data']['created_at'], 
									$data['project']['data']['dataStream']['data']['updated_at'], 
									null, 
									((isset($data['project']['data']['dataStream']['data']['dataStreamDecoder']) && !is_null($data['project']['data']['dataStream']['data']['dataStreamDecoder'])) ? (new DataStreamDecoderResponse(
										$this->apiClient, 
										new DataStreamDecoder(
											$this->apiClient, 
											$data['project']['data']['dataStream']['data']['dataStreamDecoder']['data']['id'], 
											$data['project']['data']['dataStream']['data']['dataStreamDecoder']['data']['name'], 
											$data['project']['data']['dataStream']['data']['dataStreamDecoder']['data']['class_name'], 
											$data['project']['data']['dataStream']['data']['dataStreamDecoder']['data']['file_mime_type'], 
											$data['project']['data']['dataStream']['data']['dataStreamDecoder']['data']['created_at'], 
											$data['project']['data']['dataStream']['data']['dataStreamDecoder']['data']['updated_at']
										)
									)) : null)
								)
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
	 * Sync task sync task logs list
	 * 
	 * You can specify a GET parameter `public` to filter results (Only allowed for `Developer` and `Support` users).
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param boolean $public
	 * @param string $search Search words
	 * @param int $page Format: int32. Pagination : Page number
	 * @param int $limit Format: int32. Pagination : Maximum entries per page
	 * @param string $order_by Order by : {field},[asc|desc]
	 * 
	 * @return SyncTaskLogListResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function getLogs($public = null, $search = null, $page = null, $limit = null, $order_by = null)
	{
		$routePath = '/api/syncTask/{syncTaskId}/syncTaskLog';

		$pathReplacements = [
			'{syncTaskId}' => $this->id,
		];

		$routeUrl = str_replace(array_keys($pathReplacements), array_values($pathReplacements), $routePath);

		$queryParameters = [];

		if (!is_null($public)) {
			$queryParameters['public'] = $public;
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

		$response = new SyncTaskLogListResponse(
			$this->apiClient, 
			array_map(function($data) {
				return new SyncTaskLog(
					$this->apiClient, 
					$data['id'], 
					$data['sync_task_status_id'], 
					$data['sync_task_id'], 
					$data['entry'], 
					$data['public'], 
					(isset($data['position']) ? $data['position'] : null), 
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
}
