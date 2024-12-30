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

// Récupérer l'id du client depuis la session
$idClient = $_SESSION['client']['id'];

// Récupérer les informations du client
$requeteClient = $bdd->prepare('SELECT nom, prenom, adresse, ID_STRIPE FROM clients WHERE id_client = ?');
$requeteClient->execute([$idClient]);
$client = $requeteClient->fetch();

if (!$client) {
    echo '<p>Erreur : impossible de récupérer les informations du client.</p>';
    exit;
}

// Vérification de l'ID Stripe du client, création si nécessaire
if (empty($client['ID_STRIPE'])) {
    require_once('stripe.php');
    try {
        // Créer un client Stripe si l'ID Stripe n'existe pas
        $customer = $stripe->customers->create([
            'name' => $client['prenom'] . ' ' . $client['nom'],
            'email' => $_SESSION['client']['mail'],
        ]);
        // Mettre à jour l'ID Stripe dans la base de données
        $bdd->prepare('UPDATE clients SET ID_STRIPE = ? WHERE id_client = ?')->execute([$customer->id, $idClient]);
        $client['ID_STRIPE'] = $customer->id;
    } catch (\Exception $e) {
        echo '<p>Erreur lors de la création du client Stripe : ' . $e->getMessage() . '</p>';
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once('stripe.php');

    $line_items = [];
    foreach ($_SESSION['panier'] as $articlePanier) {
        $idArt = $articlePanier['id_art'];
        $quantite = $articlePanier['quantite'];

        // Récupérer l'ID Stripe du produit à partir de la base de données
        $requeteArticle = $bdd->prepare('SELECT ID_STRIPE, nom, prix FROM articles WHERE id_art = ?');
        $requeteArticle->execute([$idArt]);
        $article = $requeteArticle->fetch();

        if ($article) {
            // Ajouter l'ID Stripe du produit et la quantité
            $line_items[] = [
                'price_data' => [
                    'currency' => 'eur', 
                    'product' => $article['ID_STRIPE'], // L'ID du produit
                    'unit_amount' => $article['prix'] * 100, // Prix en centimes
                ],
                'quantity' => $quantite,
            ];
        }
    }

    // Vérifier si des articles ont été ajoutés
    if (empty($line_items)) {
        echo 'Erreur : Aucun article dans le panier.';
        exit;
    }

    try {
        // Créer une session de paiement Stripe
        $checkout_session = $stripe->checkout->sessions->create([
            'customer' => $client['ID_STRIPE'],
            'success_url' => 'http://localhost/SAMUILAVA/acheter.php?success=true',
            'cancel_url' => 'http://localhost/SAMUILAVA/panier.php?success=false',
            'mode' => 'payment',
            'line_items' => $line_items,
        ]);

        // Rediriger l'utilisateur vers la session de paiement
        header("Location: " . $checkout_session->url);
        exit;
    } catch (\Exception $e) {
        echo 'Erreur lors de la création de la session de paiement : ' . $e->getMessage();
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Commande</title>
    <link rel="stylesheet" href="styles/commande.css" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <h1>Récapitulatif de votre commande</h1>

    <table>
        <tr>
            <th>Identifiant Article</th>
            <th>Nom</th>
            <th>Quantité</th>
            <th>Prix total</th>
        </tr>

        <?php
        $totalCommande = 0;

        // Parcourir les articles du panier
        foreach ($_SESSION['panier'] as $articlePanier) {
            $idArt = $articlePanier['id_art'];
            $quantite = $articlePanier['quantite'];

            // Récupérer les informations de l'article depuis la base de données
            $requeteArticle = $bdd->prepare('SELECT nom, prix FROM articles WHERE id_art = ?');
            $requeteArticle->execute([$idArt]);
            $article = $requeteArticle->fetch();

            if ($article) {
                $nomArticle = htmlspecialchars($article['nom']);
                $prixUnitaire = floatval($article['prix']);
                $prixTotal = $prixUnitaire * $quantite;
                $totalCommande += $prixTotal;

                echo '<tr>';
                echo '<td>' . htmlspecialchars($idArt) . '</td>';
                echo '<td>' . $nomArticle . '</td>';
                echo '<td>' . htmlspecialchars($quantite) . '</td>';
                echo '<td>' . number_format($prixTotal, 2) . ' €</td>';
                echo '</tr>';
            } else {
                echo '<tr><td colspan="4">Erreur : Impossible de récupérer les informations de l\'article pour ID ' . htmlspecialchars($idArt) . '</td></tr>';
            }
        }
        ?>

        <tr>
            <td colspan="3" style="text-align: right;"><strong>Total :</strong></td>
            <td><strong><?php echo number_format($totalCommande, 2); ?> €</strong></td>
        </tr>
    </table>

    <h2>La commande sera expédiée à l'adresse suivante :</h2>
    <p><?php echo htmlspecialchars($client['nom']) . ' ' . htmlspecialchars($client['prenom']); ?></p>
    <p><?php echo htmlspecialchars($client['adresse']); ?></p>

    <form method="post" action="commande.php" style="text-align: center;">
        <input type="submit" value="Valider" class="action-button valider-commande">
    </form>

    <p class="retour"><a href="modaliza.php">Retour à l'accueil</a></p>
</body>
</html>
