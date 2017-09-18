<?php

namespace Emsearch\Api\Resources;

use Emsearch\Api\ApiClient;
use Emsearch\Api\Exceptions\UnexpectedResponseException;

/**
 * UserGroupResponse resource class
 * 
 * @package Emsearch\Api\Resources
 */
class UserGroupResponse 
{
	/**
	 * API client
	 *
	 * @var ApiClient
	 */
	protected $apiClient;

	/**
	 * @var UserGroup
	 */
	public $data;

	/**
	 * UserGroupResponse resource class constructor
	 * 
	 * @param ApiClient $apiClient API Client to use for this manager requests
	 * @param UserGroup $data
	 */
	public function __construct(ApiClient $apiClient, $data = null)
	{
		$this->apiClient = $apiClient;
		$this->data = $data;
	}
}
