<?php
ob_start();
?>
	<footer>
		<p>FOOTER</p>
	</footer>
<?php
$page['footer'] = ob_get_clean();