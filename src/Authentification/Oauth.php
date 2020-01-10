<?php

namespace App\Authentification;

use App\Authentification\Auth;
use App\Model\User;
use App\Repository\UserRepository;

class Oauth
{
	private function getEndPoint(): ?object
	{
		return json_decode(@file_get_contents('https://accounts.google.com/.well-known/openid-configuration', false));
	}

	private function getToken(string $tokenEndPoint): ?object
	{
		if (!isset($_GET['code']))
		{
			return null;
		}

		require_once $_SERVER['DOCUMENT_ROOT'] . '/src/Config/oauth.php';

		$content  = array(
	        'code' => $_GET['code'],
			'client_id' => GOOGLE_ID,
			'client_secret' => GOOGLE_SECRET,
			'redirect_uri' => $GLOBALS['router']->url('googleConnexion'),
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

	private function getUserInfos(): ?object
	{
		$endPoint = $this->getEndPoint();
		if (!$endPoint)
		{
			return null;
		}
		$tokenEndPoint = $endPoint->token_endpoint;
		$userInfoEndPoint = $endPoint->userinfo_endpoint;

		$token = $this->getToken($tokenEndPoint);
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

	public function login(): bool
	{
		$userInfos = $this->getUserInfos();
		if (!$userInfos)
		{
			return false;
		}

		$email = $userInfos->email;
		$password = $userInfos->sub;
		$username = $userInfos->name;

		$user = new User();
		// check that the user inputs respect the filters
		if ($user->isValid(['email' => $email, 'username' => $username, 'password' => $password]))
		{
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
				if (!UserRepository::record($user))
				{
					return false;
				}
			}
			// connect user
			$user = UserRepository::findUserByEmail($email);
			Auth::addUserToSession($user);
			return true;
		}
		return false;
	}
}