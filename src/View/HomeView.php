<?php

namespace App\View;

Class HomeView
{
	public static function show($varPage)
	{
		ob_start();

		?>
			<p>MAIN</p>
		<?php

		return ob_get_clean();
	}
}