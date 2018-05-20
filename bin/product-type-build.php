#!/usr/bin/php
<?php
/**
	Merges all the little YAML to big YAML
*/

if (empty($argv[1])) {
	echo "Say one of 'csv', 'json', or 'sql'\n";
	exit(1);
}


$d = __DIR__;
$d = dirname($d);

$ini_file = sprintf('%s/etc/product-type.ini', $d);
$ini_data = parse_ini_file($ini_file, true, INI_SCANNER_TYPED);




switch ($argv[1]) {
case 'csv':

	$fh = fopen('php://output', 'a');

	foreach ($ini_data as $pt_stub => $pt_data) {

		$rec = array(
			$pt_stub,
			$pt_data['name'],
			$pt_data['sort']
		);

		fputcsv($fh, $rec);
	}

	fclose($fh);

	exit(0);

	break;
case 'json':

	echo json_encode($ini_data, JSON_PRETTY_PRINT);

	exit(0);

	break;
case 'sql':

	$dbc = new PDO(sprintf('sqlite:%s/product-type.sql', $d));
	$dbc->query('CREATE TABLE product_type (code, name, mode, unit)');

	$ins = $dbc->prepare('INSERT INTO product_type VALUES (?, ?, ?, ?)');

	foreach ($ini_data as $pt_stub => $pt_data) {

		$ins->execute(array(
			$pt_stub,
			$pt_data['name'],
			$pt_data['mode'],
			$pt_data['unit'],
		));

	}

	break;
}

