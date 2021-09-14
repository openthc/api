<?php
/**
 * Product Type Data Viewer
 */

namespace App\Controller;

class ProductType extends \OpenTHC\Controller\Base
{
	private $src_base;

	function __construct($a)
	{
		parent::__construct($a);
		$this->src_base = sprintf('%s/etc/product-type', APP_ROOT);
	}

	/**
	 *
	 */
	function __invoke($REQ, $RES, $ARG)
	{
		$src_path = sprintf('%s/*.yaml', $this->src_base);
		$src_list = glob($src_path);

		$obj_list = [];

		foreach ($src_list as $file) {

			$obj = yaml_parse_file($file);
			// $obj['link'] = sprintf('/product-type/%s', $obj['id']);
			$obj['sort'] = intval($obj['sort']);
			$obj_list[] = $obj;

		}

		usort($obj_list, function($a, $b) {
			if ($a['sort'] == $b['sort']) {
				return strcmp($a['name'], $b['name']);
			}
			return ($a['sort'] > $b['sort']);
		});

		// Data
		$data = [
			'page_title' => 'OpenTHC Product Type Data Examples',
			'object_list' => $obj_list,
		];

		return $RES->write( $this->render('product-type-list', $data) );

	}

	/**
	 * View a Single JSON Data Schema
	 */
	function single($REQ, $RES, $ARG)
	{
		$object_name = basename($ARG['obj'], '.yaml');
		$schema_name = str_replace('/[^\w\-]+/', null, $object_name);
		$schema_file = sprintf('%s/%s.yaml', $this->src_base, $schema_name);

		if (!is_file($schema_file)) {
			return $RES->withStatus(404);
		}

		$schema_data = file_get_contents($schema_file);
		$schema_json = json_decode($schema_data, true);

		// @todo Recursive Search Schema JSON for '$ref Keys
		// Then Collect and Build Links

		$data = array(
			'page_title' => 'Product Type Data Model',
			'name' => $ARG['obj'],
			'json_obj' => $src_json,
			'json_src' => json_encode($schema_json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
		);

		return $RES->write( $this->render('product-type-view', $data) );

	}
}
