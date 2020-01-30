<?php

namespace Core\Authentification;

use Core\ {
	App,
	Router\Router,
	Authentification\Auth
};

class Oauth
{
	private static $config;
	private static $network;

	public static function login(string $network): bool
	{
		self::$network = $network;
		$userInfos = self::getUserInfos();
		if (!$userInfos)
		{
			return false;
		}

		$email = $userInfos->email;
		$password = $userInfos->sub;
		$username = $userInfos->name;

		$entity = App::getClass('entity', 'user');
		$user = new $entity();
		// check that the user inputs respect the filters
		if ($user->isValid(['email' => $email, 'username' => $username, 'password' => $password]))
		{
			$repo = App::getClass('repository', 'user');
			// if email is unique create a new account
			if ($user->isUnique(['email']))
			{
				// if username already used, increment it
				if (!$user->isUnique(['username']))
				{
					$newUsername = $user->incrementAlreadyUsed('username');
					$user->isValid(['username' => $newUsername]);
				}
				// try to record new user
				if (!call_user_func_array([$repo, 'record'], [$user]))
				{
					return false;
				}
			}
			// connect user
			$user = call_user_func_array([$repo, 'findOneByCol'], ['email', $email]);
			Auth::addUserToSession($user);
			return true;
		}
		return false;
	}

	public static function getConfig(string $network = null): array
	{
		if (is_null(self::$config))
		{
			$file = App::getAppDirectory() . 'Config/security.json';
			self::$config = json_decode(file_get_contents($file), true)['oauth'];
		}
		return $network ? self::$config[$network] : self::$config[self::$network];
	}

	private static function getEndPoint(): ?object
	{
		return json_decode(@file_get_contents('https://accounts.google.com/.well-known/openid-configuration', false));
	}

	private static function getToken(string $tokenEndPoint): ?object
	{
		if (!isset($_GET['code']))
		{
			return null;
		}

		$config = self::getConfig();

		$content  = array(
	        'code' => $_GET['code'],
			'client_id' => $config['goole_id'],
			'client_secret' => $config['google_secret'],
			'redirect_uri' => Router::url($config['google_route']),
			'grant_type' => 'authorization_code'
		);

		$content = http_build_query($content);

		$options = array(
			'http'=>array(
				'method'=>'POST',
            	'header'=> 'Content-type: application/x-www-form-urlencoded',
            	'content'=>$content
			)
		);

		$context = stream_context_create($options);

		return json_decode(@file_get_contents($tokenEndPoint, false, $context));
	}

	private static function getUserInfos(): ?object
	{
		$endPoint = self::getEndPoint();
		if (!$endPoint)
		{
			return null;
		}
		$tokenEndPoint = $endPoint->token_endpoint;
		$userInfoEndPoint = $endPoint->userinfo_endpoint;

		$token = self::getToken($tokenEndPoint);
		if (!$token)
		{
			return null;
		}
		$accessToken = $token->access_token;

		$options = array(
			'http'=>array(
				'method'=>'GET',
            	'header'=> 'Authorization: Bearer' . $accessToken
            )
		);

		$context = stream_context_create($options);

		return json_decode(@file_get_contents($userInfoEndPoint, false, $context));
	}
}