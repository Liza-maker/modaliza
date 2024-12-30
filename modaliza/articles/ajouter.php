<?php
session_start(); // Démarrer la session

if (!isset($_SESSION['client'])) {
    // Si l'utilisateur n'est pas connecté, rediriger vers la page de connexion
    header('Location: ../connexion.php');
    exit;
}

// Vérifier si l'article et la quantité sont passés via POST
if (isset($_POST['id_art']) && isset($_POST['quantite'])) {
    $id_art = $_POST['id_art']; // Récupérer l'identifiant de l'article
    $quantite = (int) $_POST['quantite']; // Récupérer la quantité (en entier)

    // Connexion à la base de données
    include '../bd.php';
    $bdd = getBD();

    // Vérifier la quantité disponible dans la base de données
    $requete = $bdd->prepare('SELECT quantite FROM Articles WHERE id_art = ?');
    $requete->execute([$id_art]);
    $article = $requete->fetch();

    if (!$article) {
        echo '<p>Article non trouvé. <a href="../modaliza.php">Retour au catalogue</a></p>';
        exit;
    }

    $quantiteDisponible = (int)$article['quantite'];

    // Vérifier que la quantité demandée ne dépasse pas la quantité disponible
    if ($quantite > 0 && $quantite <= $quantiteDisponible) {
        // Si le panier n'existe pas encore, on le crée
        if (!isset($_SESSION['panier'])) {
            $_SESSION['panier'] = []; // Initialisation du panier
        }

        // Vérifier si l'article est déjà dans le panier
        $articleExiste = false;
        foreach ($_SESSION['panier'] as &$article) {
            if ($article['id_art'] == $id_art) {
                // Vérifier si la quantité totale ne dépasse pas la quantité disponible
                if ($article['quantite'] + $quantite > $quantiteDisponible) {
                    echo '<p class ="retour">Impossible d\'ajouter cette quantité. Quantité maximum dans le panier : ' . $quantiteDisponible . '. <a href="article.php?id_art=' . urlencode($id_art) . '">Retour</a></p>';
                    exit;
                }

                // Si l'article existe déjà, on ajoute la quantité
                $article['quantite'] += $quantite;
                $articleExiste = true;
                break;
            }
        }

        // Si l'article n'existe pas encore, on l'ajoute au panier
        if (!$articleExiste) {
            $_SESSION['panier'][] = [
                'id_art' => $id_art,
                'quantite' => $quantite
            ];
        }

        // Redirection vers la page principale après ajout
        header('Location: ../modaliza.php');
        exit;

    } else {
        // Si la quantité demandée n'est pas valide, afficher un message d'erreur
        echo '<p class ="retour">Quantité non valide. Quantité disponible : ' . $quantiteDisponible . '. <a href="article.php?id_art=' . urlencode($id_art) . '">Retour</a></p>';
    }
} else {
    // Si l'article ou la quantité n'ont pas été envoyés
    echo '<p>Erreur : article ou quantité non spécifiés. <a href="../modaliza.php">Retour au catalogue</a></p>';
}
?>
