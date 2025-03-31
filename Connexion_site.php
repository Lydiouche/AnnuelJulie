<?php
session_start(); // Démarrer la session
//Connexion à la base de donnée
include("Connexion.php");

//Vérification du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Id_Employe = $_POST["Id_Employe"];
    $Code_Barre = $_POST["Code_Barre"];

// Vérifier si l'ID employé existe et correspond au code barre
    $sql = "SELECT * FROM Employe WHERE Id_Employe = :Id_Employe AND Code_Barre = :Code_Barre";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":Id_Employe", $Id_Employe, PDO::PARAM_INT);
    $stmt->bindParam(":Code_Barre", $Code_Barre, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Vérification de l'existence de l'utilisateur
    if ($user) {
        $_SESSION["Id_Employe"] = $user["Id_Employe"];
        $_SESSION["nom"] = $user["Nom"];
        $_SESSION["Acces"] = $user["Acces"];

        // Insérer l'entrée de l'employé dans la table Acces
        $sql_acces = "INSERT INTO Acces (Zone, Date_Entree,Debut_Heure) VALUES (:Zone, NOW(),NOW())";
        $stmt_acces = $pdo->prepare($sql_acces);
        $stmt_acces->bindParam(":Zone", $_SESSION["Acces"], PDO::PARAM_STR);
        $stmt_acces->execute();

        // Récupérer l'ID de l'accès inséré pour la mise à jour à la déconnexion
        $_SESSION["Id_Acces"] = $pdo->lastInsertId();


        // Redirection en fonction du rôle
        if ($user["Acces"] == "Scene") {
            header("Location: Gestion_Musicien.php");
        } elseif ($user["Acces"] == "Tout") {
            header("Location: Statistiques.php"); //en cours 
        } else {
            header("Location: Accueil.html"); // Page par défaut pour les autres employés
        }
        exit();
    } else {
        echo "<script>alert('ID Employé ou Code Barre incorrect !'); window.location.href='index.php';</script>";
    }
}
?>
?>