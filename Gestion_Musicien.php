
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Gestion Musicien</title>
  <link rel="stylesheet" href="ProjetAnn.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>

<!--En tête du site-web-->
<header class="TitrePage">
  <div class="TitreEtLogo">
    <div id="TitreMenu">
      <h1><strong>Gestion Musicien</strong></h1>
    </div>
    <img id="Logo" src="Logo festival.png" width="200" height="200">
  </div>
</header>

<!-- Affichage des musiciens dans un tableau -->
<div class="Tabl_musicien">
    <?php
    // Inclure la connexion à la base de données
    include("Connexion.php");
  
    // Préparer la requête SQL pour récupérer les données de la table Musicien
    $requete = $pdo->prepare("SELECT * FROM Musicien");
    $requete->execute();
  
    // Récupérer toutes les lignes de la table Musicien
    $resultats = $requete->fetchAll(PDO::FETCH_ASSOC);
  
    // Vérifier s'il y a des résultats
    if (count($resultats) > 0) {
        // Créer le tableau HTML
        echo "<table border='1' cellpadding='10' cellspacing='0'>
                <tr>";
        
        // Afficher les en-têtes du tableau (noms des colonnes)
        foreach ($resultats[0] as $colonne => $valeur) {
            echo "<th>" . htmlspecialchars($colonne) . "</th>";
        }
        echo "</tr>";
  
        // Afficher les données des musiciens
        foreach ($resultats as $ligne) {
            echo "<tr>";
            foreach ($ligne as $valeur) {
                echo "<td>" . htmlspecialchars($valeur) . "</td>";
            }
            echo "</tr>";
        }
  
        echo "</table>";
    } else {
        echo "<p>Aucun musicien trouvé dans la base de données.</p>";
    }
  
    // Fermer le curseur
    $requete->closeCursor();
    ?>
  </div>

</body>
</html>