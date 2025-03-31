<?php
session_start();
include("Connexion.php"); // Connexion à la base de données

// Vérifier si l'utilisateur a les droits nécessaires
if (!isset($_SESSION["Id_Employe"]) || $_SESSION["Acces"] !== 'Tout') {
    header("Location: Accueil.html");
    exit();
}

// 1. Nombre total de réservations
$sql_total_reservations = "SELECT COUNT(*) AS totalReservations FROM Reservation";
$stmt_total_reservations = $pdo->prepare($sql_total_reservations);
$stmt_total_reservations->execute();
$totalReservations = $stmt_total_reservations->fetch(PDO::FETCH_ASSOC)['totalReservations'];

// 2. Nombre de réservations avec emplacement de camping
$sql_camping_reservations = "SELECT COUNT(*) AS totalCampingReservations FROM Reservation WHERE Num_Emplacement IS NOT NULL";
$stmt_camping_reservations = $pdo->prepare($sql_camping_reservations);
$stmt_camping_reservations->execute();
$totalCampingReservations = $stmt_camping_reservations->fetch(PDO::FETCH_ASSOC)['totalCampingReservations'];

// 3. Places disponibles au camping (total : 150, handicapés : 15)
$placesTotales = 150;
$placesHandicapes = 15;
$placesRestantes = $placesTotales - $totalCampingReservations;

// 4. Produits en stock
$sql_produits = "SELECT NomP, Quantite_Stock, Seuil_Alerte FROM Produit";
$stmt_produits = $pdo->prepare($sql_produits);
$stmt_produits->execute();
$produits = $stmt_produits->fetchAll(PDO::FETCH_ASSOC);

// 5. Budget total des musiciens
$sql_budget = "SELECT SUM(Budget) AS totalBudget FROM Musicien";
$stmt_budget = $pdo->prepare($sql_budget);
$stmt_budget->execute();
$budgetMusiciens = $stmt_budget->fetch(PDO::FETCH_ASSOC)['totalBudget'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord</title>
    <link rel="stylesheet" href="ProjetAnn.css"> <!-- Ajoute ton fichier CSS -->
</head>
<body>
<form action="Accueil.html" method="get">
    <button type="submit" class="onglets">Retour</button>
</form>
    <h1>Tableau de Bord du Festival</h1>

    <h2>1. Statistiques Globales</h2>
    <p><strong>Total des réservations :</strong> <?php echo $totalReservations; ?> / 1000</p>
    <p><strong>Réservations avec emplacement de camping :</strong> <?php echo $totalCampingReservations; ?> / 150</p>

    <h2>2. Disponibilités Camping</h2>
    <p><strong>Places totales :</strong> 150</p>
    <p><strong>Places réservées :</strong> <?php echo $totalCampingReservations; ?></p>
    <p><strong>Places restantes :</strong> <?php echo $placesRestantes; ?></p>
    <p><strong>Places réservées pour personnes handicapées :</strong> <?php echo min($totalCampingReservations, $placesHandicapes); ?> / 15</p>

    <h2>3. Produits en Stock</h2>
    <table border="1">
        <tr>
            <th>Produit</th>
            <th>Quantité</th>
            <th>Seuil Alerte</th>
            <th>Statut</th>
        </tr>
        <?php foreach ($produits as $produit): ?>
        <tr>
            <td><?php echo htmlspecialchars($produit['NomP']); ?></td>
            <td><?php echo $produit['Quantite_Stock']; ?></td>
            <td><?php echo $produit['Seuil_Alerte']; ?></td>
            <td>
                <?php if ($produit['Quantite_Stock'] <= $produit['Seuil_Alerte']) {
                    echo "<span style='color: red;'>⚠ Stock bas</span>";
                } else {
                    echo "<span style='color: green;'>✅ OK</span>";
                } ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <h2>4. Budget des Musiciens</h2>
    <p><strong>Total :</strong> <?php echo $budgetMusiciens; ?> €</p>

</body>
</html>
