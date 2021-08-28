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

$cfg = [];
$cfg['debug'] = true;
$app = new \OpenTHC\App($cfg);

// JSON Schema
$app->get('/doc/json-schema', 'App\Controller\Doc\JSON');
$app->get('/doc/json-schema/[{obj:.*}]', 'App\Controller\Doc\JSON:single');

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
