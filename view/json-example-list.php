<?php
/**
 * (c) 2018 OpenTHC, Inc.
 * This file is part of OpenTHC API released under MIT License
 * SPDX-License-Identifier: MIT
 * Show Examples of the JSON is a "nice" way
 */

$this->layout_file = sprintf('%s/view/html.php', APP_ROOT);

$jump_list = [];
foreach ($data['example_list'] as $x) {
	$jump_list[] = sprintf('<a href="#%s">%s</a>', $x['name'], $x['name']);
}

?>

<div class="container mt-2">

<h1>Example Data</h1>
<!-- <p> -->

<p>Jump to: <?= implode(', ', $jump_list) ?></p>

<?php
foreach ($data['example_list'] as $e) {

	echo '<div>';
	printf('<h2 id="%s">%s</h2>', __h($e['name']), __h($e['name']));
	printf('<pre><code class="language-json hljs">%s</code></pre>', __h($e['data']));
	echo '</div>';

}
?>
</div>
