<?php

namespace App\View;

Class HomeView
{
	public static function show($varPage)
	{
		ob_start();
		?>
			<h2><?=$varPage['h2'] ?? ''?></h2>
		<?php
		return ob_get_clean();
	}
}