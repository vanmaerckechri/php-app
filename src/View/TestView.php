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
				<li>id = <?= htmlspecialchars($varPage['id']) ?></li>
				<li>slug = <?= htmlspecialchars($varPage['slug']) ?></li>
			</ul>
		<?php

		return ob_get_clean();
	}
}