<?php

namespace emsearch\Api;

use GuzzleHttp\Client as GuzzleClient;
use Psr\Http\Message\RequestInterface;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\Middleware;
use emsearch\Api\Managers\MeManager;
use emsearch\Api\Managers\ProjectManager;

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
	 * API Client class constructor
	 *
	 * @param string $bearerToken Bearer authentication access token
	 * @param string $apiBaseUrl API base url for requests
	 */
	public function __construct($bearerToken, $apiBaseUrl = 'https://emsearch.ryan.ems-dev.net')
	{
		$this->apiBaseUrl = $apiBaseUrl;

		$this->bearerToken = $bearerToken;

		$stack = new HandlerStack();
		$stack->setHandler(new CurlHandler());

		$stack->push(Middleware::mapRequest(function (RequestInterface $request) {
			return $request->withHeader('Authorization', 'Bearer ' . $this->bearerToken);
		}));

		$this->httpClient = new GuzzleClient([
			'handler' => $stack,
			'base_uri' => $apiBaseUrl
		]);

		$this->meManager = new MeManager($this);
		$this->projectManager = new ProjectManager($this);
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
}