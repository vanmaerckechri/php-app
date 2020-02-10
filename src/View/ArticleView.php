<?php

namespace App\View;

use Core\ {
	AbstractView,
	Router\Router,
	Authentification\Auth
};

Class ArticleView extends AbstractView
{
	public static function show(array $varPage): string
	{
		$previous = $varPage['previous'];
		$next = $varPage['next'];
		$user = Auth::user();

		ob_start();	?>
		<div class="article-container container">
			<h2><?=$varPage['h2'] ?? ''?></h2>
			<div class="nav">
				<?php if ($previous): ?>
					<a class="btn" href="<?=Router::url('article', ['id' => $previous->getId(), 'slug' => $previous->getSlug()])?>">PREVIOUS</a>
				<?php endif; ?>
				<?php if ($next): ?>
					<a class="btn" href="<?=Router::url('article', ['id' => $next->getId(), 'slug' => $next->getSlug()])?>">NEXT</a>
				<?php endif; ?>
			</div>
			<div class="article">
				<?php if ($user && $user->getId() === $varPage['article']->getUser_id()): ?>
					<a class="btn" href="<?=Router::url('editArticle', ['id' => $varPage['article']->getId(), 'slug' => $varPage['article']->getSlug()])?>">EDIT</a>
				<?php endif; ?>
				<h3><?=htmlentities($varPage['article']->getTitle())?></h3>
				<?php if ($varPage['article']->getImg_file()): ?>
					<img src="\public\images\<?=$varPage['article']->getImg_file()?>" alt="">
				<?php endif; ?>
				<p><?=nl2br(htmlentities($varPage['article']->getContent()))?></p>
				<div class="creation-infos">
					<p class="user"><?=$varPage['article']->user_name?></p>
					<p class="date"><?=$varPage['article']->getCreated_at()->format('d/m/y')?></p>
				</div>
			</div>
		</div>
		<?php return ob_get_clean();
	}
	
	public static function form(array $varPage): string
	{
		ob_start();	?>
		<div class="article-container container">
			<h2><?=$varPage['h2'] ?? ''?></h2>
			<form class="article-form" method="post" enctype="multipart/form-data">
				<label class="title" for="title">Titre<input type="text" name="title" id="title" value="<?=htmlentities($varPage['recordedInputs']['title'] ?? '')?>" required><?=$varPage['messages']['titleSms'] ?? ''?></label>
				<label for="image">Image<input type="file" name="image" id="image" accept="image/*"><?= $varPage['messages']['uploadSms'] ?? '' ?></label>
				<img id="imagePreview" class="imagePreview" src="<?= $varPage['recordedInputs']['imagePreview'] ?? '' ?>" alt="">
				<label for="content">Contenu<textarea class="content" name="content" id="content" required><?=htmlentities($varPage['recordedInputs']['content'] ?? '')?></textarea><?= $varPage['messages']['contentSms'] ?? '' ?></label>
				<input id="validation" class="btn" type="submit" value="ENREGISTRER">
			</form>
		</div>
		<?php return ob_get_clean();
	}
}