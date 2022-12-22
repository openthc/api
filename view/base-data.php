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

<h1>Base Data</h1>
<p>
Object Types and other special keys.
All of the special objects are identified by well-known ULID, all prefixed with <code>018NY6XC00</code>
</p>

<section>
<h2 id="company-type">
	<button class="btn btn-sm btn-outline-secondary" data-bs-toggle="collapse" data-bs-target="#company-type-list"><i class="fas fa-expand"></i></button>
	Company Types
	<small>[<?= count($data['company_type_list']) ?>]</small>
</h2>
<div class="collapse" id="company-type-list">
<?php
foreach ($data['company_type_list'] as $obj) {
	_echo_object_view($obj);
}
?>
</div>
</section>


<section>
<h2 id="license-type">
	<button class="btn btn-sm btn-outline-secondary" data-bs-toggle="collapse" data-bs-target="#license-type-list"><i class="fas fa-expand"></i></button>
	License Types
	<small>[<?= count($data['license_type_list']) ?>]</small>
</h2>
<div class="collapse" id="license-type-list">
<?php
foreach ($data['license_type_list'] as $obj) {
	_echo_object_view($obj);
}
?>
</div>
</section>


<section>
<h2 id="contact-type">
	<button class="btn btn-sm btn-outline-secondary" data-bs-toggle="collapse" data-bs-target="#contact-type-list"><i class="fas fa-expand"></i></button>
	Contact Types
	<small>[<?= count($data['contact_type_list']) ?>]</small>
</h2>
<div class="collapse" id="contact-type-list">
<?php
foreach ($data['contact_type_list'] as $obj) {
	_echo_object_view($obj);
}
?>
</div>
</section>


<section>
<h2 id="product-type">
	<button class="btn btn-sm btn-outline-secondary" data-bs-toggle="collapse" data-bs-target="#product-type-list"><i class="fas fa-expand"></i></button>
	Product Types
	<small>[<?= count($data['product_type_list']) ?>]</small>
</h2>
<div class="collapse" id="product-type-list">
<?php
foreach ($data['product_type_list'] as $obj) {
	_echo_object_view($obj);
}
?>
</div>
</section>


<section>
<h2 id="lab-metric">
	<button class="btn btn-sm btn-outline-secondary" data-bs-toggle="collapse" data-bs-target="#lab-metric-type-list"><i class="fas fa-expand"></i></button>
	Lab Metric Types (Groups)
	<small>[<?= count($data['lab_metric_type_list']) ?>]</small>
</h2>
<div class="collapse" id="lab-metric-type-list">
<?php
foreach ($data['lab_metric_type_list'] as $obj) {
	_echo_object_view($obj);
}
?>
</div>
</section>


<section>
<h2 id="lab-metric">
	<button class="btn btn-sm btn-outline-secondary" data-bs-toggle="collapse" data-bs-target="#lab-metric-list"><i class="fas fa-expand"></i></button>
	Lab Metric (ie: Test Types)
	<small>[<?= count($data['lab_metric_list']) ?>]</small>
</h2>
<div class="collapse" id="lab-metric-list">
<?php
foreach ($data['lab_metric_list'] as $obj) {
	_echo_object_view($obj);
}
?>
</div>
</section>

</div>


<?php
/**
 *
 */

function _echo_object_view($obj)
{
	echo '<hr>';

	echo '<div>';

	printf('<h3 id="%s">%s <small>', $obj['id'], h($obj['name']));
	printf('<code>%s</code>', $obj['id']);
	if ( ! empty($obj['base']) && ($obj['base'] != $obj['id'])) {
		printf(' base: <a href="#%s"><code>%s</code></a>', $obj['base'], $obj['base']);
	}
	echo '</small></h3>';

	if (!empty($obj['note'])) {
		echo _markdown($obj['note']);
	}

	echo '<ul class="nav nav-tabs" role="tablist">';
	printf('<li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#json%s">JSON</button>', $obj['id']);
	printf('<li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#yaml%s">YAML</button>', $obj['id']);
	echo '</ul>';

	// @todo Tabs
	printf('<div class="tab-content" id="tabs%s">', $obj['id']);

	printf('<div class="tab-pane fade show active" id="json%s" role="tabpanel" aria-labelledby="home-tab">', $obj['id']);
		echo '<pre><code class="language-json hljs">';
		echo json_encode($obj, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
		echo '</code></pre>';
	echo '</div>';

	printf('<div class="tab-pane fade" id="yaml%s" role="tabpanel" aria-labelledby="home-tab">', $obj['id']);
		echo '<pre><code class="language-yaml hljs">';
		echo yaml_emit($obj);
		echo '</code></pre>';
	echo '</div>';

	echo '</div>';

	echo '</div>';

}
