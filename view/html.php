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
<link rel="stylesheet" href="/vendor/fontawesome/css/all.min.css" integrity="sha256-HtsXJanqjKTc8vVQjO4YMhiqFoXkfBsjBWcX91T1jr8=">
<link rel="stylesheet" href="/vendor/bootstrap/bootstrap.min.css" integrity="sha256-fx038NkLY4U1TCrBDiu5FWPEa9eiZu01EiLryshJbCo=">
<link rel="stylesheet" href="https://cdn.openthc.com/css/www/0.0.2/www.css">
<title><?= __h($this->data['page_title']) ?></title>
</head>
<body>

<nav class="navbar navbar-expand-md navbar-dark bg-dark sticky-top">
<div class="container-fluid">

	<a class="navbar-brand" href="/"><i class="fa-solid fa-house"></i></a>

	<button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#ot-menu-head" aria-expanded="false" aria-controls="ot-menu-head">
		<span class="navbar-toggler-icon"></span>
	</button>

	<div class="navbar-collapse collapse" id="ot-menu-head">
		<ul class="navbar-nav mr-auto">
			<li class="nav-item">
				<a class="nav-link" href="/data-model"><i class="fa-solid fa-database"></i> Data Model</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="/json-schema"><i class="fa-solid fa-sitemap"></i> JSON Schema</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="/json-example"><i class="fa-solid fa-wand-magic-sparkles"></i> JSON Examples</a>
			</li>
			<!-- <li class="nav-item">
				<a class="nav-link" href="/product-type"><i class="fa-solid fa-boxes-packing"></i> Product Types</a>
			</li> -->
		</ul>
	</div>

</div>
</nav>

<main>
<?= $this->body ?>
</main>

<footer class="page-foot">
	<div><a href="https://openthc.com/">openthc.com</a></div>
	<div><a href="https://openthc.com/about/tos">Terms of Service</a></div>
	<div><a href="https://openthc.com/about/privacy">Privacy</a></div>
</footer>

<script src="/vendor/jquery/jquery.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g="></script>
<script src="/vendor/bootstrap/bootstrap.bundle.min.js" integrity="sha256-qlPVgvl+tZTCpcxYJFdHB/m6mDe84wRr+l81VoYPTgQ="></script>

<link rel="stylesheet" href="/vendor/highlight.js/atom-one-dark.min.css" integrity="sha256-Qjf/ynzmqttDjEV+CmdbElxTS73aW4f0HzoUlWA7zJs=" crossorigin="anonymous" referrerpolicy="no-referrer" />
<script src="/vendor/highlight.js/highlight.min.js" integrity="sha256-RJn/k21P1WKtylpcvlEtwZ64CULu6GGNr7zrxPeXS9s=" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>hljs.highlightAll();</script>

</body>
</html>
