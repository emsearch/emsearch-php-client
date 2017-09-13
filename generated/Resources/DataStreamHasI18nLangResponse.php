<?php

namespace emsearch\Api\Resources;

use emsearch\Api\ApiClient;
use emsearch\Api\Exceptions\UnexpectedResponseException;

/**
 * DataStreamHasI18nLangResponse resource class
 * 
 * @package emsearch\Api\Resources
 */
class DataStreamHasI18nLangResponse 
{
	/**
	 * API client
	 *
	 * @var ApiClient
	 */
	protected $apiClient;

	/**
	 * @var DataStreamHasI18nLang
	 */
	public $data;

	/**
	 * DataStreamHasI18nLangResponse resource class constructor
	 * 
	 * @param ApiClient $apiClient API Client to use for this manager requests
	 * @param DataStreamHasI18nLang $data
	 */
	public function __construct(ApiClient $apiClient, $data = null)
	{
		$this->apiClient = $apiClient;
		$this->data = $data;
	}
}
