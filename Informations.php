<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"> 
    <title>Informations</title>
    <link rel="stylesheet" href="ProjetAnn.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body>
    <!-- Contenu de la page ici -->
     <!--En tête du site-web-->
<header class="TitrePage">
    <div class="TitreEtLogo">
      <div id="TitreMenu">
        <h1><strong>Informations !</strong></h1>
      </div>
      <img id="Logo" src="Logo festival.png" width="200" height="200">
    </div>
    <a href="#popup" class="login-bouton">Se connecter</a>

</header>

<!-- Boutons pour accéder aux autres pages -->
<div class="navigation">
    <!-- Boutons sous forme de formulaires -->
     <!--Le action permet de spécifier l'URL vers où est envoyé le formulaire-->
     <!--Le get permet d'envoyer les données-->
     <!--Button et submit permettent de soumettre le formulaire à l'URL-->
    <form action="Accueil.html" method="get">
      <button type="submit" class="onglets">Accueil</button>
    </form>
    <form action="Inscription.html" method="get">
        <button type="submit" class="onglets">Inscription</button>
    </form>
    <form action="Mon compte.html" method="get">
        <button type="submit" class="onglets">Mon compte</button>
    </form>
    <form action="Contact.html" method="get">
        <button type="submit" class="onglets">Contact</button>
    </form>
  </div>
  
<!-- Barre de recherche sous le titre -->
<form class="BarreRecherche">
    <input type="text" name="text" class="BarreRecherche" placeholder="Rechercher" list="suggestions">
    <button type="submit" name="submit" class="submit">
      <i class="fas fa-search"></i> <!--Pour avoir la loupe-->
    </button>
    <datalist id="suggestions">
      <option value="Visualisation festivalier/camping">
      <option value="Gestion des musiciens">
      <option value="Gestion des accès 'Employé'">
      <option value="Tableau de bord récapitulatif">
      <option value="Gestion des stocks pour la buvette/restaurant">
      <option value="Gestion des objets perdus">
    </datalist>
  </form>
<!-- Pop-up de connexion -->
<div id="popup" class="popup">
  <h2>Connexion</h2> 
  <form method="POST" action="Connexion_site.php">
    <input type="int" name="Id_Employe" placeholder="Id_Employe" required>
    <input type="password" name="Code_Barre" placeholder="Code_Barre" required>
    <button type="submit">Se connecter</button>
  </form>
  <a href="#" class="close-btn">Fermer</a>
</div>
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