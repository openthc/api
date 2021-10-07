<?php
/**
 * (c) 2018 OpenTHC, Inc.
 * This file is part of OpenTHC API released under MIT License
 * SPDX-License-Identifier: MIT
 *
 * A Layout to use for Home Pages and Landers
 */
?>
<!DOCTYPE html>
<html translate="no">
<head>
<meta charset="utf-8">
<meta name="application-name" content="OpenTHC">
<meta name="mobile-web-app-capable" content="yes">
<meta name="google" content="notranslate">
<meta name="theme-color" content="#069420">
<meta name="viewport" content="initial-scale=1, user-scalable=yes">
<link rel="stylesheet" href="https://cdn.openthc.com/bootstrap/5.1.1/bootstrap.min.css" integrity="sha256-sAcc18zvMnaJZrNT4v8J0T4HqzEUiUTlVFgDIywjQek=" crossorigin="anonymous" referrerpolicy="no-referrer">
<link rel="stylesheet" href="https://cdn.openthc.com/css/www/0.0.2/www.css">
<title><?= h($this->data['page_title']) ?></title>
<script src="https://kit.fontawesome.com/7373621fd1.js" crossorigin="anonymous"></script>
</head>
<body>

<nav class="navbar navbar-expand-md navbar-dark bg-dark sticky-top">
	<a class="navbar-brand" href="/"><i class="fas fa-home"></i></a>

	<ul class="navbar-nav mr-auto">
		<li class="nav-item">
			<a class="nav-link" href="/data-model"><i class="far fa-database"></i> Data Model</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="/json-schema"><i class="far fa-project-diagram"></i> JSON Schema</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="/json-example"><i class="far fa-terminal"></i> JSON Examples</a>
		</li>
		<!-- <li class="nav-item">
			<a class="nav-link" href="/product-type"><i class="fas fa-truck-loading"></i> Product Types</a>
		</li> -->
	</ul>

</nav>

<main>
<?= $this->body ?>
</main>

<footer>
	<div><a href="https://openthc.com/">openthc.com</a></div>
	<div><a href="https://openthc.com/about/tos">Terms of Service</a></div>
	<div><a href="https://openthc.com/about/privacy">Privacy</a></div>
</footer>

<script src="https://cdn.openthc.com/jquery/3.4.1/jquery.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<script src="https://cdn.openthc.com/bootstrap/5.1.1/bootstrap.min.js" integrity="sha256-/hGxZHGQ57fXLp+NDusFZsZo/PG21Bp2+hXYV5a6w+g=" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.2.0/styles/atom-one-dark.min.css" integrity="sha512-Jk4AqjWsdSzSWCSuQTfYRIF84Rq/eV0G2+tu07byYwHcbTGfdmLrHjUSwvzp5HvbiqK4ibmNwdcG49Y5RGYPTg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.2.0/highlight.min.js" integrity="sha512-MinqHeqca99q5bWxFNQEQpplMBFiUNrEwuuDj2rCSh1DgeeTXUgvgYIHZ1puBS9IKBkdfLMSk/ZWVDasa3Y/2A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>hljs.highlightAll();</script>

</body>
</html>
