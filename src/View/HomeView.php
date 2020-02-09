<?php

namespace App\View;

use Core\{
	AbstractView,
	Pagination,
	Router\Router
};

Class HomeView extends AbstractView
{
	public static function index(array $varPage): string
	{
		ob_start(); ?>
		<div class="container">
			<h2><?=$varPage['h2'] ?? ''?></h2>
		</div>
		<?php return ob_get_clean();
	}
}