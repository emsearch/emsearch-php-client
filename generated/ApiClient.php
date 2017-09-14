<?php

namespace emsearch\Api;

use GuzzleHttp\Client as GuzzleClient;
use Psr\Http\Message\RequestInterface;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\Middleware;
use emsearch\Api\Managers\MeManager;
use emsearch\Api\Managers\ProjectManager;
use emsearch\Api\Managers\DataStreamManager;
use emsearch\Api\Managers\DataStreamFieldManager;
use emsearch\Api\Managers\DataStreamHasI18nLangManager;
use emsearch\Api\Managers\DataStreamDecoderManager;
use emsearch\Api\Managers\DataStreamPresetManager;
use emsearch\Api\Managers\DataStreamPresetFieldManager;

/**
 * emsearch API client class (version 1.0)
 * 
 * @package emsearch\Api
 */
class ApiClient 
{
	/**
	 * API base url for requests
	 *
	 * @var string
	 */
	protected $apiBaseUrl;

	/**
	 * Guzzle client for API requests
	 *
	 * @var GuzzleClient;
	 */
	protected $httpClient;

	/**
	 * Bearer authentication access token
	 *
	 * @var string
	 */
	protected $bearerToken;

	/**
	 * Map of global headers to use with every requests
	 *
	 * @var string[]
	 */
	protected $globalHeaders = [];

	/**
	 * Me manager
	 *
	 * @var MeManager
	 */
	protected $meManager;

	/**
	 * Project manager
	 *
	 * @var ProjectManager
	 */
	protected $projectManager;

	/**
	 * DataStream manager
	 *
	 * @var DataStreamManager
	 */
	protected $dataStreamManager;

	/**
	 * DataStreamField manager
	 *
	 * @var DataStreamFieldManager
	 */
	protected $dataStreamFieldManager;

	/**
	 * DataStreamHasI18nLang manager
	 *
	 * @var DataStreamHasI18nLangManager
	 */
	protected $dataStreamHasI18nLangManager;

	/**
	 * DataStreamDecoder manager
	 *
	 * @var DataStreamDecoderManager
	 */
	protected $dataStreamDecoderManager;

	/**
	 * DataStreamPreset manager
	 *
	 * @var DataStreamPresetManager
	 */
	protected $dataStreamPresetManager;

	/**
	 * DataStreamPresetField manager
	 *
	 * @var DataStreamPresetFieldManager
	 */
	protected $dataStreamPresetFieldManager;

	/**
	 * API Client class constructor
	 *
	 * @param string $bearerToken Bearer authentication access token
	 * @param string $apiBaseUrl API base url for requests
	 * @param string[] $globalHeaders Map of global headers to use with every requests
	 */
	public function __construct($bearerToken, $apiBaseUrl = 'https://emsearch.ryan.ems-dev.net', $globalHeaders = [])
	{
		$this->apiBaseUrl = $apiBaseUrl;
		$this->globalHeaders = $globalHeaders;

		$this->bearerToken = $bearerToken;

		$stack = new HandlerStack();
		$stack->setHandler(new CurlHandler());

		$stack->push(Middleware::mapRequest(function (RequestInterface $request) {
			if (count($this->globalHeaders) > 0) {
				$request = $request->withHeader('Authorization', 'Bearer ' . $this->bearerToken);
				foreach ($this->globalHeaders as $header => $value) {
					$request = $request->withHeader($header, $value);
				}
				return $request;
			} else {
				return $request->withHeader('Authorization', 'Bearer ' . $this->bearerToken);
			}
		}));

		$this->httpClient = new GuzzleClient([
			'handler' => $stack,
			'base_uri' => $apiBaseUrl
		]);

		$this->meManager = new MeManager($this);
		$this->projectManager = new ProjectManager($this);
		$this->dataStreamManager = new DataStreamManager($this);
		$this->dataStreamFieldManager = new DataStreamFieldManager($this);
		$this->dataStreamHasI18nLangManager = new DataStreamHasI18nLangManager($this);
		$this->dataStreamDecoderManager = new DataStreamDecoderManager($this);
		$this->dataStreamPresetManager = new DataStreamPresetManager($this);
		$this->dataStreamPresetFieldManager = new DataStreamPresetFieldManager($this);
	}

	/**
	 * Return the API base url
	 *
	 * @return string
	 */
	public function getApiBaseUrl()
	{
		return $this->apiBaseUrl;
	}

	/**
	 * Return the map of global headers to use with every requests
	 *
	 * @return string[]
	 */
	public function getGlobalHeaders()
	{
		return $this->globalHeaders;
	}

	/**
	 * Return the Guzzle HTTP client
	 *
	 * @return GuzzleClient
	 */
	public function getHttpClient()
	{
		return $this->httpClient;
	}

	/**
	 * Return the Me manager
	 *
	 * @return MeManager
	 */
	public function MeManager()
	{
		return $this->meManager;
	}
	
	/**
	 * Return the Project manager
	 *
	 * @return ProjectManager
	 */
	public function ProjectManager()
	{
		return $this->projectManager;
	}
	
	/**
	 * Return the DataStream manager
	 *
	 * @return DataStreamManager
	 */
	public function DataStreamManager()
	{
		return $this->dataStreamManager;
	}
	
	/**
	 * Return the DataStreamField manager
	 *
	 * @return DataStreamFieldManager
	 */
	public function DataStreamFieldManager()
	{
		return $this->dataStreamFieldManager;
	}
	
	/**
	 * Return the DataStreamHasI18nLang manager
	 *
	 * @return DataStreamHasI18nLangManager
	 */
	public function DataStreamHasI18nLangManager()
	{
		return $this->dataStreamHasI18nLangManager;
	}
	
	/**
	 * Return the DataStreamDecoder manager
	 *
	 * @return DataStreamDecoderManager
	 */
	public function DataStreamDecoderManager()
	{
		return $this->dataStreamDecoderManager;
	}
	
	/**
	 * Return the DataStreamPreset manager
	 *
	 * @return DataStreamPresetManager
	 */
	public function DataStreamPresetManager()
	{
		return $this->dataStreamPresetManager;
	}
	
	/**
	 * Return the DataStreamPresetField manager
	 *
	 * @return DataStreamPresetFieldManager
	 */
	public function DataStreamPresetFieldManager()
	{
		return $this->dataStreamPresetFieldManager;
	}
}