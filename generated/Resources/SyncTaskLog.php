<?php

namespace Emsearch\Api\Resources;

use Emsearch\Api\ApiClient;
use Emsearch\Api\Exceptions\UnexpectedResponseException;

/**
 * SyncTaskLog resource class
 * 
 * @package Emsearch\Api\Resources
 */
class SyncTaskLog 
{
	/**
	 * API client
	 *
	 * @var ApiClient
	 */
	protected $apiClient;

	/**
	 * @var string
	 */
	public $id;

	/**
	 * Format: uuid.
	 * 
	 * @var string
	 */
	public $sync_task_status_id;

	/**
	 * Format: uuid.
	 * 
	 * @var string
	 */
	public $sync_task_id;

	/**
	 * @var string
	 */
	public $entry;

	/**
	 * @var boolean
	 */
	public $public;

	/**
	 * Format: int32.
	 * 
	 * @var int
	 */
	public $position;

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
	 * SyncTaskLog resource class constructor
	 * 
	 * @param ApiClient $apiClient API Client to use for this manager requests
	 * @param string $id
	 * @param string $sync_task_status_id Format: uuid.
	 * @param string $sync_task_id Format: uuid.
	 * @param string $entry
	 * @param boolean $public
	 * @param int $position Format: int32.
	 * @param string $created_at Format: date-time.
	 * @param string $updated_at Format: date-time.
	 */
	public function __construct(ApiClient $apiClient, $id = null, $sync_task_status_id = null, $sync_task_id = null, $entry = null, $public = null, $position = null, $created_at = null, $updated_at = null)
	{
		$this->apiClient = $apiClient;
		$this->id = $id;
		$this->sync_task_status_id = $sync_task_status_id;
		$this->sync_task_id = $sync_task_id;
		$this->entry = $entry;
		$this->public = $public;
		$this->position = $position;
		$this->created_at = $created_at;
		$this->updated_at = $updated_at;
	}
}
