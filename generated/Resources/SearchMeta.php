<?php

namespace emsearch\Api\Resources;

use emsearch\Api\ApiClient;
use emsearch\Api\Exceptions\UnexpectedResponseException;

/**
 * SearchMeta resource class
 * 
 * @package emsearch\Api\Resources
 */
class SearchMeta 
{
	/**
	 * API client
	 *
	 * @var ApiClient
	 */
	protected $apiClient;

	/**
	 * @var SearchPagination
	 */
	public $pagination;

	/**
	 * SearchMeta resource class constructor
	 * 
	 * @param ApiClient $apiClient API Client to use for this manager requests
	 * @param SearchPagination $pagination
	 */
	public function __construct(ApiClient $apiClient, $pagination = null)
	{
		$this->apiClient = $apiClient;
		$this->pagination = $pagination;
	}
}
