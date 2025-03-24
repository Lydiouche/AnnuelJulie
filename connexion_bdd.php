<?php

$USER = 'jbeloeil';
$PASSWORD = '22409499';
$DBN = 'mysql:host=localhost;dbname=jbeloeil;port=1521'; //Mettre le nom de la base de données, ici, votre identifiant phpmyadmin


try{
       //Création d’une instance PDO
       $bdd = new PDO($DBN,$USER,$PASSWORD);
      echo('Connexion réussie !'); 
}
catch(PDOException $e){
       echo('Echec de connexion : '.$e->getMessage()); 
}

?>


 



