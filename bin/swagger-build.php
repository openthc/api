#!/usr/bin/php
<?php
/**
 * Merges all the little YAML to big YAML
 */

openlog('openthc-api', LOG_PERROR, LOG_LOCAL7);

$src_path = '/opt/api.openthc.org/swagger';
$src_root = '/opt/api.openthc.org/swagger/_.yaml';

$yaml = yaml_parse_file($src_root, 0);
//print_r($yaml);

$yaml = _resolve_ref($yaml, $src_root);

echo yaml_emit($yaml);

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
				//$load_path = realpath($load_path);

				if (empty($load_path)) {
					var_dump($load_path);
					var_dump($node);
					var_dump($key);
					die("noPath\n");
				}

				syslog(LOG_DEBUG, "yaml_parse_file($load_path)");

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
