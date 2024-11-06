#!/usr/bin/php
<?php
/**
 *
 */

namespace OpenTHC\Test;

require_once(dirname(__DIR__) . '/boot.php');

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
	'help' => true,
	'optionsFirst' => true,
]);
var_dump($res);
$arg = $res->args;
var_dump($arg);
if ('all' == $arg['<command>']) {
	$arg['phplint'] = true;
	$arg['phpstan'] = true;
	$arg['phpunit'] = true;
} else {
	$cmd = $arg['<command>'];
	$arg[$cmd] = true;
	unset($arg['<command>']);
}
var_dump($arg);

$dt0 = new \DateTime();

define('OPENTHC_TEST_OUTPUT_BASE', \OpenTHC\Test\Helper::output_path_init());

// PHPLint
$tc = new \OpenTHC\Test\Facade\PHPLint([
	'output' => OPENTHC_TEST_OUTPUT_BASE
]);
// $res = $tc->execute();
// var_dump($res);

// Call PHPCS?
// $tc = \OpenTHC\Test\PHPStyle::execute();


// PHPStan
$tc = new \OpenTHC\Test\Facade\PHPStan([
	'output' => OPENTHC_TEST_OUTPUT_BASE
]);
// $res = $tc->execute();
// var_dump($res);


// Psalm/Psalter?


// PHPUnit
$cfg = [
	'output' => OPENTHC_TEST_OUTPUT_BASE
];
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
\OpenTHC\Test\Helper::index_create($html);


// Output Information
$origin = \OpenTHC\Config::get('openthc/api/origin');
$output = str_replace(sprintf('%s/webroot/', APP_ROOT), '', OPENTHC_TEST_OUTPUT_BASE);

echo "TEST COMPLETE\n  $origin/$output\n";
