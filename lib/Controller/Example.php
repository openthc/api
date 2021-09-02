<?php
/**
 *
 */

namespace App\Controller;

class Example extends \OpenTHC\Controller\Base
{
	private $src_base;

	function __construct($a)
	{
		parent::__construct($a);
		$this->src_base = sprintf('%s/json-example/openthc', APP_ROOT);
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
				'data' => $d,
			);

		}

		// Data
		$data = array(
			'page_title' => 'OpenTHC JSON Data Examples',
			'example_list' => $out_list,
		);

		return $RES->write( $this->render('json-example-list', $data) );

	}

	/**
	 * View a Single JSON Data Schema
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
			'page_title' => 'JSON Data Model',
			'name' => $ARG['obj'],
			'json_obj' => $src_json,
			'json_src' => json_encode($schema_json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
		);

		return $RES->write( $this->render('json-example-view', $data) );

	}
}
