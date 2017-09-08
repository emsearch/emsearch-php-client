<?php

namespace emsearch\Api\Resources;

use emsearch\Api\ApiClient;
use emsearch\Api\Exceptions\ApiException;
use emsearch\Api\Resources\Pagination;

/**
 * Meta resource class
 * 
 * @package emsearch\Api\Resources
 */
class Meta 
{
	/**
	 * API client
	 *
	 * @var ApiClient
	 */
	protected $apiClient;

	/**
	 * @var Pagination
	 */
	public $pagination;

	/**
	 * Meta resource class constructor
	 * 
	 * @param ApiClient $apiClient API Client to use for this manager requests
	 * @param Pagination $pagination
	 */
	public function __constructor(ApiClient $apiClient, $pagination = null)
	{
		$this->apiClient = $apiClient;
		$this->pagination = $pagination;
	}
}
