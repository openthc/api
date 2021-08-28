<?php
/**
 *
 */

header('content-type: text/plain');

$this->layout_file = sprintf('%s/view/html.php', APP_ROOT);

$json_output = h($data['json_src']);

// After we did this, HighlightJS Lost It :(
// $json_output = preg_replace('/&quot;\$ref&quot;: &quot;(\w+?)\.json&quot;/', '&quot;\\$ref&quot;: &quot;<a href="#">$1.json</a>&quot;', $json_output);

?>

<h1>Schema: <?= h($data['name']) ?></h1>

<h2>Schema Definition</h2>
<pre><code class="language-json hljs"><?= $json_output ?></code></pre>

<h2>Schema Samples</h2>
