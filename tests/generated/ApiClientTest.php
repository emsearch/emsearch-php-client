<?php

namespace Emsearch\Api\Tests;

use PHPUnit\Framework\TestCase;
use Emsearch\Api\ApiClient;

/**
 * emsearch API client test class (test for version 1.0)
 * 
 * @package Emsearch\Api\Tests
 */
class ApiClientTest extends TestCase
{
	public function testCanCreateClient()
	{
		$apiClient = new ApiClient(
			getenv('bearerToken'),
			getenv('apiBaseUrl')
		);

		$this->assertNotNull(
			$apiClient->getHttpClient()
		);
	}
}