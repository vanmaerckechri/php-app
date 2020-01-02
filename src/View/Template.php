<?php

namespace App\View;

Class Template
{
	private static function importJs($jsFileNames)
	{
		ob_start();

		foreach ($jsFileNames as $fileName) 
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
		if (isset($varPage['jsFileNames']))
		{
			$js = SELF::importJs($varPage['jsFileNames']);
		}

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
					<h1><?= $varPage['h1'] ?></h1>
				</header>
				<div id="main">
			    	<?= $varPage['content'] ?>
			    </div>
			    <footer>
				</footer>
				<?= $js ?? '' ?>
			</body>
			</html>
		<?php

		echo ob_get_clean();
	}
}