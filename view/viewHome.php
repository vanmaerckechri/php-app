<?php
ob_start();
?>
	<div id="main">
		<p>MAIN</p>
	</div>
<?php
$page['content'] = ob_get_clean();