<?php
/**
 * (c) 2018 OpenTHC, Inc.
 * This file is part of OpenTHC API released under MIT License
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\API\Controller\Doc;

class JSON extends \OpenTHC\Controller\Base
{
	private $src_base;

	function __construct($a)
	{
		parent::__construct($a);
		$this->src_base = sprintf('%s/webroot/pub/json-schema', APP_ROOT);

	}
	/**
	 *
	 */
	function __invoke($REQ, $RES, $ARG)
	{
		$src_path = sprintf('%s/*.json', $this->src_base);
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
			'page_title' => 'OpenTHC JSON Schema Examples',
			'object_file_list' => $out_list,
		);

		return $RES->write( $this->render('json-schema-list', $data) );

	}

	/**
	 * View a Single JSON Object Schema
	 */
	function single($REQ, $RES, $ARG)
	{
		$object_name = basename($ARG['obj'], '.json');
		$schema_name = str_replace('/[^\w\-]+/', null, $object_name);
		$schema_file = sprintf('%s/%s.json', $this->src_base, $schema_name);

		if (!is_file($schema_file)) {
			return $RES->withStatus(404);
		}

		$schema_data = file_get_contents($schema_file);
		$schema_json = json_decode($schema_data, true);

		// @todo Recursive Search Schema JSON for '$ref Keys
		// Then Collect and Build Links

		$data = array(
			'page_title' => 'JSON Schema',
			'name' => $ARG['obj'],
			'json_obj' => $src_json,
			'json_src' => json_encode($schema_json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
			'sample_list' => sprintf('%s/json-example/openthc/*.json', APP_ROOT, $schema_name),
		);

		return $RES->write( $this->render('json-schema-view', $data) );

	}
}
