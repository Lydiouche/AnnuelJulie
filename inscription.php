<?php
session_start(); // Démarre la session
include('connexion_bdd.php'); // Connexion à la base de données
// Si le formulaire est soumis et qu'un pass est sélectionné, on sauvegarde la valeur du pass dans la session
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['pass'])) {
    $_SESSION['pass_selected'] = $_POST['pass'];
    // Maintenant, on peut récupérer cette valeur dans la session plus tard, ou l'utiliser pour l'inscription.
}

// Vérifier si un pass a été sélectionné dans la session
$pass = isset($_SESSION['pass_selected']) ? $_SESSION['pass_selected'] : '';



// Vérification si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Récupération des valeurs du formulaire avec sécurité
        $nom = $_POST['nom'] ?? '';
$prenom = $_POST['prenom'] ?? '';
$age = $_POST['age'] ?? 0;
$genre = $_POST['genre'] ?? '';
$telephone = $_POST['tel'] ?? null;
$email = $_POST['email'] ?? '';
$password = password_hash($_POST['password'] ?? '', PASSWORD_DEFAULT);
$adresse = ($_POST['num-rue'] ?? '') . ' ' . ($_POST['rue'] ?? '') . ', ' . ($_POST['code-postal'] ?? '') . ' ' . ($_POST['ville'] ?? '');
$mode_paiement = $_POST['mode_paiement'] ?? ''; // Mode de paiement
$tarif = $_POST['tarif'] ?? '';
if (isset($_POST['tarif']) && $_POST['tarif'] == "Personne en situation de handicap") {
    $handicap = 1;
} else {
    $handicap = 0;
}
$nb_place = $_POST['place'] ?? 1;  // Nombre de place par défaut 1
        //$camping = $_POST['camping'] ?? 'non';

        // Si l'âge est inférieur à 18 et un accompagnateur est spécifié, on met le nombre de places à 2
        if ($age < 18 && !empty($_POST['accompagnateur_nom']) && !empty($_POST['accompagnateur_prenom'])) {
            $nb_place = 2;  // Définir le nombre de places à 2 si l'accompagnateur est présent
        }

        // Insertion des données dans la table Client
        $sql = "INSERT INTO Client (NomC, PrenomC, AgeC, Genre, Telephone, Mail, Adresse, Pass, Mode_Payement, Handicap, password, Tarif) 
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $bdd->prepare($sql);
        $stmt->execute([
            $nom, 
            $prenom, 
            $age, 
            $genre, 
            $telephone, 
            $email, 
            $adresse, 
            $pass,
            $mode_paiement, 
            $handicap, 
            $password,
            $tarif 
        ]);

        // Récupération de l'ID client
        $id_client = $bdd->lastInsertId();

        // Insertion de la réservation avec le nombre de places
        $sql_reservation = "INSERT INTO Reservation (Date_Resa, Nb_Place, Statut_Payement, Id_Client) 
                            VALUES (NOW(), ?, 0, ?)";

        $stmt_reservation = $bdd->prepare($sql_reservation);
        $stmt_reservation->execute([$nb_place, $id_client]);

        // Si l'âge est inférieur à 18, insérer l'accompagnateur
        if ($age < 18 && !empty($_POST['accompagnateur_nom']) && !empty($_POST['accompagnateur_prenom'])) {
            $acc_nom = $_POST['accompagnateur_nom'];
            $acc_prenom = $_POST['accompagnateur_prenom'];
            $acc_email = $_POST['accompagnateur_email'] ?? '';

            // Insérer l'accompagnateur comme un nouveau client
            $sql_acc = "INSERT INTO Client (NomC, PrenomC, Mail) 
                        VALUES (?, ?, ?)";

            $stmt_acc = $bdd->prepare($sql_acc);
            $stmt_acc->execute([$acc_nom, $acc_prenom, $acc_email]);

            // Récupérer l'ID de l'accompagnateur nouvellement inséré
            $id_accompagnateur = $bdd->lastInsertId();

            // Mise à jour de l'accompagnateur dans la table Client pour le client mineur
            $sql_update = "UPDATE Client 
                           SET Id_Accompagnateur = ? 
                           WHERE Id_Client = ?";

            $stmt_update = $bdd->prepare($sql_update);
            $stmt_update->execute([$id_accompagnateur, $id_client]);
        }
        unset($_SESSION['pass_selected']);

        // Redirection vers une page de confirmation
        header("Location:inscription.php");
        exit();
    } catch (PDOException $e) {
        echo "Erreur lors de l'inscription : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" href="inscription.css">
</head>
<body>
    <img id="Logo" src="Logo festival.png" width="100" height="100">
   
   <!--Affichage des emplacements disponibles 
     <div id="available-places">Emplacements disponibles: 100</div> -->
   
    <a id="back-button" href="choix_pass.html">Retour</a>

    <h1>Formulaire d'inscription au festival Sam'et à l'eze</h1>
    <form action="inscription.php" method="POST">

        <!-- Nombre de places -->
        <div>
            Nombre de places: <input type="number" id="place" name="place" placeholder="Entrez le nombre de places" required>
        </div>
        <br>

        <!-- Nom -->
        <div>
            Nom : <input type="text" id="nom" name="nom" placeholder="Entrez votre nom" required>
        </div>
        <br>

        <!-- Prénom -->
        <div>
            Prénom : <input type="text" id="prenom" name="prenom" placeholder="Entrez votre prénom" required>
        </div>
        <br>

        <!-- Age -->
        <div> 
            Age: <input type="number" id="age" name="age" placeholder="Entrez votre âge" required>
        </div>

        <!-- Formulaire de l'accompagnateur -->
        <details>
            <summary>Accompagnateur d'une personne mineure</summary>
            <br>
            <div>
                <label>Nom : <input type="text" name="accompagnateur_nom" placeholder="Nom"></label><br><br>
                <label>Prénom : <input type="text" name="accompagnateur_prenom" placeholder="Prénom"></label><br><br>
                <label>Email : <input type="email" name="accompagnateur_email" placeholder="Adresse e-mail"></label><br><br>
            </div>
        </details>
        <br>
        <br>
        <!-- Genre -->
        <div>
            Genre : 
            <select id="genre" name="genre" required>
                <option value="homme">Homme</option>
                <option value="femme">Femme</option>
                <option value="non-genré">Non-genré</option>
                <option value="ne-pas-se-prononcer">Ne pas se prononcer</option>
            </select>
        </div>
        <br>

        <!-- Numéro de téléphone -->
        <div>
            Numéro de téléphone : <input type="tel" id="tel" name="tel" placeholder="Entrez votre numéro de tel" required>
        </div>
        <br>

        <!-- Adresse-mail -->
        <div>
            Adresse-mail : <input type="email" id="email" name="email" placeholder="Entrez votre e-mail" required>
        </div>
        <br>

        <!-- Mot de passe -->
        <div>
            Mot de passe : <input type="password" id="password" name="password" placeholder="Entrez votre mot de passe" required>
        </div>
        <br>
       
        <!-- Adresse postale -->
        <div>
            Adresse postale : <br><br>

            <div>
                Numéro de rue : <input type="text" id="num-rue" name="num-rue" placeholder="Entrez le numéro de rue" required>
            </div>
            <br>

            Rue : <input type="text" id="rue" name="rue" placeholder="Entrez votre rue" required>
        </div>
        <br>
        <div>
            Code postal : <input type="text" id="code-postal" name="code-postal" placeholder="Entrez votre code postal" required>
        </div>
        <br>
        <div>
            Ville : <input type="text" id="ville" name="ville" placeholder="Entrez votre ville" required>
        </div>
        <br>


        <!-- Emplacement de camping -->
       <!-- <div>
            Voulez-vous avoir un emplacement de camping ? <br>
            <input type="radio" id="camping-oui" name="camping" value="oui" required>
            <label for="camping-oui">Oui</label>
            <input type="radio" id="camping-non" name="camping" value="non">
            <label for="camping-non">Non</label>
        </div>
        <br> -->


        <!-- Choix du tarif -->
        <div>
            Quel tarif choisissez-vous ?<br>
            <input type="radio" id="tarif-groupe" name="tarif" value="Groupe (5 personnes ou plus)" required>
            <label for="tarif-groupe">Groupe (5 personnes ou plus)</label>
            <input type="radio" id="tarif-jeunes" name="tarif" value="Moins de 18 ans">
            <label for="tarif-jeunes">Moins de 18 ans</label>
            <input type="radio" id="tarif-adulte" name="tarif" value="Adulte">
            <label for="tarif-adulte">Adulte</label>
            <input type="radio" id="tarif-handicap" name="tarif" value="Personne en situation de handicap">
            <label for="tarif-handicap">Personne en situation de handicap</label>
            <input type="radio" id="tarif-enfant" name="tarif" value="Moins de 10 ans" required>
            <label for="tarif-enfant">Moins de 10 ans</label>
        </div>
        <br><br>

         <!-- Mode de paiement -->
         <div>
            Mode de paiement : <br>
            <input type="radio" id="mode_paiement_en_ligne" name="mode_paiement" value="En ligne" required>
            <label for="mode_paiement_en_ligne">En ligne</label>
            <input type="radio" id="mode_paiement_sur_place" name="mode_paiement" value="Sur place">
            <label for="mode_paiement_sur_place">Sur place</label>
        </div>
        <br>

        <!-- Bouton reinitialiser -->
        <div>
            <input type="reset" value="Réinitialiser"><br><br>
        </div>

         <!-- Bouton envoyer -->
         <div>
            <input type="submit" value="Envoyer le formulaire d'inscription"><br><br>
        </div>

    </form>
</body>
</html>


