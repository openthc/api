<?php
/**
 * OpenTHC API - Front Controller via Slim
 *
 * This file is part of OpenTHC API Specifications
 *
 * OpenTHC API is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License version 3 as published by
 * the Free Software Foundation.
 *
 * OpenTHC API is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with OpenTHC API.  If not, see <https://www.gnu.org/licenses/>.
 */

require_once(dirname(dirname(__FILE__)) . '/boot.php');

$app = new \OpenTHC\App();

// JSON Schema
$app->get('/json-schema[/{obj}]', function($REQ, $RES, $ARG) {

	$src_base = $src_path = sprintf('%s/json-schema/openthc', APP_ROOT);

	if (empty($ARG['obj'])) {

		$src_path = sprintf('%s/*.json', $src_base);
		$src_list = glob($src_path);

		$out_list = array();

		foreach ($src_list as $f) {

			$b = basename($f, '.json');

			// Skip these files
			$chk = substr($b, 0, 3);
			if (('_de' == $chk) || ('all' == $chk)) {
				continue;
			}

			$d = file_get_contents($f);

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
	$schema_file = sprintf('%s/%s.json', $src_base, $schema_name);

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
