<?php

namespace Emsearch\Api\Resources;

use Emsearch\Api\ApiClient;
use Emsearch\Api\Exceptions\UnexpectedResponseException;

/**
 * Meta resource class
 * 
 * @package Emsearch\Api\Resources
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
	public function __construct(ApiClient $apiClient, $pagination = null)
	{
		$this->apiClient = $apiClient;
		$this->pagination = $pagination;
	}
}
