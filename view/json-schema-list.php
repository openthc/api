<?php
/**
 *
 */

$this->layout_file = sprintf('%s/view/html.php', APP_ROOT);

//  {% extends "layout/base.html" %}
?>

<h1>Schema <small>(<?= count($data['object_file_list']) ?>)</small></h1>

<table class="table table-sm table-hover">
<thead class="thead-dark">
<tr>
	<th>Object</th>
</tr>
</thead>
<tbody>
<?php
foreach ($data['object_file_list'] as $obj) {
?>
	<tr>
		<td><a href="/doc/json-schema/<?= h($obj['name']) ?>"><?= h($obj['name']) ?></a></td>
	</tr>
<?php
}
?>

</tbody>
</table>