#!/usr/bin/env php
<?php
/**
 * OpenTHC API Test Runner
 *
 * SPDX-License-Identifier: MIT
 */

require_once(dirname(__DIR__) . '/boot.php');

// Default Option
if (empty($_SERVER['argv'][1])) {
	$_SERVER['argv'][1] = 'phpunit';
	$_SERVER['argc'] = count($_SERVER['argv']);
}

// Command Line
$doc = <<<DOC
OpenTHC API Test Runner

Usage:
	test <command> [<command-options>...]

Commands:
	all       run all tests
	phplint   run some tests
	phpunit
	phpstan

Options:
	--phpunit-filter=FILTER    Filter to pass to PHPUnit
DOC;

$res = \Docopt::handle($doc, [
	'exit' => false,
	'optionsFirst' => true,
]);
$cli_args = $res->args;
if ('all' == $cli_args['<command>']) {
	$cli_args['phplint'] = true;
	$cli_args['phpstan'] = true;
	$cli_args['phpunit'] = true;
} else {
	$cmd = $cli_args['<command>'];
	$cli_args[$cmd] = true;
	unset($cli_args['<command>']);
}
var_dump($cli_args);


// Test Config
$cfg = [];
$cfg['base'] = APP_ROOT;
$cfg['site'] = 'api';

$test_helper = new \OpenTHC\Test\Helper($cfg);
$cfg['output'] = $test_helper->output_path;


// PHPLint
if ($cli_args['phplint']) {
	echo "PHPLint\n";
	$tc = new \OpenTHC\Test\Facade\PHPLint($cfg);
	$res = $tc->execute();
	$res = $tc->execute(); // 0=Success; 1=Failure
	switch ($res) {
	case 0:
			echo "PHPLint Success\n";
			break;
	case 1:
	default:
			echo "PHPLint Failure ($res)\n";
			break;
	}
}


// Call PHPCS?
// $tc = \OpenTHC\Test\PHPStyle::execute();


// PHPStan
if ($cli_args['phpstan']) {
	echo "PHPStan\n";
	$tc = new \OpenTHC\Test\Facade\PHPStan($cfg);
	$res = $tc->execute();
	var_dump($res);
}


// Psalm/Psalter?


// PHPUnit
// Pick Config File
$cfg_file_list = [];
$cfg_file_list[] = sprintf('%s/phpunit.xml', __DIR__);
$cfg_file_list[] = sprintf('%s/phpunit.xml.dist', __DIR__);
foreach ($cfg_file_list as $f) {
	if (is_file($f)) {
		$cfg['--configuration'] = $f;
		break;
	}
}
// Filter?
if ( ! empty($cli_args['--filter'])) {
	$cfg['--filter'] = $cli_args['--filter'];
}
$tc = new \OpenTHC\Test\Facade\PHPUnit($cfg);
$res = $tc->execute();
var_dump($res);


// Output
$res = $test_helper->index_create($res['data']);
echo "TEST COMPLETE\n  $res\n";
