<?php
/**
 * OpenTHC API - Main Controller via Slim
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
$app->get('/json-schema', 'App\Controller\Doc\JSON');
$app->get('/json-schema/[{obj:.*}]', 'App\Controller\Doc\JSON:single');

// JSON Example
$app->get('/json-example', 'App\Controller\Example');

$app->run();
