#!/usr/bin/php
<?php
/**
 * (c) 2018 OpenTHC, Inc.
 * This file is part of OpenTHC API released under MIT License
 * SPDX-License-Identifier: MIT
 *
 * Lab Metric Toolkit
 * CSV Columns: ULID?, Type, Name, UOM?, Stub?, Name-Full?, BioTrack_Path?, LeafData_Path?, METRC_Path?
 */

// use Edoceo\Radix\DB\SQL;
// use Edoceo\Radix\Net\HTTP;

require_once(dirname(__DIR__) . '/boot.php');

if (empty($argv[1])) {
	echo "Say one of 'csv', 'tsv', 'json', or 'sql'\n";
	exit(1);
}


// Load YAML Sources
$src_path = APP_ROOT . '/etc/lab-metric';
$src_list = glob("$src_path/*.yaml");
$src_data = [];
foreach ($src_list as $src_file) {
	$x = yaml_parse_file($src_file, 0);
	if (empty($x['id'])) {
		// don't just auto-correct
		throw new \Exception(sprintf('Invalid Lab Metric, Missing "id", %s', $src_file));
		$x['id'] = basename($src_file, '.yaml');
	}
	if (empty($x['name'])) {
		throw new \Exception(sprintf('Invalid Lab Metric, Missing "name", %s', $src_file));
	}
	if (empty($x['type'])) {
		throw new \Exception(sprintf('Invalid Lab Metric, Missing "type", %s', $src_file));
	}
	$src_data[] = $x;
}

switch ($argv[1]) {
case 'csv':
case 'tsv':

	$sep = ',';
	if ('tsv' == $argv[1]) {
		$sep = "\t";
	}

	$fh = fopen('php://output', 'a');

	// Head
	$rec = array(
		'id',
		'type',
		'name',
		'stub',
		'sort',
		'biotrack_path',
		'biotrack_name',
		'leafdata_path',
		'leafdata_name',
		'metrc_path',
		'metrc_name',
	);
	fputcsv($fh, $rec, $sep);

	foreach ($src_data as $out_data) {

		$rec = array(
			$out_data['id'],
			$out_data['type'],
			$out_data['name'],
			$out_data['stub'],
			$out_data['sort'],
			$out_data['biotrack']['path'],
			$out_data['biotrack']['name'],
			$out_data['leafdata']['path'],
			$out_data['leafdata']['name'],
			$out_data['metrc']['path'],
			$out_data['metrc']['name'],
		);

		fputcsv($fh, $rec, $sep);
	}

	fclose($fh);

	exit(0);

	break;

case 'json':

	echo json_encode($src_data, JSON_PRETTY_PRINT);

	exit(0);

	break;

// Make Examples
case 'example':
case 'json-example':

	$out = [];
	$out['id'] = '018NY6XC006M4V3ZM8M825Z38K';
	$out['lab_sample'] = [
		'id' => '018NY6XC00GA65ZTCZ4FM9FQ8Z',
	];
	$out['metric_list'] = [];

	$enum_status = [ 'fail', 'na', 'nd', 'nt', 'pass' ];

	foreach ($src_data as $m) {

		// print_r($m); exit;

		$out['metric_list'][] = [
			'id' => $m['id'],
			'name' => $m['name'],
			'type' => $m['type'],
			'sort' => $m['sort'],
			'uom' => $m['uom'],
			'qom' => rand(10, 1000) / 100,
			'lod' => rand(10, 50) / 100,
			'loq' => rand(10, 50) / 200,
			'status' => $enum_status[ array_rand($enum_status) ],
		];
	}

	echo json_encode($out, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

	break;


case 'sql':

	// $cfg = \OpenTHC\Config::get('database/main');
	// $c = sprintf('pgsql:host=%s;dbname=%s', $cfg['hostname'], $cfg['database']);
	// $u = $cfg['username'];
	// $p = $cfg['password'];
	// $dbc = new \Edoceo\Radix\DB\SQL($c, $u, $p);

	foreach ($src_data as $out_data) {

		$lm = array(
			'id' => $out_data['id'],
			'type' => $out_data['type'],
			'name' => $out_data['name'],
			'meta' => json_encode([
				'uom' => $out_data['uom'],
				'stub' => $out_data['stub'],
				'cre' => [
					'biotrack' => $out_data['biotrack'],
					'leafdata' => $out_data['leafdata'],
					'metrc' => $out_data['metrc'],
				]
			]),
		);

		$k = implode(',', array_keys($lm));
		$v = implode("', '", array_values($lm));

		echo "INSERT INTO lab_metric ($k) VALUES ('$v');\n";

		// try {
		// 	$dbc->insert('lab_metric', $lm);
		// } catch (\Exception $e) {
		// 	// Ignore
		// 	echo $e->getMessage();
		// 	echo "\n";
		// }

	}

	break;

// case 'export':
// 	sql2xxx();
// 	break;
//
// case 'yaml':
//
// 	// // parse
// 	$out_data = yaml_emit($out_data, YAML_UTF8_ENCODING, YAML_LN_BREAK);
// 	file_put_contents($out_file, $out_data);
//
// 	break;
//
}
