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

        // Redirection en fonction du rôle
        if ($user["Acces"] == "Scene") {
            header("Location: Gestion_Musicien.php");
        } elseif ($user["Acces"] == "Receptionniste") {
            header("Location: Accueil.html"); //en cours 
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