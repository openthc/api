<?php
/**
	Test the Authentication
*/

use GuzzleHttp\Psr7\Request;

class OpenTHC_Authentication_Test extends \AppTest\Components\OpenTHC_Test_Case
{
	function testAuthPlain()
	{
		$api = $this->_api();

		$res = $api->post('/auth/open', array(
			'form_params' => array(
				'business' => '123456789',
				'username' => 'test@openthc.org',
				'password' => 'password',
				'client-key' => $_ENV['api-client-key'],
				'vendor-key' => $_ENV['api-vendor-key'],
			)
		));

		$hsc = $res->getStatusCode();

		//$buf = $res->getBody()->__toString();
		// echo "\n>>> $hsc\n";
		// print_r($buf);
		// echo "\n<<<\n";

		$this->assertEquals(200, $res->getStatusCode());
		$this->assertEquals('application/json;charset=utf-8', $res->getHeaderLine('Content-Type'));

		$res = json_decode($res->getBody(), true);
		$this->assertEquals('success', $res['status']);

		// $this->assertRegEx('success', /\w{64,256}/');

	}

	/**
	 * Conenct via oAuth2
	 */
	function _testOAuth()
	{
		$api = $this->_api();
		$res = $api->post('/auth/open', array(
			'client_id' => $_ENV['api-oauth-client-id'],
			'redirect_uri' => 'OPENTHC_TEST_REDIRECT_URI',
			'response_type' => 'code',
			//'scope' => 'photos',
			//'state' => '1234zyx'
		));

		$this->assertEquals(200, $res->getStatusCode());
		$this->assertEquals('application/json;charset=utf-8', $res->getHeaderLine('Content-Type'));

		//print_r($res);
		// Code and State

		$res = $api->post('/token', array(
			'client_id' => 'OPENTHC_TEST_CLIENT_ID',
			'client_secret' => 'OPENTHC_TEST_CLIENT_SECRET',
			'redirect_uri' => 'OPENTHC_TEST_REDIRECT_URI',
			'grant_type' => 'authorization_code',
			'code' => '',
		));

		$this->assertEquals(200, $res->getStatusCode());
		$this->assertEquals('application/json;charset=utf-8', $res->getHeaderLine('Content-Type'));

		$res = json_decode($res->getBody(), true);

		//print_r($res);
		// Access Token, Expires In

		$head = array(
			sprintf('Authorization: Bearer %s', $res['token']),
		);
		$req = new \GuzzleHttp\Client\Request('GET', '/auth/ping', $head);

		$res = $res->send($req);
		$this->assertEquals(200, $res->getStatusCode());
		$this->assertEquals('application/json;charset=utf-8', $res->getHeaderLine('Content-Type'));
		//print_r($res);

	}
}
