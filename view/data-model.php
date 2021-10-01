<?php
/**
 * (c) 2018 OpenTHC, Inc.
 * This file is part of OpenTHC API released under MIT License
 * SPDX-License-Identifier: MIT
 *
 */

$this->layout_file = sprintf('%s/view/html.php', APP_ROOT);
?>

<div class="container">

<h1>Data Model</h1>

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
			printf('<dt>%s <small class="badge badge-info">%s</small></dt>', $pk, $pv['type']);
			printf('<dd>%s</dd>', h($pv['description']));
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
