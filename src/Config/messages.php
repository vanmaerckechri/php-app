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
			'fr' => 'Le nom d\'utilisateur existe déjà',
			'en' => 'Username already exists'
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
	'type' => array(
		'type' => 'error',
		'content' => array(
			'fr' => 'Ce champ est de type {{x}}',
			'en' => 'This field is of type {{x}}'
		)
	)
);