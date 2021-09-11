<?php
/**
 *
 *
 */

$this->layout_file = sprintf('%s/view/html.php', APP_ROOT);
?>

<div class="container">

<h1>Product Types</h1>
<p>We've put all the product type base data here as both JSON and YAML.</p>

<?php
foreach ($data['object_list'] as $obj) {

	echo '<hr>';

	echo '<div class="product-type">';

	printf('<h2 id="%s">%s</h2>'
		, $obj['id']
		, h($obj['name'])
	);

	if (!empty($obj['note'])) {
		echo _markdown($obj['note']);
	}
	echo '<p>Object ID: ';
	printf('<code>%s</code>', $obj['id']);
	if ( ! empty($obj['base'])) {
		printf(' Base Object: <a href="#%s"><code>%s</code></a>', $obj['base'], $obj['base']);
	}

	// $obj = [
	// 	'biotrack' => $obj['biotrack']['name'],
	// 	'lcb' => $obj['lcbccrs'],
	// 	'leafdata' => $obj['leafdata']['path'],
	// 	'metrc' => $obj['metrc']['path'],
	// ];

	echo '<pre style="max-height: 10em; overflow: auto;"><code class="language-json hljs">';
	// echo '<pre><code class="language-json hljs">';
	echo json_encode($obj, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
	echo '</code></pre>';

	echo '<pre style="max-height: 10em; overflow: auto;"><code class="language-yaml hljs">';
	echo yaml_emit($obj);
	echo '</code></pre>';

	echo '</div>';

}
?>
</div>
