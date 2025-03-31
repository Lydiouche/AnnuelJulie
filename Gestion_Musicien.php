
<?php
  include("Connexion.php");
  $message = ""; // Variable pour afficher le message d'erreur

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $action = $_POST['action'];

      $NomDeScene = $_POST['nom'] ?? '';
      $instrument = $_POST['instrument'] ?? '';
      $num_scene = $_POST['num_scene'] ?? '';
      $horaire = $_POST['horaire'] ?? null;
      $jour = $_POST['jour'] ?? null;
      $budget = $_POST['budget'] ?? 0;
      $id_musicien = $_POST['id_musicien'] ?? null;

      // Ajouter un musicien
      if ($action == "ajouter") {
        // Vérification si la scène est déjà occuper par autre musicien
        $verif_requete = $pdo->prepare("SELECT COUNT(*) FROM Musicien WHERE Num_Scene = ? AND Horaire_Passage = ? AND Jour_Passage = ?");
        $verif_requete->execute([$num_scene, $horaire, $jour]);
        $deja_pris = $verif_requete->fetchColumn();

        if ($deja_pris > 0) {
            $message = "Ajout impossible : scène déjà prise à cet horaire.";
        } else {

          $requete = $pdo->prepare("INSERT INTO Musicien (NomDeScene, Type_Instru, Num_Scene, Horaire_Passage, Jour_Passage, Budget) VALUES (?, ?, ?, ?, ?, ?)");
          $requete->execute([$NomDeScene, $instrument, $num_scene, $horaire, $jour, $budget]);
          header("Location: Gestion_Musicien.php"); // Rafraîchir la page
          exit();
        }
      }
      // Modifier un musicien
      elseif ($action == "modifier" && !empty($id_musicien)) {
          $requete = $pdo->prepare("UPDATE Musicien SET NomDeScene=?, Type_Instru=?, Num_Scene=?, Horaire_Passage=?, Jour_Passage=?, Budget=? WHERE Id_Musicien=?");
          $requete->execute([$NomDeScene, $instrument, $num_scene, $horaire, $jour, $budget, $id_musicien]);
          header("Location: Gestion_Musicien.php");
          exit();
        }
      // Supprimer un musicien
      elseif ($action == "supprimer" && !empty($id_musicien)) {
          $requete = $pdo->prepare("DELETE FROM Musicien WHERE Id_Musicien=?");
          $requete->execute([$id_musicien]);
          header("Location: Gestion_Musicien.php");
          exit();
      }
  }
  ?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Gestion Musicien</title>
  <link rel="stylesheet" href="Gestion_Musicien.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
<?php if (!empty($message)) : ?>
<div class="error-popup">
    <p><?php echo htmlspecialchars($message); ?></p>
    <a href="Gestion_Musicien.php" class="close-btn">Fermer</a>
</div>
<?php endif; ?>
  <!-- En-tête du site-web -->
  <header class="TitrePage">
  <div class="navigation">
  <form action="Deconnexion_Site.php" method="post">
    <button type="submit" class="onglets" >Se déconnecter</button>
</form>
  <form action="Accueil.html" method="get">
    <button type="submit" class="onglets">Retour</button>
  </form>
  <form action="Planning.php" method="get">
    <button type="submit" class="onglets">Planning</button>
  </form>

</div>

    <div class="TitreEtLogo">
      <div id="TitreMenu">
        <h1><strong>Gestion Musicien</strong></h1>
      </div>
      <img id="Logo" src="Logo festival.png" width="200" height="200">
    </div>
  </header>
  <?php if (!empty($message)) : ?>
    <div class="error-message"><?php echo htmlspecialchars($message); ?></div>
<?php endif; ?>

  <!-- Conteneur des boutons pour afficher les pop-ups -->
  <div class="container">
    <a href="#ajoutPopup" class="btn">Ajouter Musicien</a>
    <a href="#modifierPopup" class="btn">Modifier Musicien</a>
    <a href="#supprimerPopup" class="btn">Supprimer Musicien</a>
  </div>

  <!-- Pop-up pour ajouter un musicien -->
  <div id="ajoutPopup" class="popup">
    <h2>Ajouter un Musicien</h2>
    <form method="POST" action="Gestion_Musicien.php">
      <label for="NomDeScene">Nom de Scene:</label>
      <input type="text" name="NomDeScene" required>

      <label for="instrument">Instrument :</label>
      <input type="text" name="instrument">

      <label for="num_scene">Numéro de scène :</label>
      <input type="text" name="num_scene">

      <label for="horaire">Horaire de passage :</label>
      <input type="TIME" name="horaire">

      <label for="jour">Jour de passage :</label>
      <input type="date" name="jour">

      <label for="budget">Budget :</label>
      <input type="number" name="budget">

      <button type="submit" name="action" value="ajouter">Ajouter</button>
    </form>
    <a href="#" class="close-btn">Fermer</a>
  </div>

  <!-- Pop-up pour modifier un musicien -->
  <div id="modifierPopup" class="popup">
    <h2>Modifier un Musicien</h2>
    <form method="POST" action="Gestion_Musicien.php">
      <label for="id_musicien">ID Musicien :</label>
      <input type="text" name="id_musicien" required>

      <label for="NomDeScene">Nom de Scene:</label>
      <input type="text" name="NomDeScene" required>

      <label for="instrument">Instrument :</label>
      <input type="text" name="instrument">

      <label for="num_scene">Numéro de scène :</label>
      <input type="text" name="num_scene">

      <label for="horaire">Horaire de passage :</label>
      <input type="TIME" name="horaire">

      <label for="jour">Jour de passage :</label>
      <input type="date" name="jour">

      <label for="budget">Budget :</label>
      <input type="number" name="budget">

      <button type="submit" name="action" value="modifier">Modifier</button>
    </form>
    <a href="#" class="close-btn">Fermer</a>
  </div>

  <!-- Pop-up pour supprimer un musicien -->
  <div id="supprimerPopup" class="popup">
    <h2>Supprimer un Musicien</h2>
    <form method="POST" action="Gestion_Musicien.php">
      <label for="id_musicien">ID Musicien :</label>
      <input type="text" name="id_musicien" required>

      <button type="submit" name="action" value="supprimer">Supprimer</button>
    </form>
    <a href="#" class="close-btn">Fermer</a>
  </div>



  <!-- Affichage des musiciens dans un tableau -->
  <div class="Tabl_musicien">
    <?php

    $requete = $pdo->prepare("SELECT 
    Id_Musicien, 
    NomDeScene, 
    Type_Instru, 
    Num_Scene, 
    Horaire_Passage, 
    DATE_FORMAT(Jour_Passage, '%d/%m/%Y') AS Jour_Passage, 
    Budget 
FROM Musicien");
    $requete->execute();
    $resultats = $requete->fetchAll(PDO::FETCH_ASSOC);

    //Définition des noms des colonnes : enlever l'id du musicien et fusionner sous Nom de scène
    $colonne_personnalisee = [
      "Id_Musicien"=>"ID Musicien",
      "NomDeScene"=>"Nom de Scene",
      "Type_Instru"=>"Instruments",
      "Num_Scene"=>"Numero de Scene",
      "Horaire_Passage"=>"Horaire de Passage",
      "Jour_Passage"=>"Jour de Passage",
      "Budget"=>"Budget"
    ];

    if (count($resultats) > 0) {
        echo "<table class='musicien-table border='1' cellpadding='10' cellspacing='0'>
                <tr>";

        foreach ($resultats[0] as $colonne => $valeur) {
          $nom_affiche= isset($colonne_personnalisee[$colonne])? $colonne_personnalisee[$colonne] : $colonne;
            echo "<th>" . htmlspecialchars($nom_affiche) . "</th>";
        }
        echo "</tr>";

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
