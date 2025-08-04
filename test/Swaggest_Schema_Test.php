<?php
/**
 * (c) 2018 OpenTHC, Inc.
 * This file is part of OpenTHC API released under MIT License
 * SPDX-License-Identifier: MIT
 */

namespace OpenTHC\API\Test;

class Swaggest_Schema_Test extends \PHPUnit\Framework\TestCase
{

	function test_all_schema()
	{
		// This stupid map cause my names suck
		$source_file_list = glob(sprintf('%s/json-example/openthc/*.json', APP_ROOT));
		$source_file_list = array_combine($source_file_list, $source_file_list);

		$source_data_list = [

			'company' => 'Company',
			'company-type' => 'Company_Type',

			'license' => 'License',
			'license-type' => 'License_Type',

			'product' => 'Product',
			'product-type' => 'Product_Type',

			'section' => 'Section',
			'variety' => 'Variety',

			// 'plant' => 'Plant',
			// 'plant-collect' => 'Plant_Collect',

			'inventory' => 'Inventory',

			'lab-sample' => 'Lab_Sample',
			'lab-result' => 'Lab_Result',
		];


		foreach ($source_data_list as $json_object => $json_schema) {

			// echo "json-object: $json_object\n";

			$object_file = sprintf('%s/json-example/openthc/%s.json', APP_ROOT, $json_object);
			$schema_file = sprintf('%s/webroot/pub/json-schema/%s.json', APP_ROOT, $json_schema);

			$this->assertTrue(is_file($object_file), "Missing: $object_file");
			$this->assertTrue(is_file($schema_file), "Missing: $schema_file");

			$object_data = file_get_contents($object_file);
			$object_data = json_decode($object_data);

			$schema_data = file_get_contents($schema_file);
			$schema_data = json_decode($schema_data);

			// Schema Check with Tool #3
			// $jsv = new Saaggest\JsonValidator();
			$schema = \Swaggest\JsonSchema\Schema::import($schema_data);
			$res = $schema->in($object_data);

		}

	}

}
