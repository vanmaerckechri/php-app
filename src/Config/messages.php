<?php
return array(
	'registerComplete' => array(
		'type' => 'info',
		'content' => array(
			'fr' => 'Un mail de validation vient de vous être envoyé',
			'en' => 'A validation email has just been sent to you'
		)
	),
	'auth' => array(
		'type' => 'error',
		'content' => array(
			'fr' => 'Nom d\'utilisateur ou mot de passe incorrect',
			'en' => 'Username or password incorrect'
		)
	),
	'usernameTaken' => array(
		'type' => 'error',
		'content' => array(
			'fr' => 'Le nom d\'utilisateur est déjà utilisé',
			'en' => 'Username is already in use'
		)
	),
	'emailTaken' => array(
		'type' => 'error',
		'content' => array(
			'fr' => 'L\'adresse email est déjà utilisée',
			'en' => 'The email address is already used'
		)
	),
	'required' => array(
		'type' => 'error',
		'content' => array(
			'fr' => 'Champ requis',
			'en' => 'Required field'
		)
	),
	'minLength' => array(
		'type' => 'error',
		'content' => array(
			'fr' => 'Ce champ doit comporter au moins {{x}} caractères',
			'en' => 'This field must contain at least {{x}} characters'
		)
	),
	'maxLength' => array(
		'type' => 'error',
		'content' => array(
			'fr' => 'Ce champ doit comporter au maximum {{x}} caractères',
			'en' => 'This field must contain a maximum of {{x}} characters'
		)
	),
	'type_int' => array(
		'type' => 'error',
		'content' => array(
			'fr' => 'Ce champ n\'accepte que les nombres entiers',
			'en' => 'This field accepts only whole numbers'
		)
	),
	'type_email' => array(
		'type' => 'error',
		'content' => array(
			'fr' => 'Adresse email non valide',
			'en' => 'Email address Invalid'
		)
	),
	'type_string' => array(
		'type' => 'error',
		'content' => array(
			'fr' => 'Ce champ doit être une chaîne de caractères',
			'en' => 'This field must be a character string'
		)
	)
);