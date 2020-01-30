<?php

namespace App\Controller;

use Core\ {
	App,
	Helper,
	AbstractController,
	FilesManager,
	MessagesManager\MessagesManager,
	Authentification\Auth
};
use App\ {
	Entity\Article,
	Repository\ArticleRepository
};

class ArticleController extends AbstractController
{
	protected $varPage = [
		'title' => 'APP-PHP::ARTICLE',
		'h1' => 'APP-PHP',
		'h2' => 'Article',
	];

	public function show(int $id, string $slug): void
	{
		$article = new Article();

		// 'id' and 'slug' are valid before request db ?
		if ($article->isValid(['id' => $id, 'slug' => $slug], false))
		{
			// item with this 'id' and this 'slug' does exist ?
			$article = ArticleRepository::findArticleById($id);

			if (!is_null($article) && $article->getSlug() === $slug)
			{
				$createdAt = $article->getCreated_at()->format('Y-m-d H:i:s');
				$this->varPage['article'] = $article;
				$this->varPage['previous'] = ArticleRepository::findNextLater(['id', 'slug'], $id, $createdAt);
				$this->varPage['next'] = ArticleRepository::findNextEarler(['id', 'slug'], $id, $createdAt);
				$this->renderer('ArticleView', 'show');
				return;
			}
		}

		$this->redirect('error404', ['code' => 404]);
	}

	public function new(): void
	{
		$this->redirect('connection', ['logged' => false]);
		$this->varPage['recordedInputs'] = $this->getRecordedInputs();
		$this->varPage['messages'] = MessagesManager::getMessages();
		$this->renderer('ArticleView', 'form');
	}

	public function create(): void
	{
		$this->redirect('connection', ['logged' => false]);

		$userId = Auth::user()->getId();

		if (isset($_POST['title']) && isset($_POST['content']))
		{
			$article = new Article();

			if ($article->isValid(['title' => $_POST['title'], 'content' => $_POST['content']]) && $article->isUnique(['title']))
			{
				$imagePath = '/public/images/';
				$schemaClass = App::getClass('schema', 'article');
				$schema = $schemaClass::$schema['img_file'];

				if (FilesManager::uploadImage($imagePath, 'image', $schema, false))
				{
					if ($img_file = FilesManager::getLastFileName())
					{
						$article->setImg_file($img_file);
					}

					$article->setSlug(Helper::slugify($_POST['title']));
					$article->setUser_id($userId);
					ArticleRepository::record($article);
					$this->redirect('articles', ['url' => ['page' => 1]]);
				}
			}
		}

		$this->recordInputs(['title' => $_POST['title'], 'content' => $_POST['content']]);
		$this->redirect('newArticle');
	}

	public function edit(int $id, string $slug): void
	{
		$this->redirect('connection', ['logged' => false]);

		$userId = Auth::user()->getId();
		$article = new Article();

		if ($article->isValid(['id' => $id, 'slug' => $slug], false))
		{
			$article = ArticleRepository::findOneByCol('id', $id);

			if (!is_null($article) && $article->getSlug() === $slug)
			{
				// item belongs to the user ?
				if ($article->getUser_id() === $userId)
				{
					$recordInputs = $this->getRecordedInputs();
					$this->varPage['recordedInputs']['title'] = isset($recordInputs['title']) ? $recordInputs['title'] : $article->getTitle();
					$this->varPage['recordedInputs']['content'] = isset($recordInputs['content']) ? $recordInputs['content'] : $article->getContent();
					$this->varPage['messages'] = MessagesManager::getMessages();
					$this->renderer('ArticleView', 'form');
					return;
				}
				MessagesManager::add(['info' => ['notHaveRights' => null]]);
				$this->redirect('home');
			}
		}

		$this->redirect('error404', ['code' => 404]);
	}

	public function update(int $id, string $slug): void
	{
		$this->redirect('connection', ['logged' => false]);

		$userId = Auth::user()->getId();

		if (isset($_POST['title']) && isset($_POST['content']))
		{
			$article = new Article();

			if ($article->isValid(['id' => $id, 'slug' => $slug], false) &&
				$article->isValid(['title' => $_POST['title'], 'content' => $_POST['content']]) &&
				$article->isUnique(['title'], $id))
			{
				$articleOld = ArticleRepository::findOneByCol('id', $id);

				if (!is_null($articleOld) && $articleOld->getSlug() === $slug)
				{
					$imagePath = '/public/images/';
					$schemaClass = App::getClass('schema', 'article');
					$schema = $schemaClass::$schema['img_file'];

					// try to upload img (schema for validation (minLength, maxLength) && required = false)
					if (FilesManager::uploadImage($imagePath, 'image', $schema, false))
					{
						if ($articleOld->getUser_id() === $userId);
						{
							// if a new image is uploaded, set new filename in database and remove last file
							if ($img_file = FilesManager::getLastFileName())
							{
								$oldFileName = $articleOld->getImg_file();
								$article->setImg_file($img_file);
								FilesManager::dropFile($imagePath, $oldFileName);
							}

							$article->setSlug(Helper::slugify($_POST['title']));
							ArticleRepository::updateById($article, $id);
							$this->redirect('articles', ['url' => ['page' => 1]]);
						}
						MessagesManager::add(['info' => ['notHaveRights' => null]]);
						$this->redirect('home');
					}
				}
			}
		}

		$this->recordInputs(['title' => $_POST['title'], 'content' => $_POST['content']]);
		$this->redirect('editArticle', ['url' => ['id' => $id, 'slug' => $slug]]);
	}
}