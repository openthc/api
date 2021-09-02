<?php
/**
 * Show Examples of the JSON is a "nice" way
 */

$this->layout_file = sprintf('%s/view/html.php', APP_ROOT);

echo '<div class="container mt-4">';
echo '<h1>Data Model Examples</h1>';

foreach ($data['example_list'] as $e) {

	echo '<div>';
	printf('<h2 id="%s">%s</h2>', h($e['name']), h($e['name']));
	printf('<pre><code class="language-json hljs">%s</code></pre>', h($e['data']));
	echo '</div>';

}

echo '</div>';
