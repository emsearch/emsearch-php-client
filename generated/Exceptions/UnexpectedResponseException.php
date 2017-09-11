<?php

namespace emsearch\Api\Exceptions;

use Exception;
use Psr\Http\Message\ResponseInterface;

/**
 * Api Unexpected Response Exception class
 * 
 * @package emsearch\Api\Exceptions
 */
class UnexpectedResponseException extends ApiException
{
	/**
	 * Response HTTP Code
	 *
	 * @var int
	 */
	protected $httpCode;

	/**
	 * Expected HTTP Code
	 *
	 * @var int
	 */
	protected $expectedHttpCode;

	/**
	 * Psr\Http\Message\ResponseInterface response
	 *
	 * @var ResponseInterface
	 */
	protected $response;

	/**
	 * Construct the exception.
	 *
	 * @param string $httpCode Response HTTP Code
	 * @param string $expectedHttpCode Expected response HTTP Code
	 * @param ResponseInterface $response Psr\Http\Message\ResponseInterface response
	 * @param string $message [optional] The Exception message to throw.
	 * @param int $code [optional] The Exception code.
	 * @param Exception $previous [optional] The previous exception used for the exception chaining.
	 */
	public function __construct($httpCode, $expectedHttpCode, ResponseInterface $response, $message = "", $code = 0, Exception $previous = null) {
		$this->httpCode = $httpCode;
		$this->expectedHttpCode = $expectedHttpCode;
		$this->response = $response;

		if (!$this->message) {
			$this->message = 'Unexpected response HTTP code : ' . $this->httpCode . ' instead of ' . $this->expectedHttpCode;
		}

		parent::__construct($message, $code, $previous);
	}

	/**
	 * {@inheritdoc}
	 */
	public function __toString()
	{
		return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
	}

	/**
	 * Return the response HTTP Code
	 *
	 * @return int
	 */
	public function getHttpCode()
	{
		return $this->httpCode;
	}

	/**
	 * Return the expected response HTTP Code
	 *
	 * @return int
	 */
	public function getExpectedHttpCode()
	{
		return $this->expectedHttpCode;
	}

	/**
	 * Return the Psr\Http\Message\ResponseInterface response
	 *
	 * @return ResponseInterface
	 */
	public function getResponse()
	{
		return $this->response;
	}
}