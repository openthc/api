<?php
/**
 * (c) 2018 OpenTHC, Inc.
 * This file is part of OpenTHC API released under MIT License
 * SPDX-License-Identifier: MIT
 */

$this->layout_file = sprintf('%s/view/html.php', APP_ROOT);

$json_output = __h($data['json_src']);

// After we did this, HighlightJS Lost It :(
// $json_output = preg_replace('/&quot;\$ref&quot;: &quot;(\w+?)\.json&quot;/', '&quot;\\$ref&quot;: &quot;<a href="#">$1.json</a>&quot;', $json_output);

?>

<div class="container">
<h1>Schema: <?= __h($data['name']) ?></h1>

<h2>Schema Definition</h2>

<pre><code class="language-json hljs"><?= $json_output ?></code></pre>

<h2>Schema Samples</h2>

</div>
