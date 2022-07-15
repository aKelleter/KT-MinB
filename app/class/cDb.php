<?php

//-----------------------------------------------------------------------------

class Model
{

	// Tableau static pour stocker la connection à la base de données
	static $connections = array();

	// Choix des credentials de la base de donnée (voir conf.class.php)
	public $conf = 'default';

	// Variable pour stocker la table
	public $table = false;

	// Variable pour stocker la DB
	public $db;

	// Variable qui définit l'index à utiliser en fonction de la table sélectionnée
	public $primaryKey = 'id';
    
        // Variable de stockage des credentials
        public $credentials = NULL;

    



	public function __construct($table)
	{

		// Intialisation de variables
        
        // Chargement des credentials database dans $conf
        $this->credentials = ConfDB::$databases[$this->conf];

		// Initialise le nom de la table
		if($this->table === false)
		{
			// Passage de la table fournie en paramètre au constructeur
			$this->table = $this->credentials['prefix'].$table;

		}

		                                  

		// TEST si on a déjà une connection ouverte
		if(isset(Model::$connections[$this->conf]))
		{
			// Stockage de la connection dans la variable $db
			$this->db = Model::$connections[$this->conf];

			// Sortie du constructeur
			return true;
		}

		try
		{
			// Connection à la database 
            
            // DEBUG // $database = $credentials['prefix'].$credentials['database']; die($database);
            
			$pdo = new PDO('mysql:host='.$this->credentials['host'].';dbname='.$this->credentials['database'].';',
					$this->credentials['login'],
					$this->credentials['password'],
					array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
                          PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING
                    
                    )
			);
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

			// Stockages de la connection
			Model::$connections[$this->conf] = $pdo;
			$this->db = $pdo;

		}catch(PDOException $e){
            $dbug = new debug();
            if(K_DEBUG) $dbug->AKDebug($e); else die('Error connexion database - Active Debug Mode for more explanations');
		}


	}
   

}/*end of class*/