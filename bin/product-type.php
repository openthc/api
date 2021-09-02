#!/usr/bin/php
<?php
/**
 * Product Type Toolkit
 */

use Edoceo\Radix\DB\SQL;
use Edoceo\Radix\Net\HTTP;

require_once(dirname(__DIR__) . '/boot.php');

if (empty($argv[1])) {
	echo "Say one of 'csv', 'tsv', 'json', or 'sql'\n";
	exit(1);
}

$src_path = APP_ROOT . '/etc/product-type';
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

	foreach ($src_data as $out_data) {

		$rec = array(
			$out_data['id'],
			$out_data['name'],
			$out_data['stub'],
			$out_data['sort'],
			$out_data['biotrack']['path'],
			$out_data['leafdata']['path'],
			$out_data['metrc']['path'],
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

	foreach ($src_data as $out_data) {

		$out_data['meta'] = json_encode([
			'biotrack' => $out_data['biotrack'],
			'leafdata' => $out_data['leafdata'],
			'metrc' => $out_data['metrc'],
		]);

		echo "INSERT INTO product_type (id, name, stub, sort, meta) VALUES (";
		echo "'{$out_data['id']}',";
		echo "'{$out_data['name']}',";
		echo "'{$out_data['stub']}',";
		echo "'{$out_data['sort']}',";
		echo "'{$out_data['meta']}'";
		echo ");";
		echo "\n";

	}

	break;

case 'yaml':

	$d = date(DateTime::RFC3339);

	foreach ($src_data as $out_data) {

		$out_file = sprintf('%s/etc/product-type/%s-next.yaml', APP_ROOT, $out_data['id']);

		echo "Ulid: {$out_data['id']}\n";
		if (empty($out_data['stub'])) {
			$out_data['stub'] = _text_stub($out_data['name']);
		}

		// $out_data = [];
		// $out_data['id'] = $ulid;
		// $out_data['name'] = $pt_data['name']; unset($pt_data['name']);
		// $out_data['mode'] = $pt_data['mode']; unset($pt_data['mode']);
		// $out_data['sort'] = $pt_data['sort']; unset($pt_data['sort']);
		// $out_data['stub'] = null;
		// $out_data['output'] = [];
		// $out_data['output'][] = 'ADD_ULID_HERE';
		// $out_data['biotrack'] = []; unset($pt_data['biotrack']);
		// $out_data['biotrack']['path'] = $pt_data['biotrack_code']; unset($pt_data['biotrack_code']);
		// $out_data['biotrack']['name'] = null;
		// $out_data['leafdata'] = []; unset($pt_data['leafdata']);
		// $out_data['leafdata']['path'] = $pt_data['leafdata_code']; unset($pt_data['leafdata_code']);
		// $out_data['leafdata']['name'] = null;
		// $out_data['metrc'] = []; unset($pt_data['metrc']);
		// $out_data['metrc']['path'] = $pt_data['metrc_code']; unset($pt_data['metrc_code']);
		// $out_data['metrc']['name'] = null;

		$out_data = yaml_emit($out_data, YAML_UTF8_ENCODING, YAML_LN_BREAK);
		// $out_data = "#\n# Generated File $d\n#\n\n$out_data\n";
		// echo "Output To: $out_file\n";

		file_put_contents($out_file, $out_data);

	}

}
