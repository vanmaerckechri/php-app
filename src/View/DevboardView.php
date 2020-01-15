<?php

namespace App\View;

Class DevboardView
{
	public static function index($varPage)
	{
		ob_start();
		?>
		<div class="container">
			<h2><?=$varPage['h2'] ?? ''?></h2>
		</div>
		<?php

		return ob_get_clean();
	}
}