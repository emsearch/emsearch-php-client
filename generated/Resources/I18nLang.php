<?php

namespace emsearch\Api\Resources;

use emsearch\Api\ApiClient;
use emsearch\Api\Exceptions\UnexpectedResponseException;

/**
 * I18nLang resource class
 * 
 * @package emsearch\Api\Resources
 */
class I18nLang 
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
	 * @var string
	 */
	public $description;

	/**
	 * I18nLang resource class constructor
	 * 
	 * @param ApiClient $apiClient API Client to use for this manager requests
	 * @param string $id
	 * @param string $description
	 */
	public function __construct(ApiClient $apiClient, $id = null, $description = null)
	{
		$this->apiClient = $apiClient;
		$this->id = $id;
		$this->description = $description;
	}
}
