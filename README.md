# Environnement PHP-MVC (en dév.)

## 1. Installation:

- ### 1.1 Configurer la Base de Données

    Complétez le fichier '...\src\Config\security.json' avec vos informations.
    ```php
    {
        "server": {
            "host": "hostName",
            "charset": "utf8",
            "user": "myUserName",
            "pwd": "myPassword",
            "db": {
                "name": "dbname",
                "default character": "utf8 COLLATE utf8_general_ci"
            }
        }
    }
    ```

## 2. Interface de Développement:

- ### 2.1 Sécuriser l'Accès à l'Interface

    Créer un fichier '\\.htpasswd'. Dans ce dernier, il est possible d'introduire un nom d'utilisateur accompagné d'un mot de passe hashé. Pour obtenir celui-ci, la fonction password_hash de php peut s'avérer utile:
    
    ```php
    echo password_hash("votreMotDePasse", PASSWORD_DEFAULT);
    ```
    
    Voici à quoi peut ressembler le fichier '.htpasswd' une fois configuré:
    ```
    dev:$2y$10$zQgv8A69woD8JprTMPE/c.uzMNL0Kg1JTDBSy.jfppDgaTvlmPa.S
    ```
    
    Il faut aussi configurer le fichier '\\.htaccess'. Pour compléter le fichier, le chemin absolu vers le fichier 'htpasswd' est indispensable. Pour obtenir celui-ci, la fonction realpath de php peut s'avérer utile:
    
    ```php
    echo realpath($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . '.htpasswd');
    ```
    
    Ajouter les lignes suivantes dans le fichier '.htaccess':
    
    ```
    AuthUserFile "le résultat obtenu avec realpath"
    AuthType Basic
    AuthName "DevBoard"

    <Files "devboard">
        Require valid-user
    </Files>
    ```
    
- ### 2.2 Charger l'Interface

    Se rendre sur l'url '\\devboard'.
    
- ### 2.3 Création de la Base de Données

    Si vos informations ont été correctement introduites au point 1.1, vous devriez pouvoir monter la base de données à l'aide d'un simple clique sur le bouton de création.

- ### 2.4 Création des Tables

    La création des tables s'appuie sur les fichiers schemas (voir chapitre 3). Pour l'exemple, par defaut il existe deux schemas ('user' et 'article') permettant de créer les tables en base de données. Pour créer celle-ci, un simple clique sur le bouton de création suffit.

- ### 2.5 Remplissage des Tables

    Le remplissage des tables s'appuie sur les fichiers schemas (voir chapitre 3). Pour lancer le remplissage, un simple clique sur le bouton approprié suffit.

- ### 2.6 Création des Modèles

    La création des modèles s'appuie sur les fichiers schemas (voir chapitre 3). Elles sont composées de deux types de fichiers, 'entity' (voir chapitre 4.1) et 'repository' (voir chapitre 4.2). Lorsque vous effacer une table, les modèles persistent! Par defaut, les modèles 'user' et 'post' existent déjà.
    

## 3. les Schemas:

- ### 3.1 Présentation:

    La classe 'schema' est composée des règles associées aux colonnes d'une table. La plupart de ces règles sont identiques à celles utilisées avec 'MySql'. C'est à partir de celles-ci qu'il sera possible de créer automatiquement les tables ainsi que les modèles (chapitre 4). Elles permettent aussi le bon fonctionnement du système de validation et de messages lors des entrées utilisateur (formulaire, url, etc.).
    Les classes schemas doivent être placée dans le dossier '\\src\\Schema\\'. Par defaut, deux classes sont déjà présentes dans ce dossier, 'UserSchema' et 'PostSchema'. Toutes les réglages doivent être complétés en minuscules!

    Voici à quoi ressemble la structure de la classe:
    ```php
    class UserSchema
    {
        public static $schema = array(
            'id' => array(
                'type' => 'int',
                'default' => 'auto_increment',
                ...
            ),
            'email' => array(
                ...
            ),
            ...
        );

        public static $options = array(
            'engine' => 'InnoDB',
            ...
        );
    }
    ```

- ### 3.2 Propriété Schema:

    - #### 3.2.1 Typage

        Propriété: 'type'
        Type: string
        valeurs acceptées: 'bool, int, varchar, text, datetime, email, password'.
        Indispensable: oui
        
        ```php
        'id' => array(
            'type' => 'int',
            ...
        ```
        
    - #### 3.2.2 Valeurs par Défaut
  
        Propriété: 'default'
        Type: string
        valeurs acceptées: 'not null, null, not null default xXx, auto_increment'.
        Indispensable: oui
        
         ```php
        'email' => array(
            'default' => 'not null',
             ...
        ```
        
    - #### 3.2.3 Tailles
    
        Propriétés: 'minLength' et 'maxLength'
        Type: int
        Indispensable: non pour le min et oui pour le max

        ```php
        'username' => array(
            'minLength' => 5,
            'maxLength' => 30,
            ...
        ```
        
    - #### 3.2.4 Clés Uniques et Primaires
    
        Propriétés: 'unique' et 'primaryKey'
        Type: bool
        Indispensable: non

         ```php
        'id' => array(
            'primaryKey' => true,
            ...
        'email' => array(
            'unique' => true,
            ...
        ```

    - #### 3.2.5 Clés Étrangères
    
        Propriété: 'foreignKey => array('table' => ..., 'column' => ..., 'constraint' => ...)'
        Type: array(string, string, bool)
        Indispensable: non
        
         ```php
        'user_id' => array(
            'foreignKey' => array(
                'table' => 'user',
                'column' => 'id',
                'constraint' => true
            ),
            ...
        ```
        
    - #### 3.2.6 Filtrer une Liste de Valeurs
    
        Propriété: 'only => array('valeur1', 'valeur2', ...)
        Type: array(string|int)
        Indispensable: non
        
         ```php
        'couleur' => array(
            'only' => array('rouge', 'orange', 'rose')
            ...
        ```

- ### 3.3 Propriété Options:

    - #### 3.3.1 Le Moteur de la Table
    
        Propriété: 'engine'
        Type: string
        valeurs acceptées: Tous les moteurs de stockage Mysql.
        Indispensable: oui
        
        ```php
        'engine' => 'InnoDB',
        ```
        
    - #### 3.3.2 Initialisation de l'Auto-Incrementation
    
        Propriété: 'auto_increment'
        Type: int
        Indispensable: oui
        
        ```php
        'auto_increment' => 0,
        ```
        
    - #### 3.3.3 Codage de Caractères par Défaut
    
        Propriété: 'default charset'
        Type: string
        valeurs acceptées: Tous les codages de caractères compatibles avec Mysql.
        Indispensable: oui
        
        ```php
        'default charset' => 'utf8'
        ```

## 4. Les Modèles

- ### 4.1 Présentation

    Les modèles sont composés de deux types de classe 'entity' et 'repository'. Elles permettent de communiquer avec les tables auquelles elles sont liées. Elles sont respectivement placées dans les dossiers: '\\src\\Entity' et '\\src\\Repository'.
    Il est possible de générer les classes à partir de l'interface 'devBoard' (voir chapitre 2) et de son 'schema' correspondant à la table (voir chapitre 3).

- ### 4.2 Entity

    La classe est composée de propriétés privées correspondantes aux colonnes présentes dans la table ainsi que de leurs accesseurs et mutateurs (mutateurs: uniquement pour les colonnes le permettant).
    Elle peut être utilisée pour automsatiser certains traitement lors d'une insertion ou d'une selection en base de données.
    Elle hérite de la classe abstraite '\\core\\AbstractEntity'. Cette dernière possède quelques méthodes dont l'appel aux validations des entrées.
   
    - #### 4.2.1 Méthodes Héritées
    
        Valider des Entrées:
        ```php
        // $setColumns = true: enregistre automatiquement les valeurs à l'entity pour peu qu'elles soient valides!
        isValid(array $inputs, bool $setColumns = true): bool
        
        $post = new Post();
        if ($post->isValid(['title' => $_POST['title'], 'content' => $_POST['content']])
        {
            ...
        }
        ```
        Vérifier que l'Entrée est Unique:
        ```php
        // $idToExclude = $currentId: éviter que l'item à modifier ne soit pris en compte dans le test.
        isUnique(array $columns, int $idToExclude = null): bool
        ```
        Incrémenter l'Entrée tant qu'elle n'est pas Unique:
        ```php
        incrementAlreadyUsed(string $column): string
        $newUsername = $user->incrementAlreadyUsed('username');
        ```

- ### 4.3 Repository
    
    Cette classe hérite de la classe abstraite '\\core\\AbstractRepository' qui dispose de quelques méthodes permettant de communiquer avec la base de données.

    - #### 4.3.1 Méthodes Héritées
   
        Le nom des méthodes suivantes étant suffisamment clair, elles ne seront pas détaillées. Il s'agit de méthodes statiques!
    
        ```php
        findOneByCol(string $column, $value): ?Object
        ```
        ```php
        findUnique(string $column, $value, ?int $idToExclude): ?Object
        ```
        ```php
        findAll(): ?array
        ```
        ```php
        findAllLimitOffset(string $select, string $orderBy, string $limit, string $offset): ?array
        ```
        ```php
        findNextEarler($select, int $id, string $createdAt, string $createdColName = 'created_at'): ?Object
        ```
        ```php
        findNextLater($select, int $id, string $createdAt, string $createdColName = 'created_at'): ?Object
        ```
        ```php
        record(object $obj): bool
        ```
        ```php
        updateById(object $obj, int $id): bool
        ```
        ```php
        countRowByCol(string $column): int
        ```
    
    - #### 4.3.2 Requêtes Personnalisées
    
        Pour fabriquer de nouvelles requêtes, il suffit d'appeller la classe '\\core\\Request' dans le repository de la table correspondante.
        Voici les méthodes disponibles:
        
        ```php
        select($columns): self
        ```
        ```php
        from(string $table): self
        ```
        ```php
        insertInto(string $table, array $binds): self
        ```
        ```php
        update(string $table, array $binds): self
        ```
        ```php
        innerJoin(string $link): self
        ```
        ```php
        on(string $table): self
        ```
        ```php
        where|and|or(string $column, string $operator, $value): self
        ```
        ```php
        count($column): self
        ```
        ```php
        orderBy(string $orderBy): self
        ```
        ```php
        limit(int $size): self
        ```
        ```php
        offset(int $size): self
        ```
        ```php
        on(string $table): self
        ```
        ```php
        fetchClass(): ?object
        ```
        ```php
        fetchAllClass(): ?array
        ```
        ```php
        fetchNum(): ?array
        ```
        Voici un exemple d'utilisation:
        
        ```php
        static function findPostsByPage(int $itemsByPage, int $firstItemIndex)
        {
            $request = new Request();
            $output = $request
                ->select('post.*, user.username AS user_name, user.role AS user_role')
                ->from('post')
                ->innerJoin('user')
                ->on('post.user_id = user.id')
                ->orderBy('post.created_at DESC')
                ->limit($itemsByPage)
                ->offset($firstItemIndex)
                ->fetchAllClass();
            return $output ?: null;
        }
        ```
        
## 5. Les Controlleurs

- ### 5.1 Présentation

    Les controlleurs sont situés dans le dossier '\\src\\Controller', ils sont appelés par le routeur (voir chapitre 8) et permettent de faire le lien entre les 'modèles' (voir chapitre 4) et les 'vues' (voir chapitre 6). Ils héritent de la classe abstraite ‘\core\AbstractController’.

    Voici à quoi peut ressembler la structure de la classe:

    ```php
    class PostController extends AbstractController
    {
        protected $varPage = [
            'title' => 'APP-PHP::ARTICLE',
            'h1' => 'APP-PHP',
            'h2' => 'Article',
        ];
    
        public function show(int $id, string $slug): void
        {
        }
        public function new(): void
        {
        }
        public function create(): void
        {
        }
        public function edit(int $id, string $slug): void
        {
        }
        public function update(int $id, string $slug): void
        {
        }
    }
    ```
- ### 5.2 La Variable '$varPage'

    La variable '$varPage' est de type array et est utilisée pour transmettre les informations (titre de la page, entrées utilisateur, résultat des requêtes faites en base de données, fichiers javascript, etc.) du controlleur vers la vue.
    
    Il existe trois clés dont le nom est réservé:
    - javascript: de type array, utilisée pour transmettre le nom des fichiers javascript qu'il faut charger sur la page.
        
        ```php
       $this->varPage['javascript'][] = 'confirmPassword';
       ```
       
    - css: de type array, utilisée pour transmettre le nom des fichiers css qu'il faut charger sur la page.
    - content: de type string, il s'agit du contenu de la vue appelée par son controlleur et la méthode 'renderer'.
    

- #### 5.3 Méthodes Héritées

    Afficher une Vue (voir chapitre 6):
    ```php
    renderer(string $class, string $method): void
    
    $this->renderer('PostView', 'show');
    ```
    Sauvegarder les Entrées:
    ```php
    recordInputs(array $inputs): void
    
    $this->recordInputs(['title' => $_POST['title'], 'content' => $_POST['content']]);
    ```
    Récuperer les Entrées Sauvegardées:
    ```php
    getRecordedInputs(): void
    
    $recordedInputs = $this->getRecordedInputs();
    ```   
    Redirections:
    ```php
    redirect(string $route, ?array $params = []): void
    
    // rediriger si l'utilisateur n'est pas connecté:
        $this->redirect('connection', ['logged' => false]);
    // rediriger si le niveau d'autorisation de l'utilisateur est inférieur à 2:
        $this->redirect('connection', ['logged' => true, 'minRole' => 2]);
    // rediriger avec des paramètres dans l'url:
        $this->redirect('articles', ['url' => ['page' => 1]]);
    ```
    
## 6. Les Vues

- ### 6.1 Présentation

    Les vues sont situés dans le dossier '\\src\\View', elles sont appelées par la méthode 'render' des controlleurs (voir chapitre 5). Une fois la vue chargée, elle est envoyée vers le template '\\src\\View\\Template'.
    Elles héritent de la classe abstraite ‘\core\AbstractView’.
    
    Voici à quoi peut ressembler la structure de la classe:

    ```php
    Class PostView
    {
        // la page affichant l'article
        public static function show($varPage)
        {
            ob_start(); ?>
            <div>
                ...
            </div>
            <?php return ob_get_clean();
        }
        // la page du formulaire d'édition de l'article
        public static function edit($varPage)
        {
            ob_start(); ?>
            <div>
                ...
            </div>
            <?php return ob_get_clean();
        }
    }
    ```

## 7. L'Autoloader

- ### 7.1 Configuration

    Dans le fichier '\\public\\index.php' il faut appeler le fichier 'Autoloader.php':
    
    ```php
    require_once $_SERVER['DOCUMENT_ROOT'] . '/core/Autoloader.php';
    ```

    Il est possible de personnaliser le namespace principal du projet ainsi que son dossier à l'aide du fichier '\\cvm_php_init.json'.

    ```php
    {
        ...
        "autoload":{
            "namespace": "App\\",
            "directory": "src/"
    }
    ```

## 8. Le Routeur

- ### 8.1 Configuration

    Toutes les requêtes réalisées en dehors des sous-dossiers '\\public\\...' doivent être redirigées vers le fichier '\\public\\index.php'.
    
    Dans le fichier '\\.htaccess':
    ```php
    RewriteEngine On

    RewriteCond %{REQUEST_URI} !(public/css|public/images|public/js)
    RewriteRule ^(.*)$ public/index.php?url=$1 [QSA,L]
    ```
    
    Le routeur doit être déployé dans le fichier '\\public\\index.php':

    ```php
    use Core\Router\Router;
    
    Router::init();
    
    // les routes...
    
    Router::run();
    ```
    
- ### 8.2 Structure d'une Route

    Voici un exemple de route:
    
    ```php
    Router::get('/post/new', 'PostController#new', 'newPost');
    ```
    
    Lors de la configuration d'une route, le premier paramètre corresond à l'url:

    ```php
    Router::get('/post/new', ..., ...);
    ```
    
    Le second paramètre correspond au controlleur et à la méthode appelés par l'url:
    
    ```php
    Router::get(..., 'PostController#new', ...);
    ```
    
    Quant au dernier, il est facultatif, c'est le nom qui permet de récuperer l'url:
    
     ```php
    Router::get(..., ..., 'newPost');
    ```

- ### 8.3 Gestion des Paramètres en Url

    Exemple de route avec paramètres:
    
    ```php
    Router::get('/post/edit/:id-:slug', 'PostController#edit', 'editPost')->with('id', '[0-9]+')->with('slug', '([a-z\-0-9]+)');
    ```
    
    La méthode 'with' permet de configurer les paramètres attendus dans l'url à l'aide de regex.
    
- ### 8.4 Les Methodes de Requête Personnalisées

    Pour appeler les méthodes de requête personnalisées ('delete' et 'put'), il suffit d'ajouter un input de type hidden portant comme nom: 'method' et comme valeur, le nom de la méthode désirée.

    Exemple d'une requête 'delete':
    
    ```php
    <input name="method" type="hidden" value="delete">
    ```
    
- ### 8.5 Récupérer une Url à Partir d'une Route

    ```php
    Router::url('connection');
    ```

## 9. Gestion des Messages

- ### 9.1 Présentation

    Le gestionnaire s'occupe de transmettre les messages à l'utilisateur par l'intermédiaire des vues. Les messages liés aux entrées introduites par l'utilisateur (url, formulaire, etc.) sont ajoutés automatiquement à la liste des messages actifs.

- ### 9.2 Configuration

    Il est possible d'ajouter de nouveaux messages, d'ajouter de nouvelles langues ou encore de personnaliser les messages existants en modifiant le fichier '\\src\\Config\\messages.json'.

    Voici à quoi ressemble la structure d'un message:
    
    ```php
    {
    ...
    "type_email": {
        "type":   "error",
        "content": {
            "fr":   "Adresse email non valide",
            "en":   "Email address Invalid"
        }
    },
    ...
    ```

- ### 9.3 Activer les Messages

    Lors d'une entrée utilisateur (formulaire, url, etc.), les messages d'erreur sont automatiquement ajoutés à la liste des messages activés. Dans ce cas, le nom de la variable utilisée pour enregistrer le message portera le nom du champ concerné avec le suffixe 'Sms'.

    Exemple:
    
    ```php
    // L'erreur sera enregistrée dans la variable 'titleSms'.
    <input type="text" name="title">
    ```
    
    Dans le controlleur, il est aussi possible d'activer manuellement des messages:
    
    ```php
    // Placer le message 'registerComplete' dans la variable 'info'.
    MessagesManager::add(['info' => ['registerComplete' => null]]);
    // La plupart du temps on assigne 'null' comme valeur au message. Cependant, il est possible de transmettre une chaîne de caractères aux messages disposant de {{x}} dans leur contenu.
    ```

- ### 9.4 Importation et Affichage

    Dans le controlleur:
    
    ```php
    $this->varPage['messages'] = MessagesManager::getMessages();
    ```

    Dans la vue:
    
    ```php
    public static function form($varPage)
    {
        ob_start(); ?>
        <form method='post'>
            ...
            <input type="text" name="title">
            <?=$varPage['messages']['titleSms'] ?? ''?>
            ...
        </form>
        <?php return ob_get_clean();
    }
    ```

## 10. Pagination

- ### 10.1 Initialisation

    Exemple:
    
    ```php
    // Pagination::init(nom de la table, le nombre d'éléments par page, la page actuelle)
    $firstItemIndex = Pagination::init('post', $itemsByPage, $currentPage);
    ```
    
    Pagination:init() peut retourner:
    - -2 si la page n'existe pas.
    - -1 si aucun élément n'a été trouvé.
    - Un entier positif représentant l'index du premier élément de la page actuelle. Cette valeur servira d'offset pour la requête réalisée avec les repositories personnalisés (voir l'exemple du chapitre 4.3.2)!

- ### 10.2 Importer les Boutons de Pagination dans la Vue

    Le paramètre représente le nombre maximum de liens menant directement vers leur page pour chaque direction. Dans l'exemple qui suit, il y'aura donc maximum 4 liens menant vers les pages précédentes et maximum 4 liens menant vers les pages suivantes.
    
    Exemple:
    
    ```php
    Pagination::getNav(4) ?: 'Aucun article trouvé!';
    ```

## 11. Gestion des Mails

- ### 11.1 Configuration

    Dans le fichier '\\src\\Config\\security.json':

    ```php
    ...
    "mail": {
        "smtp": "smtp.fai.be",
        "sendmail_from": "blabla@blibli.com",
        "smtp_port": "25"
    },
    ```

- ### 11.1 Construire l'Email

    Le header, le sujet ainsi que le contenu sont montés à l'aide d'une classe dédiée s'appuyant sur 'AbstractMail'. Le fichier doit être présent dans le dossier '\\src\\Mail\\'.
    
    Exemple:
    
    ```php
    namespace App\Mail;
    
    class TestMail extends AbstractMail
    {
        protected static function getHeader(): string
        {
            $header = "From: \"PHP-APP\"<php_app@cvm.com>\n";
            $header .= "Content-Type: text/html; charset=\"UTF-8\"\n";
            $header .= "Content-Transfer-Encoding: 8bit";
            return $header;
        }
    
        protected static function getSubject(): string
        {
            return 'Le Sujet de Mon Mail';
        }
    
        protected static function getMessage(array $vars = []): string
        {
            ob_start(); ?>
            <div>
                <h1>Mail de Test</h1>
                <p>blablabla...</p>
            </div>
            <?php return ob_get_clean();
        }
    }
    ```
    
- ### 11.2 Envoie du Mail

    Dans le constructeur, appeler la classe réalisée précédemment:
    
    ```php
    use App\Mail\TestMail;
    ...
    TestMail::send($emailAddress, ['param1' => $value1, 'param2' => $value2]);
    ```

## 12. Authentification

- ### 12.1 Compte Dédiée

    - ### 12.1.1 Configuration
    
        Pour fonctionner, la classe d'authentification à besoin d'une table 'user' composée au minimum des colonnes suivantes: 'id', 'username', 'password', 'role'. Par defaut, il existe un schema 'user' complet (voir chapitre 3).
        
    - ### 12.1.2 Connexion
    
        La validation des entrées utilisateurs et la gestion des messages d'erreur se fera automatiquement. La connexion nécessite uniquement le nom d'utilisateur et le mot de passe:
        
        ```php
        // retourne false | true (si true alors ajoute l'utilisateur à la session!)
    	Auth::login($_POST['username'], $_POST['password']);
        ```
        
    - ### 12.1.3 Déconnexion
    
        ```php
        // retourne true | false
    	Auth::removeUserFromSession();
        ```
        
    - ### 12.1.4 Récupérer l'Utilisateur
    
        ```php
        // retourne null | utilisateur
        $user = Auth::user();
        ```
        
    - ### 12.1.5 Redirections
    
        Les méthodes liées aux redirections appartiennent aux controlleurs (voir chapitre 5.3).
        
- ### 12.2 Compte Google (OAuth 2.0)

    - ### 12.2.1 Configuration
    
        Configurer votre application à l'aide de la plateforme 'Google API'.
        Configurer le fichier '\\src\\Config\\security.json' avec les informations récupérées au point précédent:
        
        ```php
        ...
        "oauth": {
    		"google": {
    			"goole_id": "xXx1",
    			"google_secret": "xXx2",
    			"google_route": "routePourTraiterLaCo"
    		}
    	}
    	...
        ```

        Dans le controlleur:

        ```php
        $oauthGoogle = Oauth::getConfig('google');
        // Pour l'utilisation de '$this->varPage' (voir chapitre 5.2).
        $this->varPage['goole_id'] = $oauthGoogle['goole_id'];
		$this->varPage['google_route'] = $oauthGoogle['google_route'];
        ```
        
        Dans la vue:
        
        ```php
        <a href="https://accounts.google.com/o/oauth2/v2/auth?scope=profile email&access_type=online&redirect_uri=<?=Router::url($varPage['google_route'])?>&response_type=code&client_id=<?= $varPage['goole_id'] ?>">SE CONNECTER AVEC GOOGLE</a>
        ```
            
    - ### 12.2.2 Connexion

        Dans le controlleur appelé par 'google_route':
    
        ```php
    	$oauth = new Oauth();
		if ($oauth->login('google'))
		{
			// welcome!
		}
	    ```
	    
## 13. Sources

- [Grafikart](https://www.grafikart.fr/)
- [OpenClassrooms](https://openclassrooms.com/fr/)
- [PHP - Documentation](https://www.php.net/manual/fr/intro-whatis.php)
- [Stack Overflow](https://stackoverflow.com/)