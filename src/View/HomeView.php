<?php

namespace App\View;

Class HomeView
{
	public static function show($varPage)
	{
		ob_start();
		?>
		<?php
		return ob_get_clean();
	}
}