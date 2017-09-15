<?php

namespace emsearch\Api\Resources;

use emsearch\Api\ApiClient;
use emsearch\Api\Exceptions\UnexpectedResponseException;

/**
 * User resource class
 * 
 * @package emsearch\Api\Resources
 */
class User 
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
	public $user_group_id;

	/**
	 * @var string
	 */
	public $name;

	/**
	 * Format: email.
	 * 
	 * @var string
	 */
	public $email;

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
	 * User resource class constructor
	 * 
	 * @param ApiClient $apiClient API Client to use for this manager requests
	 * @param string $id Format: uuid.
	 * @param string $user_group_id Format: uuid.
	 * @param string $name
	 * @param string $email Format: email.
	 * @param string $created_at Format: date-time.
	 * @param string $updated_at Format: date-time.
	 */
	public function __construct(ApiClient $apiClient, $id = null, $user_group_id = null, $name = null, $email = null, $created_at = null, $updated_at = null)
	{
		$this->apiClient = $apiClient;
		$this->id = $id;
		$this->user_group_id = $user_group_id;
		$this->name = $name;
		$this->email = $email;
		$this->created_at = $created_at;
		$this->updated_at = $updated_at;
	}
}
