<?php

namespace emsearch\Api\Resources;

use emsearch\Api\ApiClient;
use emsearch\Api\Exceptions\UnexpectedResponseException;

/**
 * WidgetPreset resource class
 * 
 * @package emsearch\Api\Resources
 */
class WidgetPreset 
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
	public $search_use_case_preset_id;

	/**
	 * @var string
	 */
	public $name;

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
	 * @var SearchUseCasePresetResponse
	 */
	public $searchUseCasePreset;

	/**
	 * WidgetPreset resource class constructor
	 * 
	 * @param ApiClient $apiClient API Client to use for this manager requests
	 * @param string $id Format: uuid.
	 * @param string $search_use_case_preset_id Format: uuid.
	 * @param string $name
	 * @param string $created_at Format: date-time.
	 * @param string $updated_at Format: date-time.
	 * @param SearchUseCasePresetResponse $searchUseCasePreset
	 */
	public function __construct(ApiClient $apiClient, $id = null, $search_use_case_preset_id = null, $name = null, $created_at = null, $updated_at = null, $searchUseCasePreset = null)
	{
		$this->apiClient = $apiClient;
		$this->id = $id;
		$this->search_use_case_preset_id = $search_use_case_preset_id;
		$this->name = $name;
		$this->created_at = $created_at;
		$this->updated_at = $updated_at;
		$this->searchUseCasePreset = $searchUseCasePreset;
	}
}
