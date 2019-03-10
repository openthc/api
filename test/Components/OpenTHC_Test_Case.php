<?php
/**
 * Test Case Base Class
 */

namespace AppTest\Components;


class OpenTHC_Test_Case extends \PHPUnit\Framework\TestCase // App_TestCase
{
	protected $httpClient; // API Guzzle Client

	protected function setUp() : void
	{
		$this->httpClient = $this->_api();
	}

	/**
		@param $b The Base URL
	*/
	protected function _api($baseURL = null)
	{
		if (empty($baseURL)) {
			$baseURL = $_ENV['api-uri'];
		}

		// create our http client (Guzzle)
		$c = new \GuzzleHttp\Client(array(
			'base_uri' => $baseURL,
			'allow_redirects' => false,
			'debug' => $_ENV['debug_http'],
			'request.options' => array(
				'exceptions' => false,
			),
			'http_errors' => false,
			'cookies' => true,
		));

		return $c;
	}

	protected function auth(string $user, string $pass)
	{
		$response = $this->httpClient->post('/auth/open', $body = [
			'form_params' => [
				'vendor-key' => $_ENV['api-vendor-key'],
				'client-key' => $_ENV['api-client-key'],
			],
		]);
	}

}
