<?php
/**
 * OpenTHC API - Main Controller via Slim
 *
 * This file is part of OpenTHC API Specifications
 *
 * SPDX-License-Identifier: MIT
 */

require_once(dirname(dirname(__FILE__)) . '/boot.php');

$cfg = [];
$cfg['debug'] = true;
$app = new \OpenTHC\App($cfg);

$app->get('/data-model', 'App\Controller\DataModel');

// $app->get('/index', '');

// JSON Schema
$app->get('/json-schema', 'App\Controller\Doc\JSON');
$app->get('/json-schema/[{obj:.*}]', 'App\Controller\Doc\JSON:single');

// JSON Example
$app->get('/json-example', 'App\Controller\Example');

// Base Data Information
$app->get('/base-data', 'App\Controller\BaseData');

$app->run();
