<?php
/**
 *
 *
 */

$this->layout_file = sprintf('%s/view/html.php', APP_ROOT);
?>

<div class="container">

<?php
foreach ($data['model_list'] as $mk => $mv) {

	echo '<div>';
	printf('<h2>%s</h2>', h($mk));
	if (!empty($mv['description'])) {
		echo _markdown($mv['description']);
	}
	if (!empty($mv['properties'])) {
		echo '<dl>';
		foreach ($mv['properties'] as $pk => $pv) {
			printf('<dt>%s</dt>', $pk);
			printf('<dd>[%s] %s</dd>', $pv['type'], h($pv['description']));
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
