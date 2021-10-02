<?php
/**
 * (c) 2018 OpenTHC, Inc.
 * This file is part of OpenTHC API released under MIT License
 * SPDX-License-Identifier: MIT
 *
 */

$this->layout_file = sprintf('%s/view/html.php', APP_ROOT);

$jump_list = [];
foreach ($data['model_list'] as $mk => $mv) {
	$jump_list[] = sprintf('<a href="#%s">%s</a>', $mk, $mk);
}

?>

<div class="container">

<h1>Data Model</h1>
<p>Jump to: <?= implode(', ', $jump_list) ?></p>

<hr>


<?php
foreach ($data['model_list'] as $mk => $mv) {

	echo '<hr>';

	echo '<div class="data-model">';

	printf('<h2 id="%s">%s</h2>', rawurlencode($mk), h($mk));
	if (!empty($mv['description'])) {
		echo _markdown($mv['description']);
	}
	if (!empty($mv['properties'])) {

		echo '<dl>';
		foreach ($mv['properties'] as $pk => $pv) {
			if ( ! empty($pv['$ref'])) {
				// It's another Type
				$pv['type'] = '$ref';
				$pv['description'] = $pv['$ref'];
				printf('<dt>%s <sup class="badge badge-info">%s</sup></dt>', $pk, $pv['type']);
				printf('<dd>%s</dd>', h($pv['description']));
			} else {
				printf('<dt>%s <sup class="badge badge-info">%s</sup></dt>', $pk, $pv['type']);
				printf('<dd>%s</dd>', h($pv['description']));
			}
		}
		echo '</dl>';
	}
	// echo '<pre>';
	// var_dump($mv);
	// echo '</pre>';
	echo '</div>';

}
?>
</div>
