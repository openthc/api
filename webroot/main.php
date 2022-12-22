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
// $cfg['debug'] = true;
$app = new \OpenTHC\App($cfg);

// Base Data Information
$app->get('/base-data', 'OpenTHC\API\Controller\BaseData');

// Data Model
$app->get('/data-model', 'OpenTHC\API\Controller\DataModel');

// JSON Schema
$app->get('/json-schema', 'OpenTHC\API\Controller\Doc\JSON');
$app->get('/json-schema/[{obj:.*}]', 'OpenTHC\API\Controller\Doc\JSON:single');

// JSON Example
$app->get('/json-example', 'OpenTHC\API\Controller\Example');

// Con
$app->get('/home', 'OpenTHC\API\Controller\Page');
// $app->get('/man', 'OpenTHC\API\Controller\Page');
// $app->get('/ref', 'OpenTHC\API\Controller\Page');

$app->run();
