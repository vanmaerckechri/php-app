<?php

namespace App\Controller;

use Core\ {
	App,
	Helper,
	FilesManager,
	AbstractController,
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

		// validate before request db (set = false)
		if ($article->isValid(['id' => $id, 'slug' => $slug], false))
		{
			$article = ArticleRepository::findArticleById($id);
			// item exists and slug matches id ?
			if (!is_null($article) && $article->getSlug() === $slug)
			{
				$createdAt = $article->getCreated_at()->format('Y-m-d H:i:s');
				$this->varPage['article'] = $article;
				$this->varPage['previous'] = ArticleRepository::findNextLater(['id', 'slug'], $id, $createdAt);
				$this->varPage['next'] = ArticleRepository::findNextEarler(['id', 'slug'], $id, $createdAt);
				$this->renderer('ArticleView', 'show');
			}
		}

		$this->redirect('error404', ['code' => 404]);
	}

	public function new(): void
	{
		$this->redirect('connection', ['logged' => false]);

		$this->varPage['js'] = ['InstantLoadImg'];
		$this->varPage['script'] = self::scriptForNewAndEdit();

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

			if ($article->isValid(['title' => $_POST['title'], 'content' => $_POST['content']]) && $article->isUnique(['title'])
			){
				if ($uploadInfos = $this->uploadImage(false))
				{
					$article->setImg_file($uploadInfos['fileName']);
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

		$this->varPage['js'] = ['InstantLoadImg'];
		$this->varPage['script'] = self::scriptForNewAndEdit();

		$userId = Auth::user()->getId();
		$article = new Article();

		if ($article->isValid(['id' => $id, 'slug' => $slug], false))
		{
			$article = ArticleRepository::findOneByCol('id', $id);

			if (!is_null($article)
				&& $article->getSlug() === $slug
				&& $article->getUser_id() === $userId // belongs to the user ?
			){
				$recordInputs = $this->getRecordedInputs();
				$this->varPage['recordedInputs']['title'] = isset($recordInputs['title']) ? $recordInputs['title'] : $article->getTitle();
				$this->varPage['recordedInputs']['content'] = isset($recordInputs['content']) ? $recordInputs['content'] : $article->getContent();
				$this->varPage['recordedInputs']['imagePreview'] = $article->getImg_file() ? '/public/images/' . $article->getImg_file() : null;

				$this->varPage['messages'] = MessagesManager::getMessages();
				$this->renderer('ArticleView', 'form');
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

			if ($article->isValid(['id' => $id, 'slug' => $slug], false)
				&& $article->isValid(['title' => $_POST['title'], 'content' => $_POST['content']]) 
				&& $article->isUnique(['title'], $id)
			){
				$articleOldVer = ArticleRepository::findOneByCol('id', $id);

				if (!is_null($articleOldVer)
					&& $articleOldVer->getSlug() === $slug
					&& $articleOldVer->getUser_id() === $userId
				){
					if ($uploadInfos = $this->uploadImage(false));
					{
						// new file and last file exist ? remove last file
						if ($uploadInfos['fileName'] && $oldFileName = $articleOldVer->getImg_file())
						{
							FilesManager::dropFile($uploadInfos['path'], $oldFileName);
						}
						$article->setImg_file($uploadInfos['fileName']);
						$article->setSlug(Helper::slugify($_POST['title']));
						ArticleRepository::updateById($article, $id);
						$this->redirect('articles', ['url' => ['page' => 1]]);
					}
				}
			}
		}
		$this->recordInputs(['title' => $_POST['title'], 'content' => $_POST['content']]);
		$this->redirect('editArticle', ['url' => ['id' => $id, 'slug' => $slug]]);
	}

	private function uploadImage(bool $required = true): ?array
	{
		$imagePath = '/public/images/';
		$schemaClass = App::getClass('schema', 'article');
		$schema = $schemaClass::$schema['img_file'];

		// try to upload img (schema for validation (minLength, maxLength))
		if (FilesManager::uploadImage($imagePath, 'image', $schema, $required))
		{
			return ['path' => $imagePath, 'fileName' => FilesManager::getFileName()];
		}
		return null;
	}

	private static function scriptForNewAndEdit(): string
	{
		ob_start(); ?>
			var instantLoadImg = new CVMTOOLS.InstantLoadImg();
			instantLoadImg.init('image', 'imagePreview');
		<?php return ob_get_clean();
	}
}