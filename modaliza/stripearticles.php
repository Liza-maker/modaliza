<?php
include 'bd.php';

function updateArticleStripeId($id_art, $id_stripe) {
    $bdd = getBD();

    $stmt = $bdd->prepare("UPDATE articles SET ID_STRIPE = ? WHERE id_art = ?");
    $stmt->execute([$id_stripe, $id_art]);

    if ($stmt->rowCount() > 0) {
        echo "Article $id_art mis à jour avec ID Stripe $id_stripe<br>";
    } else {
        echo "Échec de la mise à jour pour l'article $id_art<br>";
    }
}

// Appel de la fonction pour mettre à jour vos articles
updateArticleStripeId(1, 'prod_RJ6M5eXiY2ew08');
updateArticleStripeId(2, 'prod_RJ6QFArLr1ESJK');
updateArticleStripeId(3, 'prod_RJ6RRxhYgRvC34');
?>
