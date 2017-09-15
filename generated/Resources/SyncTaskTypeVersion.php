<?php

namespace emsearch\Api\Resources;

use emsearch\Api\ApiClient;
use emsearch\Api\Exceptions\UnexpectedResponseException;

/**
 * SyncTaskTypeVersion resource class
 * 
 * @package emsearch\Api\Resources
 */
class SyncTaskTypeVersion 
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
	public $sync_task_type_id;

	/**
	 * @var string
	 */
	public $i18n_lang_id;

	/**
	 * @var string
	 */
	public $description;

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
	 * SyncTaskTypeVersion resource class constructor
	 * 
	 * @param ApiClient $apiClient API Client to use for this manager requests
	 * @param string $sync_task_type_id Format: uuid.
	 * @param string $i18n_lang_id
	 * @param string $description
	 * @param string $created_at Format: date-time.
	 * @param string $updated_at Format: date-time.
	 */
	public function __construct(ApiClient $apiClient, $sync_task_type_id = null, $i18n_lang_id = null, $description = null, $created_at = null, $updated_at = null)
	{
		$this->apiClient = $apiClient;
		$this->sync_task_type_id = $sync_task_type_id;
		$this->i18n_lang_id = $i18n_lang_id;
		$this->description = $description;
		$this->created_at = $created_at;
		$this->updated_at = $updated_at;
	}
}
