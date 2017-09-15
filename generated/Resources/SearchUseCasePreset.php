<?php

namespace emsearch\Api\Resources;

use emsearch\Api\ApiClient;
use emsearch\Api\Exceptions\UnexpectedResponseException;

/**
 * SearchUseCasePreset resource class
 * 
 * @package emsearch\Api\Resources
 */
class SearchUseCasePreset 
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
	public $data_stream_preset_id;

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
	 * Format: int32.
	 * 
	 * @var int
	 */
	public $search_use_case_preset_fields_count;

	/**
	 * @var DataStreamPresetResponse
	 */
	public $dataStreamPreset;

	/**
	 * SearchUseCasePreset resource class constructor
	 * 
	 * @param ApiClient $apiClient API Client to use for this manager requests
	 * @param string $id Format: uuid.
	 * @param string $data_stream_preset_id Format: uuid.
	 * @param string $name
	 * @param string $created_at Format: date-time.
	 * @param string $updated_at Format: date-time.
	 * @param int $search_use_case_preset_fields_count Format: int32.
	 * @param DataStreamPresetResponse $dataStreamPreset
	 */
	public function __construct(ApiClient $apiClient, $id = null, $data_stream_preset_id = null, $name = null, $created_at = null, $updated_at = null, $search_use_case_preset_fields_count = null, $dataStreamPreset = null)
	{
		$this->apiClient = $apiClient;
		$this->id = $id;
		$this->data_stream_preset_id = $data_stream_preset_id;
		$this->name = $name;
		$this->created_at = $created_at;
		$this->updated_at = $updated_at;
		$this->search_use_case_preset_fields_count = $search_use_case_preset_fields_count;
		$this->dataStreamPreset = $dataStreamPreset;
	}
}
