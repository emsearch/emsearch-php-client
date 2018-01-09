<?php

namespace Emsearch\Api;

use GuzzleHttp\Client as GuzzleClient;
use Psr\Http\Message\RequestInterface;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\Middleware;
use Emsearch\Api\Managers\SearchUseCaseManager;
use Emsearch\Api\Managers\MeManager;
use Emsearch\Api\Managers\UserGroupManager;
use Emsearch\Api\Managers\UserManager;
use Emsearch\Api\Managers\ProjectManager;
use Emsearch\Api\Managers\SyncItemManager;
use Emsearch\Api\Managers\SyncTaskManager;
use Emsearch\Api\Managers\SyncTaskTypeManager;
use Emsearch\Api\Managers\SyncTaskTypeVersionManager;
use Emsearch\Api\Managers\SyncTaskStatusManager;
use Emsearch\Api\Managers\SyncTaskStatusVersionManager;
use Emsearch\Api\Managers\DataStreamManager;
use Emsearch\Api\Managers\DataStreamFieldManager;
use Emsearch\Api\Managers\DataStreamHasI18nLangManager;
use Emsearch\Api\Managers\UserHasProjectManager;
use Emsearch\Api\Managers\DataStreamDecoderManager;
use Emsearch\Api\Managers\DataStreamPresetManager;
use Emsearch\Api\Managers\DataStreamPresetFieldManager;
use Emsearch\Api\Managers\I18nLangManager;
use Emsearch\Api\Managers\SearchEngineManager;
use Emsearch\Api\Managers\SearchUseCaseFieldManager;
use Emsearch\Api\Managers\SearchUseCasePresetManager;
use Emsearch\Api\Managers\SearchUseCasePresetFieldManager;
use Emsearch\Api\Managers\WidgetManager;
use Emsearch\Api\Managers\WidgetPresetManager;

/**
 * ems-search API client class (version 1.0)
 * 
 * @package Emsearch\Api
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
	 * SearchUseCase manager
	 *
	 * @var SearchUseCaseManager
	 */
	protected $searchUseCaseManager;

	/**
	 * Me manager
	 *
	 * @var MeManager
	 */
	protected $meManager;

	/**
	 * UserGroup manager
	 *
	 * @var UserGroupManager
	 */
	protected $userGroupManager;

	/**
	 * User manager
	 *
	 * @var UserManager
	 */
	protected $userManager;

	/**
	 * Project manager
	 *
	 * @var ProjectManager
	 */
	protected $projectManager;

	/**
	 * SyncItem manager
	 *
	 * @var SyncItemManager
	 */
	protected $syncItemManager;

	/**
	 * SyncTask manager
	 *
	 * @var SyncTaskManager
	 */
	protected $syncTaskManager;

	/**
	 * SyncTaskType manager
	 *
	 * @var SyncTaskTypeManager
	 */
	protected $syncTaskTypeManager;

	/**
	 * SyncTaskTypeVersion manager
	 *
	 * @var SyncTaskTypeVersionManager
	 */
	protected $syncTaskTypeVersionManager;

	/**
	 * SyncTaskStatus manager
	 *
	 * @var SyncTaskStatusManager
	 */
	protected $syncTaskStatusManager;

	/**
	 * SyncTaskStatusVersion manager
	 *
	 * @var SyncTaskStatusVersionManager
	 */
	protected $syncTaskStatusVersionManager;

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
	 * UserHasProject manager
	 *
	 * @var UserHasProjectManager
	 */
	protected $userHasProjectManager;

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
	 * I18nLang manager
	 *
	 * @var I18nLangManager
	 */
	protected $i18nLangManager;

	/**
	 * SearchEngine manager
	 *
	 * @var SearchEngineManager
	 */
	protected $searchEngineManager;

	/**
	 * SearchUseCaseField manager
	 *
	 * @var SearchUseCaseFieldManager
	 */
	protected $searchUseCaseFieldManager;

	/**
	 * SearchUseCasePreset manager
	 *
	 * @var SearchUseCasePresetManager
	 */
	protected $searchUseCasePresetManager;

	/**
	 * SearchUseCasePresetField manager
	 *
	 * @var SearchUseCasePresetFieldManager
	 */
	protected $searchUseCasePresetFieldManager;

	/**
	 * Widget manager
	 *
	 * @var WidgetManager
	 */
	protected $widgetManager;

	/**
	 * WidgetPreset manager
	 *
	 * @var WidgetPresetManager
	 */
	protected $widgetPresetManager;

	/**
	 * API Client class constructor
	 *
	 * @param string $bearerToken Bearer authentication access token
	 * @param string $apiBaseUrl API base url for requests
	 * @param string[] $globalHeaders Map of global headers to use with every requests
	 */
	public function __construct($bearerToken, $apiBaseUrl = 'https://www.ems-search.com', $globalHeaders = [])
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

		$this->searchUseCaseManager = new SearchUseCaseManager($this);
		$this->meManager = new MeManager($this);
		$this->userGroupManager = new UserGroupManager($this);
		$this->userManager = new UserManager($this);
		$this->projectManager = new ProjectManager($this);
		$this->syncItemManager = new SyncItemManager($this);
		$this->syncTaskManager = new SyncTaskManager($this);
		$this->syncTaskTypeManager = new SyncTaskTypeManager($this);
		$this->syncTaskTypeVersionManager = new SyncTaskTypeVersionManager($this);
		$this->syncTaskStatusManager = new SyncTaskStatusManager($this);
		$this->syncTaskStatusVersionManager = new SyncTaskStatusVersionManager($this);
		$this->dataStreamManager = new DataStreamManager($this);
		$this->dataStreamFieldManager = new DataStreamFieldManager($this);
		$this->dataStreamHasI18nLangManager = new DataStreamHasI18nLangManager($this);
		$this->userHasProjectManager = new UserHasProjectManager($this);
		$this->dataStreamDecoderManager = new DataStreamDecoderManager($this);
		$this->dataStreamPresetManager = new DataStreamPresetManager($this);
		$this->dataStreamPresetFieldManager = new DataStreamPresetFieldManager($this);
		$this->i18nLangManager = new I18nLangManager($this);
		$this->searchEngineManager = new SearchEngineManager($this);
		$this->searchUseCaseFieldManager = new SearchUseCaseFieldManager($this);
		$this->searchUseCasePresetManager = new SearchUseCasePresetManager($this);
		$this->searchUseCasePresetFieldManager = new SearchUseCasePresetFieldManager($this);
		$this->widgetManager = new WidgetManager($this);
		$this->widgetPresetManager = new WidgetPresetManager($this);
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
	 * Return the SearchUseCase manager
	 *
	 * @return SearchUseCaseManager
	 */
	public function SearchUseCaseManager()
	{
		return $this->searchUseCaseManager;
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
	 * Return the UserGroup manager
	 *
	 * @return UserGroupManager
	 */
	public function UserGroupManager()
	{
		return $this->userGroupManager;
	}
	
	/**
	 * Return the User manager
	 *
	 * @return UserManager
	 */
	public function UserManager()
	{
		return $this->userManager;
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
	 * Return the SyncItem manager
	 *
	 * @return SyncItemManager
	 */
	public function SyncItemManager()
	{
		return $this->syncItemManager;
	}
	
	/**
	 * Return the SyncTask manager
	 *
	 * @return SyncTaskManager
	 */
	public function SyncTaskManager()
	{
		return $this->syncTaskManager;
	}
	
	/**
	 * Return the SyncTaskType manager
	 *
	 * @return SyncTaskTypeManager
	 */
	public function SyncTaskTypeManager()
	{
		return $this->syncTaskTypeManager;
	}
	
	/**
	 * Return the SyncTaskTypeVersion manager
	 *
	 * @return SyncTaskTypeVersionManager
	 */
	public function SyncTaskTypeVersionManager()
	{
		return $this->syncTaskTypeVersionManager;
	}
	
	/**
	 * Return the SyncTaskStatus manager
	 *
	 * @return SyncTaskStatusManager
	 */
	public function SyncTaskStatusManager()
	{
		return $this->syncTaskStatusManager;
	}
	
	/**
	 * Return the SyncTaskStatusVersion manager
	 *
	 * @return SyncTaskStatusVersionManager
	 */
	public function SyncTaskStatusVersionManager()
	{
		return $this->syncTaskStatusVersionManager;
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
	 * Return the UserHasProject manager
	 *
	 * @return UserHasProjectManager
	 */
	public function UserHasProjectManager()
	{
		return $this->userHasProjectManager;
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
	
	/**
	 * Return the I18nLang manager
	 *
	 * @return I18nLangManager
	 */
	public function I18nLangManager()
	{
		return $this->i18nLangManager;
	}
	
	/**
	 * Return the SearchEngine manager
	 *
	 * @return SearchEngineManager
	 */
	public function SearchEngineManager()
	{
		return $this->searchEngineManager;
	}
	
	/**
	 * Return the SearchUseCaseField manager
	 *
	 * @return SearchUseCaseFieldManager
	 */
	public function SearchUseCaseFieldManager()
	{
		return $this->searchUseCaseFieldManager;
	}
	
	/**
	 * Return the SearchUseCasePreset manager
	 *
	 * @return SearchUseCasePresetManager
	 */
	public function SearchUseCasePresetManager()
	{
		return $this->searchUseCasePresetManager;
	}
	
	/**
	 * Return the SearchUseCasePresetField manager
	 *
	 * @return SearchUseCasePresetFieldManager
	 */
	public function SearchUseCasePresetFieldManager()
	{
		return $this->searchUseCasePresetFieldManager;
	}
	
	/**
	 * Return the Widget manager
	 *
	 * @return WidgetManager
	 */
	public function WidgetManager()
	{
		return $this->widgetManager;
	}
	
	/**
	 * Return the WidgetPreset manager
	 *
	 * @return WidgetPresetManager
	 */
	public function WidgetPresetManager()
	{
		return $this->widgetPresetManager;
	}
}