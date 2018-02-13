<?php

namespace Emsearch\Api\Resources;

use Emsearch\Api\ApiClient;
use Emsearch\Api\Exceptions\UnexpectedResponseException;

/**
 * Project resource class
 * 
 * @package Emsearch\Api\Resources
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
	 * @var DataStreamResponse
	 */
	public $dataStream;

	/**
	 * @var SearchEngineResponse
	 */
	public $searchEngine;

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
	 * @param DataStreamResponse $dataStream
	 * @param SearchEngineResponse $searchEngine
	 */
	public function __construct(ApiClient $apiClient, $id = null, $search_engine_id = null, $data_stream_id = null, $name = null, $created_at = null, $updated_at = null, $dataStream = null, $searchEngine = null)
	{
		$this->apiClient = $apiClient;
		$this->id = $id;
		$this->search_engine_id = $search_engine_id;
		$this->data_stream_id = $data_stream_id;
		$this->name = $name;
		$this->created_at = $created_at;
		$this->updated_at = $updated_at;
		$this->dataStream = $dataStream;
		$this->searchEngine = $searchEngine;
	}
	/**
	 * Project relationship between users and projects list
	 * 
	 * You can specify a GET parameter `user_role_id` to filter results.
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $user_role_id
	 * @param string $search Search words
	 * @param int $page Format: int32. Pagination : Page number
	 * @param int $limit Format: int32. Pagination : Maximum entries per page
	 * @param string $order_by Order by : {field},[asc|desc]
	 * 
	 * @return UserHasProjectListResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function getProjectHasUsers($user_role_id = null, $search = null, $page = null, $limit = null, $order_by = null)
	{
		$routePath = '/api/project/{projectId}/userHasProject';

		$pathReplacements = [
			'{projectId}' => $this->id,
		];

		$routeUrl = str_replace(array_keys($pathReplacements), array_values($pathReplacements), $routePath);

		$queryParameters = [];

		if (!is_null($user_role_id)) {
			$queryParameters['user_role_id'] = $user_role_id;
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

		$response = new UserHasProjectListResponse(
			$this->apiClient, 
			array_map(function($data) {
				return new UserHasProject(
					$this->apiClient, 
					$data['user_id'], 
					$data['project_id'], 
					$data['user_role_id'], 
					$data['created_at'], 
					$data['updated_at'], 
					((isset($data['user']) && !is_null($data['user'])) ? (new UserResponse(
						$this->apiClient, 
						new User(
							$this->apiClient, 
							$data['user']['data']['id'], 
							$data['user']['data']['user_group_id'], 
							$data['user']['data']['name'], 
							$data['user']['data']['email'], 
							$data['user']['data']['created_at'], 
							$data['user']['data']['updated_at']
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
		$routePath = '/api/project/{projectId}';

		$pathReplacements = [
			'{projectId}' => $this->id,
		];

		$routeUrl = str_replace(array_keys($pathReplacements), array_values($pathReplacements), $routePath);

		$bodyParameters = [];
		$bodyParameters['search_engine_id'] = $search_engine_id;
		$bodyParameters['name'] = $name;

		if (!is_null($data_stream_id)) {
			$bodyParameters['data_stream_id'] = $data_stream_id;
		}

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

		$response = new ProjectResponse(
			$this->apiClient, 
			new Project(
				$this->apiClient, 
				$requestBody['data']['id'], 
				$requestBody['data']['search_engine_id'], 
				$requestBody['data']['data_stream_id'], 
				$requestBody['data']['name'], 
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
						$requestBody['data']['dataStream']['data']['basic_auth_user'], 
						$requestBody['data']['dataStream']['data']['basic_auth_password'], 
						$requestBody['data']['dataStream']['data']['created_at'], 
						$requestBody['data']['dataStream']['data']['updated_at'], 
						null, 
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
				)) : null), 
				((isset($requestBody['data']['searchEngine']) && !is_null($requestBody['data']['searchEngine'])) ? (new SearchEngineResponse(
					$this->apiClient, 
					new SearchEngine(
						$this->apiClient, 
						$requestBody['data']['searchEngine']['data']['id'], 
						$requestBody['data']['searchEngine']['data']['name'], 
						$requestBody['data']['searchEngine']['data']['class_name'], 
						$requestBody['data']['searchEngine']['data']['created_at'], 
						$requestBody['data']['searchEngine']['data']['updated_at'], 
						(isset($requestBody['data']['searchEngine']['data']['projects_count']) ? $requestBody['data']['searchEngine']['data']['projects_count'] : null)
					)
				)) : null)
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
	 * @return ErrorResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function delete()
	{
		$routePath = '/api/project/{projectId}';

		$pathReplacements = [
			'{projectId}' => $this->id,
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
	 * Project sync task list
	 * 
	 * You can specify a GET parameter `root` (return only root tasks if true, children only if false) to filter results
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param boolean $root
	 * @param string $search Search words
	 * @param int $page Format: int32. Pagination : Page number
	 * @param int $limit Format: int32. Pagination : Maximum entries per page
	 * @param string $order_by Order by : {field},[asc|desc]
	 * 
	 * @return SyncTaskListResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function getSyncTasks($root = null, $search = null, $page = null, $limit = null, $order_by = null)
	{
		$routePath = '/api/project/{projectId}/syncTask';

		$pathReplacements = [
			'{projectId}' => $this->id,
		];

		$routeUrl = str_replace(array_keys($pathReplacements), array_values($pathReplacements), $routePath);

		$queryParameters = [];

		if (!is_null($root)) {
			$queryParameters['root'] = $root;
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
	 * Request a new main sync. task
	 * 
	 * Excepted HTTP code : 201
	 * 
	 * @return SyncTaskResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function requestSyncTask()
	{
		$routePath = '/api/project/{projectId}/syncTask';

		$pathReplacements = [
			'{projectId}' => $this->id,
		];

		$routeUrl = str_replace(array_keys($pathReplacements), array_values($pathReplacements), $routePath);

		$requestOptions = [];

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
	 * Show project data stream
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $include Include responses : {include1},{include2,{include3}[...]
	 * 
	 * @return DataStreamResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function getDataStream($include = null)
	{
		$routePath = '/api/project/{projectId}/dataStream';

		$pathReplacements = [
			'{projectId}' => $this->id,
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
	 * Update the project data stream
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
	public function updateDataStream($data_stream_decoder_id, $name, $feed_url, $basic_auth_user = null, $basic_auth_password = null)
	{
		$routePath = '/api/project/{projectId}/dataStream';

		$pathReplacements = [
			'{projectId}' => $this->id,
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
				(isset($requestBody['app_error_code']) ? $requestBody['app_error_code'] : null), 
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
	 * Create and store the project data stream
	 * 
	 * Only one data stream per project is allowed.
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
	public function createDataStream($data_stream_decoder_id, $name, $feed_url, $basic_auth_user = null, $basic_auth_password = null)
	{
		$routePath = '/api/project/{projectId}/dataStream';

		$pathReplacements = [
			'{projectId}' => $this->id,
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
		$routePath = '/api/project/{projectId}/dataStream';

		$pathReplacements = [
			'{projectId}' => $this->id,
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
	 * Project search use case list
	 * 
	 * Excepted HTTP code : 200
	 * 
	 * @param string $search Search words
	 * @param int $page Format: int32. Pagination : Page number
	 * @param int $limit Format: int32. Pagination : Maximum entries per page
	 * @param string $order_by Order by : {field},[asc|desc]
	 * 
	 * @return SearchUseCaseListResponse
	 * 
	 * @throws UnexpectedResponseException
	 */
	public function getSearchUseCases($search = null, $page = null, $limit = null, $order_by = null)
	{
		$routePath = '/api/project/{projectId}/searchUseCase';

		$pathReplacements = [
			'{projectId}' => $this->id,
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

		$response = new SearchUseCaseListResponse(
			$this->apiClient, 
			array_map(function($data) {
				return new SearchUseCase(
					$this->apiClient, 
					$data['id'], 
					(isset($data['project_id']) ? $data['project_id'] : null), 
					$data['name'], 
					$data['created_at'], 
					$data['updated_at'], 
					(isset($data['search_use_case_fields_count']) ? $data['search_use_case_fields_count'] : null), 
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
}
