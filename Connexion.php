<?php
// Connexion à la base de données
$dbname = 'mysql:host=localhost;dbname=llefeb09;port=1521;charset=utf8';
$username = 'llefeb09'; // Identifiant sell 
$password = '22403478'; // Mot de passe sel

// Vérification de la connexion 
try {
    $pdo = new PDO($dbname,$username,$password);
}
catch (PDOException $e) {
    die("La connexion à la BDD est impossible : <br>". $e->getMessage());
} //Affichage d'un message en cas d'erreur
?>