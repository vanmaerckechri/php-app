<?php
ob_start();
?>
	<header>
		<h1><?= $page['h1'] ?></h1>
		<p>HEADER</p>
	</header>
<?php
$page['header'] = ob_get_clean();