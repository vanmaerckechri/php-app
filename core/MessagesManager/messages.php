<?php
// to custom messages => './src/Config/messages.php'
return array(
	'registerComplete' => array(
		'type' => 'info',
		'content' => array(
			'fr' => 'Un mail de validation vient de vous être envoyé',
			'en' => 'A validation email has just been sent to you'
		)
	),
	'disconnectComplete' => array(
		'type' => 'info',
		'content' => array(
			'fr' => 'Vous êtes déconnecté',
			'en' => 'You are disconnected'
		)
	),
	'notHaveRights' => array(
		'type' => 'error',
		'content' => array(
			'fr' => 'Vous n\'avez pas les droits pour effectuer cette action',
			'en' => 'You do not have the rights to perform this action'
		)
	),
	'auth' => array(
		'type' => 'error',
		'content' => array(
			'fr' => 'Nom d\'utilisateur ou mot de passe incorrect',
			'en' => 'Username or password incorrect'
		)
	),
	'unique' => array(
		'type' => 'error',
		'content' => array(
			'fr' => 'Déjà utilisé!',
			'en' => 'Already used!'
		)
	),
	'required' => array(
		'type' => 'error',
		'content' => array(
			'fr' => 'Champ requis',
			'en' => 'Required field'
		)
	),
	'only' => array(
		'type' => 'error',
		'content' => array(
			'fr' => 'Seules les entrées suivantes sont acceptées {{x}}',
			'en' => 'Only the following entries are accepted {{x}}'
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
	'type_varchar' => array(
		'type' => 'error',
		'content' => array(
			'fr' => 'Ce champ doit être une chaîne de caractères',
			'en' => 'This field must be a character string'
		)
	),
	'sql_foreignKeyFromAnotherTable' => array(
		'type' => 'error',
		'content' => array(
			'fr' => 'Action impossible! La table \'{{x}}\' possède une clef étrangère pointant vers cette table.',
			'en' => 'Impossible action! The table \'{{x}}\' has a foreign key pointing to this table.'
		)
	),
	'sql_foreignKeyOnAbsentTable' => array(
		'type' => 'error',
		'content' => array(
			'fr' => 'Action impossible! Cette table possède une clef étrangère pointant vers la table \'{{x}}\', cependant cette dernière n\'existe pas!',
			'en' => 'Impossible action! This table has a foreign key pointing to the table \'{{x}}\', however the latter does not exist!'
		)
	),
	'sql_foreignKeyOnEmptyTable' => array(
		'type' => 'error',
		'content' => array(
			'fr' => 'Action impossible! Cette table possède une clef étrangère pointant vers la table \'{{x}}\', cependant cette dernière est vide!',
			'en' => 'Impossible action! This table has a foreign key pointing to the table \'{{x}}\', however the latter is empty!'
		)
	)
);

