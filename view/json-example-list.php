<?php
/**
 * Show Examples of the JSON is a "nice" way
 */

$this->layout_file = sprintf('%s/view/html.php', APP_ROOT);

echo '<div class="container mt-4">';
echo '<h1>Data Model Examples</h1>';

foreach ($data['example_list'] as $e) {
	echo '<div>';
	echo '<h2>' . h($e['name']) . '</h2>';
	echo '<pre><code class="language-json hljs">';
	echo h($e['data']);
	echo '</code></pre>';
	echo '</div>';
}

echo '</div>';
