<?php
session_start(); // Démarrer la session

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['client'])) {
    header('Location: connexion.php');
    exit;
}

// Connexion à la base de données
include 'bd.php';
$bdd = getBD(); // Connexion à la base de données

// Vérifier si le panier contient des articles
if (!isset($_SESSION['panier']) || empty($_SESSION['panier'])) {
    echo '<p>Votre panier est vide. <a href="modaliza.php">Retour à l\'accueil</a></p>';
    exit;
}

// Récupérer l'id du client depuis la session
$idClient = $_SESSION['client']['id'];

// Démarrer une transaction
try {
    $bdd->beginTransaction();

    // Enregistrer chaque article du panier dans la table Commandes
    foreach ($_SESSION['panier'] as $articlePanier) {
        $idArt = $articlePanier['id_art'];
        $quantite = $articlePanier['quantite'];

        // Récupérer la quantité actuelle en stock
        $requeteStock = $bdd->prepare('SELECT quantite FROM Articles WHERE id_art = ?');
        $requeteStock->execute([$idArt]);
        $stockActuel = intval($requeteStock->fetchColumn());

        // Vérifier que la quantité commandée ne dépasse pas la quantité disponible
        if ($quantite > $stockActuel) {
            // Annuler la transaction et afficher une erreur
            $bdd->rollBack();
            echo "Erreur : Vous avez commandé une quantité supérieure à celle disponible pour l'article ID $idArt.";
            exit;
        }

        // Insérer dans la table Commandes
        $requeteCommande = $bdd->prepare('INSERT INTO commandes (id_art, id_client, quantite, envoi) VALUES (?, ?, ?, FALSE)');
        $requeteCommande->execute([$idArt, $idClient, $quantite]);

        // Mettre à jour la quantité des articles dans la table Articles
        $nouveauStock = $stockActuel - $quantite;

        // Vérifier que le nouveau stock ne devient pas négatif (normalement cela ne devrait pas arriver avec la vérification ci-dessus)
        if ($nouveauStock < 0) {
            // Annuler la transaction et afficher une erreur
            $bdd->rollBack();
            echo "Erreur critique : La quantité en stock ne peut pas être négative pour l'article ID $idArt.";
            exit;
        }

        $requeteUpdateStock = $bdd->prepare('UPDATE Articles SET quantite = ? WHERE id_art = ?');
        $requeteUpdateStock->execute([$nouveauStock, $idArt]);
    }

    // Si tout va bien, valider la transaction
    $bdd->commit();

} catch (Exception $e) {
    // En cas d'erreur, annuler la transaction
    $bdd->rollBack();
    echo "Une erreur est survenue : " . $e->getMessage();
    exit;
}

// Vider le panier en supprimant la variable de session 'panier'
unset($_SESSION['panier']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Commande enregistrée</title>
    <link rel="stylesheet" href="styles/commande.css" type="text/css">
</head>
<body>
    <h1>Votre commande a bien été enregistrée.</h1>
    <p class="retour"><a href="modaliza.php">Retour à l'accueil</a></p>
</body>
</html>
