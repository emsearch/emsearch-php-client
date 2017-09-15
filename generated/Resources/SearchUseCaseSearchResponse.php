<?php

namespace emsearch\Api\Resources;

use emsearch\Api\ApiClient;
use emsearch\Api\Exceptions\UnexpectedResponseException;

/**
 * SearchUseCaseSearchResponse resource class
 * 
 * @package emsearch\Api\Resources
 */
class SearchUseCaseSearchResponse 
{
	/**
	 * API client
	 *
	 * @var ApiClient
	 */
	protected $apiClient;

	/**
	 * Search response data
	 * 
	 * @var object
	 */
	public $data;

	/**
	 * @var SearchMeta
	 */
	public $meta;

	/**
	 * SearchUseCaseSearchResponse resource class constructor
	 * 
	 * @param ApiClient $apiClient API Client to use for this manager requests
	 * @param object $data Search response data
	 * @param SearchMeta $meta
	 */
	public function __construct(ApiClient $apiClient, $data = null, $meta = null)
	{
		$this->apiClient = $apiClient;
		$this->data = $data;
		$this->meta = $meta;
	}
}
