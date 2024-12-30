<?php
session_start(); // Démarrer la session

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['client'])) {
    header('Location: connexion.php'); // Rediriger vers la page de connexion si non connecté
    exit;
}

// Connexion à la base de données
include 'bd.php';
$bdd = getBD(); // Connexion à la base de données

// Récupérer l'id du client depuis la session
$idClient = $_SESSION['client']['id'];

// Requête pour récupérer l'historique des commandes
$requete = $bdd->prepare('
    SELECT c.id_commande, c.id_art, a.nom, a.prix, c.quantite, c.envoi
    FROM commandes c
    JOIN Articles a ON c.id_art = a.id_art
    WHERE c.id_client = ?
');
$requete->execute([$idClient]);
$commandes = $requete->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Historique des Commandes</title>
    <link rel="stylesheet" href="styles/commande.css" type="text/css">
</head>
<body>
    <h1>Historique de vos commandes</h1>

    <?php if (empty($commandes)): ?>
        <p>Vous n'avez aucune commande.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID Commande</th>
                    <th>ID Article</th>
                    <th>Nom</th>
                    <th>Prix</th>
                    <th>Quantité</th>
                    <th>État de la commande</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($commandes as $commande): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($commande['id_commande']); ?></td>
                        <td><?php echo htmlspecialchars($commande['id_art']); ?></td>
                        <td><?php echo htmlspecialchars($commande['nom']); ?></td>
                        <td><?php echo number_format($commande['prix'], 2); ?> €</td>
                        <td><?php echo htmlspecialchars($commande['quantite']); ?></td>
                        <td><?php echo $commande['envoi'] ? 'Envoyée' : 'Non envoyée'; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <p class = "retour"><a href="modaliza.php">Retour à l'accueil</a></p>
</body>
</html>
