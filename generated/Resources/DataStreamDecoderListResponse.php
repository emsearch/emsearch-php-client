<?php

namespace emsearch\Api\Resources;

use emsearch\Api\ApiClient;
use emsearch\Api\Exceptions\UnexpectedResponseException;

/**
 * DataStreamDecoderListResponse resource class
 * 
 * @package emsearch\Api\Resources
 */
class DataStreamDecoderListResponse 
{
	/**
	 * API client
	 *
	 * @var ApiClient
	 */
	protected $apiClient;

	/**
	 * @var DataStreamDecoder[]
	 */
	public $data;

	/**
	 * @var Meta
	 */
	public $meta;

	/**
	 * DataStreamDecoderListResponse resource class constructor
	 * 
	 * @param ApiClient $apiClient API Client to use for this manager requests
	 * @param DataStreamDecoder[] $data
	 * @param Meta $meta
	 */
	public function __construct(ApiClient $apiClient, $data = null, $meta = null)
	{
		$this->apiClient = $apiClient;
		$this->data = $data;
		$this->meta = $meta;
	}
}
