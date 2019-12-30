<?php

namespace App\View;

Class TestView
{
    public static function show($varPage)
	{
		ob_start();

		?>
			<p>MAIN</p>
			<ul>
				<li>id = <?= $varPage['id'] ?></li>
				<li>slug = <?= $varPage['slug'] ?></li>
			</ul>
		<?php

		return ob_get_clean();
	}
}