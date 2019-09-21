#!/usr/bin/php
<?php
/**
 * Lab Metric Toolkit
 * CSV Columns: ULID?, Type, Name, UOM?, Stub?, Name-Full?, BioTrack_Path?, LeafData_Path?, METRC_Path?
 */

use Edoceo\Radix\DB\SQL;
use Edoceo\Radix\Net\HTTP;

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
	$src_data[] = yaml_parse_file($src_file, 0);
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

case 'sql':

	$cfg = \OpenTHC\Config::get('database_main');
	$c = sprintf('pgsql:host=%s;dbname=%s', $cfg['hostname'], $cfg['database']);
	$u = $cfg['username'];
	$p = $cfg['password'];
	$dbc = new \Edoceo\Radix\DB\SQL($c, $u, $p);

	foreach ($src_data as $out_data) {

		$dbc->insert('lab_metric', array(
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
		));

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
