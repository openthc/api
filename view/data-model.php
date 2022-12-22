<?php
/**
 * Describe the Data Models
 *
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

<style>
div:target {
	background: #ffff0099;
}
.data-model {
	font-size: 1.2rem;
}
.data-model h2 {
	margin-bottom: 0.75rem;
}
.data-model p {
	text-indent: 1rem;
}
.data-model dl {
	/* border: 1px solid #ddd; */
	margin: 0 0 0.50rem 0;
	padding: 0.25rem;
}
.data-model dl dd {
	text-indent: 2rem;
}
.data-model dl dt sup {
	margin-left: 0.50rem;
}

</style>


<div class="container mt-2">

<h1>Data Model</h1>
<p>Brief on the OpenTHC Data Model.
More details are in the <a href="https://github.com/openthc/api/tree/master/openapi/components/schema">source YAML</a>
and <a href="/doc/openapi-html-v2/">other references</a>.
</p>

<p>Jump to: <?= implode(', ', $jump_list) ?></p>

<?php
foreach ($data['model_list'] as $mk => $mv) {

	echo '<hr>';

	printf('<div id="%s" class="data-model">', rawurlencode($mk));

	printf('<h2>%s</h2>', h($mk));

	if (!empty($mv['description'])) {
		echo _markdown($mv['description']);
	}
	if (!empty($mv['properties'])) {

		echo '<dl>';
		foreach ($mv['properties'] as $pk => $pv) {

			// REmap
			if (!empty($pv['format'])) {
				$pv['type'] = sprintf('%s [%s]', $pv['type'], $pv['format']);
			}

			if ( ! empty($pv['$ref'])) {
				// It's another Type
				$pv['type'] = '$ref';
				$pv['description'] = $pv['$ref'];
				printf('<dt>%s <sup class="badge bg-info">%s</sup></dt>', $pk, $pv['type']);
				printf('<dd><a href="#%s">%s</a></dd>', basename($pv['$ref']), h($pv['description']));
			} else {
				printf('<dt>%s <sup class="badge bg-info">%s</sup></dt>', $pk, $pv['type']);
				printf('<dd>%s</dd>', h($pv['description']));
			}

			if ( ! empty($pv['enum'])) {
				printf('<dd><em>enum:</em> <code>%s</code></dd>', implode('</code>, <code>', $pv['enum']));
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
