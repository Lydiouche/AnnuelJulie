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
  <!-- En-tête du site-web -->
  <header class="TitrePage">
  <div class="navigation">
  <form action="Accueil.html" method="get">
    <button type="submit" class="onglets">Retour</button>
  </form>
</div>
    <div class="TitreEtLogo">
      <div id="TitreMenu">
        <h1><strong>Gestion Musicien</strong></h1>
      </div>
      <img id="Logo" src="Logo festival.png" width="200" height="200">
    </div>
  </header>

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
      <label for="nom">Nom :</label>
      <input type="text" name="nom" required>

      <label for="prenom">Prénom :</label>
      <input type="text" name="prenom" required>

      <label for="groupe">Groupe :</label>
      <input type="text" name="groupe">

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

      <label for="nom">Nom :</label>
      <input type="text" name="nom" required>

      <label for="prenom">Prénom :</label>
      <input type="text" name="prenom" required>

      <label for="groupe">Groupe :</label>
      <input type="text" name="groupe">

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

  <?php
  include("Connexion.php");

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $action = $_POST['action'];

      $nom = $_POST['nom'] ?? '';
      $prenom = $_POST['prenom'] ?? '';
      $groupe = $_POST['groupe'] ?? '';
      $instrument = $_POST['instrument'] ?? '';
      $num_scene = $_POST['num_scene'] ?? '';
      $horaire = $_POST['horaire'] ?? null;
      $jour = $_POST['jour'] ?? null;
      $budget = $_POST['budget'] ?? 0;
      $id_musicien = $_POST['id_musicien'] ?? null;

      // Ajouter un musicien
      if ($action == "ajouter") {
          $requete = $pdo->prepare("INSERT INTO Musicien (NomM, PrenomM, Groupe, Type_Instru, Num_Scene, Horaire_Passage, Jour_Passage, Budget) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
          $requete->execute([$nom, $prenom, $groupe, $instrument, $num_scene, $horaire, $jour, $budget]);
      }
      // Modifier un musicien
      elseif ($action == "modifier" && !empty($id_musicien)) {
          $requete = $pdo->prepare("UPDATE Musicien SET NomM=?, PrenomM=?, Groupe=?, Type_Instru=?, Num_Scene=?, Horaire_Passage=?, Jour_Passage=?, Budget=? WHERE Id_Musicien=?");
          $requete->execute([$nom, $prenom, $groupe, $instrument, $num_scene, $horaire, $jour, $budget, $id_musicien]);
      }
      // Supprimer un musicien
      elseif ($action == "supprimer" && !empty($id_musicien)) {
          $requete = $pdo->prepare("DELETE FROM Musicien WHERE Id_Musicien=?");
          $requete->execute([$id_musicien]);
      }

      header("Location: Gestion_Musicien.php");
      exit();
  }
  ?>

  <!-- Affichage des musiciens dans un tableau -->
  <div class="Tabl_musicien">
    <?php
    include("Connexion.php");
    $requete = $pdo->prepare("SELECT * FROM Musicien");
    $requete->execute();
    $resultats = $requete->fetchAll(PDO::FETCH_ASSOC);

    if (count($resultats) > 0) {
        echo "<table class='musicien-table'>
                <tr>";

        foreach ($resultats[0] as $colonne => $valeur) {
            echo "<th>" . htmlspecialchars($colonne) . "</th>";
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
    ?>
  </div>

</body>
</html>
