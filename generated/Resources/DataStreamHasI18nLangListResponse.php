<?php

namespace emsearch\Api\Resources;

use emsearch\Api\ApiClient;
use emsearch\Api\Exceptions\UnexpectedResponseException;

/**
 * DataStreamHasI18nLangListResponse resource class
 * 
 * @package emsearch\Api\Resources
 */
class DataStreamHasI18nLangListResponse 
{
	/**
	 * API client
	 *
	 * @var ApiClient
	 */
	protected $apiClient;

	/**
	 * @var DataStreamHasI18nLang[]
	 */
	public $data;

	/**
	 * @var Meta
	 */
	public $meta;

	/**
	 * DataStreamHasI18nLangListResponse resource class constructor
	 * 
	 * @param ApiClient $apiClient API Client to use for this manager requests
	 * @param DataStreamHasI18nLang[] $data
	 * @param Meta $meta
	 */
	public function __construct(ApiClient $apiClient, $data = null, $meta = null)
	{
		$this->apiClient = $apiClient;
		$this->data = $data;
		$this->meta = $meta;
	}
}
