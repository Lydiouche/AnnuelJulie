<?php
// Inclure votre fichier de connexion
include('connexion.php'); // Assurez-vous que c'est le bon chemin vers votre fichier de connexion

// Récupération des données de la table Musicien
try {
    $requete = $pdo->prepare("SELECT * FROM Musicien");
    $requete->execute();
    $musiciens = $requete->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors de la récupération des musiciens : <br>" . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Informations</title>
    <link rel="stylesheet" href="ProjetAnn.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        /* Style pour le tableau des musiciens - peut aussi être déplacé dans votre fichier CSS principal */
        .tableau-musiciens {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 16px;
        }
        .tableau-musiciens th, .tableau-musiciens td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        .tableau-musiciens th {
            background-color: #4CAF50;
            color: white;
            font-weight: bold;
        }
        .tableau-musiciens tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .tableau-musiciens tr:hover {
            background-color: #ddd;
        }
        .section-titre {
            text-align: center;
            margin: 30px 0;
            color: #333;
            font-size: 24px;
        }
    </style>
</head>
<body>
    <!-- Tout votre code HTML original reste ici -->
    <header class="TitrePage">
        <div class="TitreEtLogo">
            <div id="TitreMenu">
                <h1><strong>Informations !</strong></h1>
            </div>
            <img id="Logo" src="Logo festival.png" width="200" height="200">
        </div>
        <a href="#popup" class="login-bouton">Se connecter</a>
    </header>
    
    <div class="navigation">
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
    
    <form class="BarreRecherche">
        <input type="text" name="text" class="BarreRecherche" placeholder="Rechercher" list="suggestions">
        <button type="submit" name="submit" class="submit">
            <i class="fas fa-search"></i>
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
    
    <!-- Ajout du tableau des musiciens -->
    <h2 class="section-titre">Liste des Musiciens</h2>
    
    <?php if (!empty($musiciens)): ?>
        <table class="tableau-musiciens">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Groupe</th>
                    <th>Instrument</th>
                    <th>Scène</th>
                    <th>Horaire de passage</th>
                    <th>Jour de passage</th>
                    <th>Budget</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($musiciens as $musicien): ?>
                <tr>
                    <td><?php echo htmlspecialchars($musicien['Id_Musicien']); ?></td>
                    <td><?php echo htmlspecialchars($musicien['NomM']); ?></td>
                    <td><?php echo htmlspecialchars($musicien['PrenomM']); ?></td>
                    <td><?php echo htmlspecialchars($musicien['Groupe']); ?></td>
                    <td><?php echo htmlspecialchars($musicien['Type_Instru']); ?></td>
                    <td><?php echo htmlspecialchars($musicien['Num_Scene']); ?></td>
                    <td><?php echo htmlspecialchars($musicien['Horaire_Passage']); ?></td>
                    <td><?php echo htmlspecialchars($musicien['Jour_Passage']); ?></td>
                    <td><?php echo htmlspecialchars($musicien['Budget']); ?> €</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p style="text-align: center; color: #666;">Aucun musicien trouvé dans la base de données.</p>
    <?php endif; ?>
    
    <div id="popup" class="popup">
        <h2>Créer un compte</h2>
        <input type="text" placeholder="Nom d'utilisateur" required>
        <input type="email" placeholder="Email" required>
        <input type="password" placeholder="Mot de passe" required>
        <button>S'inscrire</button>
        <a href="#" class="close-btn">Fermer</a>
    </div>
</body>
</html>
