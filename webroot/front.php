<?php
/**
	Front Controller - via Slim
*/

use Edoceo\Radix;
use Edoceo\Radix\Session;

require_once(dirname(dirname(__FILE__)) . '/boot.php');
require_once('/opt/common/lib/App.php');

//require_once(APP_ROOT . '/lib/Middleware/Auth.php');
//// require_once(APP_ROOT . '/lib/Middleware/Auth_OpenTHC.php');
//require_once(APP_ROOT . '/lib/Middleware/LogHTTP.php');
//require_once(APP_ROOT . '/lib/Middleware/ParseJSON_BT.php');

$app = new \OpenTHC\App;

// JSON Schema
$app->get('/json-schema[/{obj}]', function($REQ, $RES, $ARG) {

	if (empty($ARG['obj'])) {

		$src_path = sprintf('%s/json-schema/*.json', APP_ROOT);
		$src_list = glob($src_path);

		$out_list = array();

		foreach ($src_list as $f) {

			$b = basename($f, '.json');
			if ('_' == substr($b, 0, 1)) {
				continue;
			}

			$d = file_get_contents($f);
			//$j = json_decode($d, true);

			$out_list[] = array(
				'name' => $b,
			);

		}

		// Data
		$data = array(
			'object_file_list' => $out_list,
		);

		return $this->view->render($RES, 'page/json-schema-list.html', $data);

	}

	$object_name = basename($ARG['obj'], '.json');
	$schema_name = str_replace('/[^\w\-]+/', null, $object_name);
	$schema_file = sprintf('%s/json-schema/%s.json', APP_ROOT, $schema_name);

	if (!is_file($schema_file)) {
		return $RES->withStatus(404);
	}

	$schema_data = file_get_contents($schema_file);
	$schema_json = json_decode($schema_data, true);

	// @todo Load Samples
	$sample_path = sprintf('%s/json-sample/%s*.json', APP_ROOT, $schema_name);

	$data = array(
		'name' => $ARG['obj'],
		'json_obj' => $src_json,
		'json_src' => json_encode($schema_json, JSON_PRETTY_PRINT),
	);

	return $this->view->render($RES, 'page/json-schema-view.html', $data);

});

// Home Request
$app->get('/', function($req, $res, $arg) {

	require_once(APP_ROOT . '/view/home.php');

	return $res;

})->add(function($req, $res, $ncb) {

	$_ENV['title'] = 'OpenTHC API';

	ob_start();
	require_once(APP_ROOT . '/layout/html-head.php');
	$buf = ob_get_clean();
	$res->getBody()->write($buf);

	$res = $ncb($req, $res);

	ob_start();
	require_once(APP_ROOT . '/layout/html-foot.php');
	$buf = ob_get_clean();
	$res->getBody()->write($buf);

	return $res;
});

$app->run();
