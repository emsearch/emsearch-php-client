<?php

namespace emsearch\Api\Resources;

use emsearch\Api\ApiClient;
use emsearch\Api\Exceptions\UnexpectedResponseException;

/**
 * SyncTask resource class
 * 
 * @package emsearch\Api\Resources
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
	 * Excepted HTTP code : 201
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
