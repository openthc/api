#!/usr/bin/php
<?php
/**
 * OpenTHC Test Runner
 *
 * SPDX-License-Identifier: GPL-3.0-only
 */

require_once(dirname(__DIR__) . '/boot.php');

// Config
$cfg = [];
$cfg['base'] = APP_ROOT;
$cfg['site'] = 'api';

$test_helper = new \OpenTHC\Test\Helper($cfg);

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

$res = Docopt::handle($doc, [
	'exit' => false,
	'help' => true,
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

$dt0 = new \DateTime();

$cfg = [];
$cfg['output'] = $test_helper->output_path;

// PHPLint
$tc = new \OpenTHC\Test\Facade\PHPLint($cfg);
// $res = $tc->execute();
// var_dump($res);

// Call PHPCS?
// $tc = \OpenTHC\Test\PHPStyle::execute();


// PHPStan
$tc = new \OpenTHC\Test\Facade\PHPStan($cfg);
// $res = $tc->execute();
// var_dump($res);


// Psalm/Psalter?


// PHPUnit
if (is_file(__DIR__ . '/phpunit.xml')) {
	$cfg['--configuration'] = sprintf('%s/phpunit.xml', __DIR__);
}
if (is_file(__DIR__ . '/phpunit.xml.dist')) {
	echo "Using phpunit.xml.dist\n";
	$cfg['--configuration'] = sprintf('%s/phpunit.xml.dist', __DIR__);
}
// Filter?
if ( ! empty($cli_args['--filter'])) {
	$cfg['--filter'] = $cli_args['--filter'];
}
$tc = new \OpenTHC\Test\Facade\PHPUnit($cfg);
$res = $tc->execute();
var_dump($res);


// Done
$res = $test_helper->index_create($res['data']);

echo "TEST COMPLETE\n  $origin/$output\n";
