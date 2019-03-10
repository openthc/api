<?php
/**
 *
 */
namespace AppTest\OpenTHC\CRE;

class Auth extends \AppTest\Components\OpenTHC_Test_Case
{
	public function testHome() {
		/**
		 * Unauthenticated Tests
		 */
		$response = $this->httpClient->get('/auth');
		$this->assertEquals(200, $response->getStatusCode());

		$response = $this->httpClient->get('/auth', [
			'form_params' => [
				'vendor-key' => 'I am Sam',
				'client-key' => 'Sam I am',
			],
		]);
		$this->assertEquals(200, $response->getStatusCode());

		$response = $this->httpClient->get('/auth', [
			'form_params' => [
				'vendor-key' => 'I am Unauthorized',
				'client-key' => 'Unauthorized I am',
			],
		]);
		$this->assertEquals(403, $response->getStatusCode());

		/**
		 * Authenticated Tests
		 */
	}

	public function testOpen() {
		$response = $this->httpClient->post('/auth/open', $body = [
			'form_params' => [
				'vendor-key' => 'I am Sam',
				'client-key' => 'Sam I am',
			],
		]);
		$this->assertEquals(200, $response->getStatusCode());

		$response = $this->httpClient->post('/auth/open', $body = [
			'form_params' => [
				'vendor-key' => 'I am Unauthorized',
				'client-key' => 'Unauthorized I am',
			],
		]);
		$this->assertEquals(403, $response->getStatusCode());
	}
}
