<!DOCTYPE html>
<html lang=<?= $GLOBALS['lang'] ?>>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>DEVBOARD</title>
</head>
<body>
	<div id="main">
		<h1>DEVBOARD</h1>
		<h2>Base de Données:</h2>
		<form method="post">
			<input type="hidden" name="context" value="database">
			<span><?=$dbName?></span>
			<?php if (!$isDbExist): ?>
			<input type="submit" value="CREER">
			<?php else: ?>
			<input name="method" type="hidden" value="delete">
			<input type="submit" value="EFFACER" onclick="return confirm('Cette action est irréversible! Voulez-vous continuer ?')">
			<?php endif; ?>
		</form>
    	<?php if ($isDbExist && $schemas): ?>
    	<h2>Tables:</h2>
			<?php foreach ($schemas as $name): ?>
				<form method="post">
					<input type="hidden" name="context" value="table">
					<input type="hidden" name="table" value=<?=$name?>>
    				<span><?=$name?></span>
					<?php if ($tablesFromDb && array_search($name, $tablesFromDb) !== false): ?>
						<input name="method" type="hidden" value="delete">
						<input type="submit" value="EFFACER" onclick="return confirm('Cette action est irréversible! Voulez-vous continuer ?')">
					<?php else: ?>
						<input type="submit" value="CREER">
					<?php endif; ?>
					<?= $varPage['messages']["{$name}TableSms"] ?? '' ?>
    			</form>
			<?php endforeach ?>
			<h2>Modèles:</h2>
			<?php foreach ($schemas as $name): ?>
				<form method="post">
					<input type="hidden" name="context" value="model">
					<input type="hidden" name="model" value=<?=$name?>>
    				<span><?=$name?></span>
					<?php if ($modelList && array_search($name, $modelList) !== false): ?>
						<input name="method" type="hidden" value="delete">
						<input type="submit" value="EFFACER" onclick="return confirm('Cette action est irréversible! Voulez-vous continuer ?')">
					<?php else: ?>
						<input type="submit" value="CREER">
					<?php endif; ?>
    			</form>
			<?php endforeach ?>
	    	<h2>Remplir:</h2>
	    	<?php if ($tablesFromDb && $modelList): ?>
				<?php foreach ($schemas as $name): ?>
					<?php if (array_search($name, $tablesFromDb) !== false && array_search($name, $modelList) !== false): ?>
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
		<?php endif; ?>
	</div>
</body>
</html>