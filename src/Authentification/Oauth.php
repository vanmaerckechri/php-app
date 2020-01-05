<?php

namespace App\Authentification;

use App\Authentification\Auth;
use App\Model\User;
use App\Request\UserRequest;

class Oauth
{
	private function getEndPoint(): ?object
	{
		return json_decode(file_get_contents('https://accounts.google.com/.well-known/openid-configuration', false));
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

		return json_decode(file_get_contents($tokenEndPoint, false, $context));
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

		return json_decode(file_get_contents($userInfoEndPoint, false, $context));
	}

	public function login(): bool
	{
		$userInfos = $this->getUserInfos();
		if (!$userInfos)
		{
			return false;
		}

		$email = $userInfos->email;

		$userRequest = new UserRequest();
		$user = $userRequest->findUserByEmail($email);

		if (!$user)
		{
			$user = new User();
			$user->setEmail($email);
			$userRequest->recordOauth($user, true);
			$user = $userRequest->findUserByEmail($email);
		}
		Auth::addUserToSession($user);
		return true;
	}

}