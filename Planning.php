<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"> 
    <title>Planning</title>
    <link rel="stylesheet" href="Gestion_Musicien.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body>
<!-- En-tête du site-web -->
<header class="TitrePage">
    <div class="navigation">
    <form action="Deconnexion_Site.php" method="post">
    <button type="submit" class="onglets">Se déconnecter</button>
</form>
    <form action="Gestion_Musicien.php" method="get">
      <button type="submit" class="onglets">Retour</button>
    </form>
  </div>
      <div class="TitreEtLogo">
        <div id="TitreMenu">
          <h1><strong>Planning</strong></h1>
        </div>
        <img id="Logo" src="Logo festival.png" width="200" height="200">
      </div>
    </header>

    <?php
    INCLUDE("Connexion.php");
// Requête SQL pour récupérer les employés et leur planning
$sql ="SELECT 
    Employe.Id_Employe, 
    Employe.NomE, 
    Employe.PrenomE, 
    DATE_FORMAT(Planning.Debut_Jour, '%d/%m/%Y') AS Debut_Jour,  
    Planning.Debut_Heure, 
    DATE_FORMAT(Planning.Fin_Jour, '%d/%m/%Y') AS Fin_Jour,    
    Planning.Fin_Heure    
FROM Employe
LEFT JOIN Ligne_Employe ON Employe.Id_Employe = Ligne_Employe.Id_Employe
LEFT JOIN Planning ON Ligne_Employe.Id_Planning = Planning.Id_Planning
ORDER BY Planning.Debut_Jour, Planning.Debut_Heure;";

   // Exécution de la requête
$stmt = $pdo->prepare($sql);
$stmt->execute();

// Récupération des résultats
$plannings = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Vérification des données récupérées
if (!$plannings) {
    echo "<p style='text-align: center; color: red;'>Aucun planning trouvé</p>";
} else {

    echo "<table border='1'>";
    echo "<tr>
            <th>ID Employé</th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Jour Début</th>
            <th>Heure Début</th>
            <th>Jour Fin</th>
            <th>Heure Fin</th>
          </tr>";

    foreach ($plannings as $ligne) {
        echo "<tr>
                <td>{$ligne['Id_Employe']}</td>
                <td>{$ligne['NomE']}</td>
                <td>{$ligne['PrenomE']}</td>
                <td>{$ligne['Debut_Jour']}</td>
                <td>{$ligne['Debut_Heure']}h</td>
                <td>{$ligne['Fin_Jour']}</td>
                <td>{$ligne['Fin_Heure']}h</td>
              </tr>";
    }

    echo "</table>";
}
?>
</body>
</html>