#!/usr/bin/php
<?php
/**
 * Lab Metric Toolkit
 * CSV Columns: ULID?, Type, Name, UOM?, Stub?, Name-Full?, BioTrack_Path?, LeafData_Path?, METRC_Path?
 */

use Edoceo\Radix\DB\SQL;
use Edoceo\Radix\Net\HTTP;

require_once(dirname(__DIR__) . '/boot.php');

// Action!
$action = $argv[1];
switch ($action) {
case 'import':
	csv2sql(APP_ROOT . '/etc/lab-metric.tsv');
	break;
case 'export':
	sql2xxx();
	break;
default:
	echo "Give 'import' or 'export' as action'\n";
	exit(1);
}

exit(0);

function csv2sql($tsv_file)
{
	$cfg = \OpenTHC\Config::get('database_main');
	$c = sprintf('pgsql:host=%s;dbname=%s', $cfg['hostname'], $cfg['database']);
	$u = $cfg['username'];
	$p = $cfg['password'];
	$dbc = new \Edoceo\Radix\DB\SQL($c, $u, $p);


	$tsv_file = realpath($tsv_file);

	$fh = fopen($tsv_file, 'r');
	while ($rec = fgetcsv($fh, "\t")) {

		if (empty($rec[1]) && empty($rec[2])) {
			continue;
		}

		// If no ULID, make one
		if (empty($rec[0])) {
			$res = HTTP::get('https://cre.openthc.com/ulid?p=LM&t=2014-04-20T00:00:00Z');
			$rec[0] = $res['body'];
		}

		$ext = array_slice($rec, 3);

		$dbc->insert('lab_metric', array(
			'id' => $rec[0],
			'type' => $rec[1],
			'name' => $rec[2],
			'meta' => json_encode(array(
				'uom' => $ext[0],
				'stub' => $ext[1],
				'name_full' => $ext[2],
				'cre' => array(
					'biotrack_path' => $ext[3],
					'leafdata_path' => $ext[4],
					'metrc_path' => $ext[5],
				)
			)),
		));

	}

}

function sql2xxx($t=null)
{
	$cfg = \OpenTHC\Config::get('database_main');
	$c = sprintf('pgsql:host=%s;dbname=%s', $cfg['hostname'], $cfg['database']);
	$u = $cfg['username'];
	$p = $cfg['password'];
	$dbc = new \Edoceo\Radix\DB\SQL($c, $u, $p);

	$res_lab_metric = $dbc->fetchAll('SELECT * FROM lab_metric ORDER BY type, name');

	$csv_data = [];
	$ini_data = [];
	$json_data = [];
	$xml_data = [];

	foreach ($res_lab_metric as $lm) {

		$lm['meta'] = json_decode($lm['meta'], true);

		$ini_data[] = sprintf('[%s]', $lm['id']);
		$ini_data[] = "\ttype = \"{$lm['type']}\"";
		$ini_data[] = "\tname = \"{$lm['name']}\"";
		$ini_data[] = "\tuom = \"{$lm['meta']['uom']}\"";
		$ini_data[] = ""; // Blank Lin

		$json_data[] = $lm;

		$row = array();
		$row[] = $lm['id'];
		$row[] = $lm['type'];
		$row[] = $lm['name'];
		$row[] = $lm['meta']['uom'];
		$row[] = $lm['meta']['stub'];
		$row[] = $lm['meta']['name_full'];
		$row[] = $lm['meta']['cre']['biotrack_path'];
		$row[] = $lm['meta']['cre']['leafdata_path'];
		$row[] = $lm['meta']['cre']['metrc_path'];

		$tab_data[] = implode("\t", $row);

		// $xml_data[] =

	}

	$ini_file = '/tmp/lab-metric.ini';
	$json_file = '/tmp/lab-metric.json';
	$tab_file = '/tmp/lab-metric.tsv';

	file_put_contents($ini_file, implode("\n", $ini_data));
	file_put_contents($json_file, json_encode($json_data, JSON_PRETTY_PRINT));
	file_put_contents($tab_file, implode("\n", $tab_data));

	echo "Files in:\n $ini_file\n $json_file\n $tab_file\n";
}
