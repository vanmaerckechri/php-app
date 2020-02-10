<?php

namespace App\View;

use Core\App;
use App\View\HeaderView;

Class Template
{
	public static function display(array $varPage): void
	{
		$header = HeaderView::get($varPage);
		ob_start();
		?>
			<!DOCTYPE html>
			<html lang=<?=App::getConfig('language')?>>
			<head>
			    <meta charset="UTF-8">
			    <meta name="viewport" content="width=device-width, initial-scale=1.0">
			    <meta http-equiv="X-UA-Compatible" content="ie=edge">
			    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700|Roboto:400,700&display=swap" rel="stylesheet">
			    <?= $varPage['css'] ?? '' ?>
			    <title><?= $varPage['title'] ?></title>
			</head>
			<body>
				<header id="header" class="test">
					<div class="container">
						<?=$header?>
					</div>
				</header>
				<div class="sms-container">
					<?= $varPage['messages']['info'] ?? '' ?>
				</div>
				<div id="main" class="main">
			    	<?= $varPage['content'] ?>
			    </div>
			    <footer>
			    	<div class="container">
					</div>
				</footer>
				<script type="text/javascript" src="/public/js/ToggleNavVisibility.js"></script>
				<?= $varPage['js'] ?? '' ?>
				<script type="text/javascript">
					window.addEventListener("load", function(event)
					{
						CVMTOOLS.toggleNavVisibility = new CVMTOOLS.ToggleNavVisibility();
						CVMTOOLS.toggleNavVisibility.init('header');
						<?= $varPage['script'] ?? '' ?>
					});
				</script>
			</body>
			</html>
		<?php
		echo ob_get_clean();
	}
}