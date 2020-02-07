<?php

namespace Core\Devboard;

use Core\App;

class DevboardView
{
	public static function index(array $varPage): void
	{
		ob_start();
		?>
			<div id="main">
				<h1>DEVBOARD</h1>
				<h2>Base de Données:</h2>
				<form method="post">
					<input type="hidden" name="context" value="database">
					<span><?=$varPage['dbName']?></span>
					<?php if (!$varPage['isDbExist']): ?>
					<input type="submit" value="CREER">
					<?php else: ?>
					<input name="method" type="hidden" value="delete">
					<input type="submit" value="EFFACER" onclick="return confirm('Cette action est irréversible! Voulez-vous continuer ?')">
					<?php endif; ?>
				</form>
		    	<?php if ($varPage['isDbExist'] && $varPage['schemas']): ?>
		    	<h2>Tables:</h2>
					<?php foreach ($varPage['schemas'] as $name): ?>
						<form method="post">
							<input type="hidden" name="context" value="table">
							<input type="hidden" name="table" value=<?=$name?>>
		    				<span><?=$name?></span>
							<?php if ($varPage['tablesFromDb'] && array_search($name, $varPage['tablesFromDb']) !== false): ?>
								<input name="method" type="hidden" value="delete">
								<input type="submit" value="EFFACER" onclick="return confirm('Cette action est irréversible! Voulez-vous continuer ?')">
							<?php else: ?>
								<input type="submit" value="CREER">
							<?php endif; ?>
							<?= $varPage['messages']["{$name}TableSms"] ?? '' ?>
		    			</form>
					<?php endforeach ?>
			    	<h2>Remplir:</h2>
			    	<?php if ($varPage['tablesFromDb']): ?>
						<?php foreach ($varPage['schemas'] as $name): ?>
							<?php if (array_search($name, $varPage['tablesFromDb']) !== false): ?>
			    				<form method="post">
			    					<input type="hidden" name="context" value="hydrate">
			    					<input type="hidden" name="table" value="<?=$name?>">
				    				<span><?=$name?></span>
									<input type="number" name="iteration" min="1" value="1">
									<input type="submit" value="REMPLIR">
				    			</form>
				    		<?php endif; ?>
				    		<?= $varPage['messages']["{$name}FillSms"] ?? '' ?>
						<?php endforeach ?>
					<?php endif; ?>
					<h2>Entity:</h2>
					<?php foreach ($varPage['schemas'] as $name): ?>
						<form method="post">
							<input type="hidden" name="context" value="model">
							<input type="hidden" name="model" value=<?=$name?>>
		    				<span><?=$name?></span>
							<?php if ($varPage['modelList'] && array_search($name, $varPage['modelList']) !== false): ?>
								<input name="method" type="hidden" value="delete">
								<input type="submit" value="EFFACER" onclick="return confirm('Cette action est irréversible! Voulez-vous continuer ?')">
							<?php else: ?>
								<input type="submit" value="CREER">
							<?php endif; ?>
		    			</form>
					<?php endforeach ?>
					<h2>Repository:</h2>
					<?php foreach ($varPage['schemas'] as $name): ?>
						<form method="post">
							<input type="hidden" name="context" value="repo">
							<input type="hidden" name="repo" value=<?=$name?>>
		    				<span><?=$name?></span>
							<?php if ($varPage['repoList'] && array_search($name . 'repository', $varPage['repoList']) !== false): ?>
								<input name="method" type="hidden" value="delete">
								<input type="submit" value="EFFACER" onclick="return confirm('Cette action est irréversible! Voulez-vous continuer ?')">
							<?php else: ?>
								<input type="submit" value="CREER">
							<?php endif; ?>
		    			</form>
					<?php endforeach ?>
				<?php endif; ?>
			</div>
		<?php
		self::template(ob_get_clean());
	}

	private static function template(string $content): void
	{
		ob_start();
		?>
			<!DOCTYPE html>
			<html lang=<?=App::getConfig('language')?>>
			<head>
			    <meta charset="UTF-8">
			    <meta name="viewport" content="width=device-width, initial-scale=1.0">
			    <meta http-equiv="X-UA-Compatible" content="ie=edge">
			    <title>DEVBOARD</title>
			</head>
			<body>
				<?=$content?>
			</body>
			</html>
		<?php
		echo ob_get_clean();
	}
}