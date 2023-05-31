#!/usr/bin/php
<?php
/**
 * (c) 2018 OpenTHC, Inc.
 * This file is part of OpenTHC API released under MIT License
 * SPDX-License-Identifier: MIT
 *
 * Merges all the little YAML to big YAML
 * @see http://azimi.me/2015/07/16/split-swagger-into-smaller-files.html
 * https://online.swagger.io/validator/debug?url=https://api.openthc.org/openapi.yaml
 */

require_once(dirname(__DIR__) . "/boot.php");

$src_file = APP_ROOT . "/openapi/openapi.yaml";
if ( ! empty($argv[1])) {
	$src_file = $argv[1];
}

$yaml_data = yaml_parse_file($src_file, 0);
$yaml_data = _resolve_ref($yaml_data, $src_file);

// Create JSON-SCHEMA files from the OpenAPI Data
foreach ($yaml_data['components']['schemas'] as $s_name => $s_data) {

	$output_data = [];
	$output_file = sprintf('%s/webroot/pub/json-schema/%s.json'
		, APP_ROOT
		, $s_name
	);

	$output_data = $s_data;
	foreach ($output_data['properties'] as $pk => $pv) {
		if ('ulid' == $pv['type']) {
			$output_data['properties'][$pk]['type'] = 'string';
		}
	}
	$output_data['$schema'] = 'http://json-schema.org/schema#';
	_ksort_r($output_data);

	file_put_contents($output_file, json_encode($output_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));

}


$yaml_text = yaml_emit($yaml_data, YAML_UTF8_ENCODING, YAML_LN_BREAK);

echo "#\n# Generated File\n#\n\n";
echo $yaml_text;

/**
 * Helper on YAML Processor
 */
function _resolve_ref($node, $file)
{
	static $depth = 0;
	$depth++;

	if (is_array($node)) {

		$key_list = array_keys($node);

		//foreach ($node as $k => $v) {
		foreach ($key_list as $key) {

			$val = $node[$key];

			//echo str_repeat('+', $depth) . "Checking: $key\n";

			if (('$ref' === $key) && ('#' != substr($val, 0, 1))) {

				$load_path = dirname($file) . '/' . $val;
				$load_path = realpath($load_path);

				if (empty($load_path)) {
					var_dump($load_path);
					var_dump($node);
					var_dump($key);
					die("noPath\n");
				}

				// syslog(LOG_DEBUG, "yaml_parse_file($load_path)");

				$load_data = yaml_parse_file($load_path);
				if (empty($load_data)) {
					echo "Failed to Load: $load_path\n";
					exit(1);
				}

				$load_data = _resolve_ref($load_data, $load_path);

				$node = array_merge($node, $load_data);
				unset($node['$ref']);

			} else {
				$val = _resolve_ref($val, $file);
				$node[$key] = $val;
			}

		}

	} elseif (is_string($node)) {
		// echo str_repeat('+', $depth) . "String: $node\n";
	}

	$depth--;

	return $node;
}
