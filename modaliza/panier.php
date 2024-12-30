<?php
session_start(); // Démarrer la session

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['client'])) {
    header('Location: connexion.php');
    exit;
}

include 'bd.php';
$bdd = getBD();

$totalCommande = 0; // Initialisation du total de la commande
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styles/panier.css" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Mon Panier</title>
</head>
<body>
    <h1>Mon Panier</h1>

    <table>
        <tr>
            <th>Identifiant Article</th>
            <th>Nom</th>
            <th>Prix unitaire</th>
            <th>Quantité</th>
            <th>Prix total</th>
        </tr>

        <?php

        // Vérifier si le panier existe
        if (!isset($_SESSION['panier']) || empty($_SESSION['panier'])) {
            echo '<p>Votre panier ne contient aucun article.</p>';
            echo '<p class ="retour"><a href="modaliza.php">Retour</a></p>';
            exit;
        }
        
        // Parcourir les articles du panier
        foreach ($_SESSION['panier'] as $articlePanier) {
            $idArt = $articlePanier['id_art'];
            $quantite = $articlePanier['quantite'];

            // Récupérer les informations de l'article depuis la base de données
            $requete = $bdd->prepare('SELECT nom, prix FROM Articles WHERE id_art = ?');
            $requete->execute([$idArt]);
            $article = $requete->fetch();

            if ($article) {
                $nom = htmlspecialchars($article['nom']);
                $prixUnitaire = floatval($article['prix']);
                $prixTotal = $prixUnitaire * $quantite;
                $totalCommande += $prixTotal; // Ajouter au total de la commande

                // Afficher chaque ligne du panier
                echo '<tr>';
                echo '<td>' . htmlspecialchars($idArt) . '</td>';
                echo '<td>' . $nom . '</td>';
                echo '<td>' . number_format($prixUnitaire, 2) . ' €</td>';
                echo '<td>' . htmlspecialchars($quantite) . '</td>';
                echo '<td>' . number_format($prixTotal, 2) . ' €</td>';
                echo '</tr>';
            }
        }
        ?>

        <tr>
            <td colspan="4" style="text-align: right;"><strong>Total :</strong></td>
            <td><strong><?php echo number_format($totalCommande, 2); ?> €</strong></td>
        </tr>
    </table>

    <!-- Bouton pour vider le panier -->
    <div style="text-align: center;">
        <a href="vider_panier.php" class="vider-panier">
            <i class="fas fa-trash-alt"></i> Vider le panier
        </a>
    </div>

    <!-- Lien pour passer la commande -->
    <p style="text-align:center;">
        <a href="commande.php" class="passer-commande">
            <i class="fas fa-check"></i> Passer la commande
        </a>
    </p>

    <p class ="retour"><a href="modaliza.php">Retour</a></p>
</body>
</html>