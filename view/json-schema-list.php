<?php
/**
 * (c) 2018 OpenTHC, Inc.
 * This file is part of OpenTHC API released under MIT License
 * SPDX-License-Identifier: MIT
 */

$this->layout_file = sprintf('%s/view/html.php', APP_ROOT);

?>

<div class="container">

<h1>JSON Schema <small>(<?= count($data['object_file_list']) ?>)</small></h1>

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
		<td><a href="/json-schema/<?= h($obj['name']) ?>"><?= h($obj['name']) ?></a></td>
	</tr>
<?php
}
?>

</tbody>
</table>

</div>
