<?php

namespace App\View;

use App\View\HeaderView;

Class Template
{
	private static function importJs($varPage)
	{
		if (!isset($varPage['jsFileNames']))
		{
			return null;
		}

		ob_start();
		foreach ($varPage['jsFileNames'] as $fileName) 
		{
			$path = '/public/js/' . $fileName . '.js';
			$path = str_replace('/', DIRECTORY_SEPARATOR, $path);
			
			?>
			<script type="text/javascript" src="<?= $path ?>"></script>
			<?php
		}
		return ob_get_clean();
	}

	public static function load($varPage)
	{
		$header = HeaderView::get($varPage);
		$js = SELF::importJs($varPage);

		ob_start();

		?>
			<!DOCTYPE html>
			<html lang=<?= $GLOBALS['lang'] ?>>
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
					<div class="container">
			    		<?= $varPage['content'] ?>
			    	</div>
			    </div>
			    <footer>
			    	<div class="container">
					</div>
				</footer>
				<?= $js ?? '' ?>
			</body>
			</html>
		<?php

		echo ob_get_clean();
	}
}