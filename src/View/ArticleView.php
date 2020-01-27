<?php

namespace App\View;

use Core\Router\Router;
use Core\Authentification\Auth;

Class ArticleView
{
	public static function show($varPage)
	{
		$previous = $varPage['previous'];
		$next = $varPage['next'];
		$user = Auth::user();

		ob_start();
		?>
		<div class="container">
			<h2><?=$varPage['h2'] ?? ''?></h2>
			<ul>
				<li>
					<h3><?=htmlentities($varPage['article']->getTitle())?></h3>
					<p><?=nl2br(htmlentities($varPage['article']->getContent()))?></p>
					<p><?=$varPage['article']->getCreated_at()->format('d/m/y')?></p>
				</li>
			</ul>
			<?php if ($user && $user->getId() === $varPage['article']->getUser_id()): ?>
				<a class="btn" href="<?=Router::url('editArticle', ['id' => $varPage['article']->getId(), 'slug' => $varPage['article']->getSlug()])?>">EDIT</a>
			<?php endif; ?>
			<div class="pagination-container">
				<?php if ($previous): ?>
					<a class="btn" href="<?=Router::url('article', ['id' => $previous->getId(), 'slug' => $previous->getSlug()])?>">PREVIOUS</a>
				<?php endif; ?>
				<?php if ($next): ?>
					<a class="btn" href="<?=Router::url('article', ['id' => $next->getId(), 'slug' => $next->getSlug()])?>">NEXT</a>
				<?php endif; ?>
			</div>
		</div>
		<?php 
		return ob_get_clean();
	}

	public static function new($varPage)
	{
		ob_start();
		?>
		<div class="container">
			<h2><?=$varPage['h2'] ?? ''?></h2>
			<form method="post" id='form'>

				<label for="username">Titre<input type="text" name="title" id="title" value="<?=htmlentities($varPage['recordedInputs']['title'] ?? '')?>" required><?=$varPage['messages']['titleSms'] ?? ''?></label>

				<label for="contenu">Contenu<textarea name="content" id="content" required><?=htmlentities($varPage['recordedInputs']['content'] ?? '')?></textarea><?= $varPage['messages']['contentSms'] ?? '' ?></label>
				<input id="validation" class="btn" type="submit" value="ENREGISTRER">
			</form>
		</div>
		<?php
		return ob_get_clean();
	}

	public static function edit($varPage)
	{
		ob_start();
		?>
		<div class="container">
			<h2><?=$varPage['h2'] ?? ''?></h2>
			<form method="post" id='form'>

				<label for="username">Titre<input type="text" name="title" id="title" value="<?=htmlentities($varPage['recordedInputs']['title'] ?? '')?>" required><?=$varPage['messages']['titleSms'] ?? ''?></label>

				<label for="contenu">Contenu<textarea name="content" id="content" required><?=htmlentities($varPage['recordedInputs']['content'] ?? '')?></textarea><?= $varPage['messages']['contentSms'] ?? '' ?></label>
				<input id="validation" class="btn" type="submit" value="ENREGISTRER">
			</form>
		</div>
		<?php
		return ob_get_clean();
	}
}