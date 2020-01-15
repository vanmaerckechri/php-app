<?php

namespace Core\Router;

class Route
{
	private $path;
	private $callable;
	private $matches = [];
	private $params = [];

	public function __construct($path, $callable)
	{
		$this->path = trim($path, '/');
		$this->callable = $callable;
	}

	public function with($param, $regex)
	{
		// Remplacer les parenthèses du paramètre(regex) par des parenthèses qui ne capturent pas. Les parenthèses capturantes seront ajoutées dans la méthode 'paramMatch' à tous les paramètres concernés (qu'ils aient des parenthèses dans leur regex de base ou pas)...
		$this->params[$param] = str_replace('(', '(?:', $regex);
		// Retourner $this permet d'utiliser du fluent comme $plop->get()->with()->with().
		return $this;
	}

	public function match($url)
	{
		// retirer les caractères '/'' en début et fin de chaîne.
		$url = trim($url, '/');
		// Remplacer les paramètres par leur regex correspondant. Soit celui précisé à l'aide de la méthode 'with' soit '([^/]+)' par défaut.
		$path = preg_replace_callback('#:([\w]+)#', [$this, 'paramMatch'], $this->path);
		// verifier le chemin du début(^) à la fin($) en prenant les min/maj (avec le flag i) en compte.
		$regex = "#^$path$#i";
		if (!preg_match($regex, $url, $matches))
		{
			return false;
		}
		// supprimer le premier index (totalité de l'url) pour ne garder que la valeur des paramètres(id, slug, etc.).
		array_shift($matches);
		$this->matches = $matches;
		return true;
	}

	private function paramMatch($match)
	{
		/* 
			ex: $match[1] = id.
			$this->params[$match[1]] = $this->params['id'] = [0-9]+.
		*/
		if (isset($this->params[$match[1]]))
		{
			// ajouter des parenthèses pour le 'preg_match' de la méthod 'match' sur le regex correspondant au paramètre entré à l'aide de la méthode 'with'.
			return '(' . $this->params[$match[1]] . ')';
		}
		// tous les caractères hormis les '/'.
		return '([^/]+)';
	}

	public function call()
	{
		// $this->callable = Class#Method.
		if (is_string($this->callable))
		{
			$params = explode('#', $this->callable);

			$controller = 'App\\Controller\\' . $params[0];
			$controller = new $controller();

			// [Class, Method], Param($id, $slug, etc.)
			return call_user_func_array([$controller, $params[1]], $this->matches);
		}
		else
		{
			// retourne la fonction présente dans le callable avec les matches du tableau
			return call_user_func_array($this->callable, $this->matches);
		}
	}

	public function getUrl($params)
	{
		$path = $this->path;
		foreach ($params as $k => $v)
		{
			$path = str_replace(":$k", $v, $path);
		}
		return $path;
	}
}