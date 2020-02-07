<?php

namespace App\View;

use Core\ {
	App,
	AbstractTemplate
};
use App\View\HeaderView;

Class Template extends AbstractTemplate
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
			    <link rel="stylesheet" type="text/css" href="/public/css/style.css">
			    <title><?= $varPage['title'] ?></title>
			</head>
			<body>
				<header>
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
				<?= $varPage['javascript'] ?? '' ?>
			</body>
			</html>
		<?php
		echo ob_get_clean();
	}
}