<?php

namespace App\View;

Class Error404View
{
	public static function show(array $varPage): string
	{
		ob_start(); ?>
		<div class="container">
			<h2><?=$varPage['h2'] ?? ''?></h2>
		</div>
		<?php return ob_get_clean();
	}
}