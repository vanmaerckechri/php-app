<?php

namespace App\View;

Class Template
{
	public static function load($varPage)
	{
		ob_start();

		?>
			<!DOCTYPE html>
			<html>
			<head>
			    <title><?= $varPage['title'] ?></title>
			</head>
			<body>
				<header>
					<h1><?= $varPage['h1'] ?></h1>
					<p>HEADER</p>
				</header>
				<div id="main">
			    	<?= $varPage['content'] ?>
			    </div>
			    <footer>
					<p>FOOTER</p>
				</footer>
			</body>
			</html>
		<?php

		echo ob_get_clean();
	}
}