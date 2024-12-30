<?php
session_start(); // Démarrer la session pour vérifier si l'utilisateur est connecté
include '../bd.php';

// Vérification de l'existence de l'article via l'ID dans l'URL
if (!isset($_GET['id_art'])) { 
    header('Location: ../modaliza.php'); // Redirection si aucun article n'est trouvé
    exit; 
}

$bdd = getBD();
$requete = $bdd->prepare('SELECT nom, description, url_photo, quantite, prix FROM Articles WHERE id_art = ?');
$requete->execute([intval($_GET['id_art'])]);
$article = $requete->fetch();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" href="../styles/article.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <title><?php echo htmlspecialchars($article['nom']); ?></title>
</head>
<body>
    <h1><?php echo htmlspecialchars($article['nom']); ?></h1>
    <img src="../images/<?php echo htmlspecialchars($article['url_photo']); ?>" alt="<?php echo htmlspecialchars($article['nom']); ?>" />
    
    <div class="description">
        <p><strong>Description:</strong> <?php echo htmlspecialchars($article['description']); ?></p>
        <p><strong>Prix:</strong> <?php echo htmlspecialchars($article['prix']); ?> €</p>
        <p><strong>Quantité disponible:</strong> <?php echo htmlspecialchars($article['quantite']); ?></p>

        <a href="../modaliza.php">Retour au site principal</a> 
    </div>
 <!-- condition php if -->
    <?php if (isset($_SESSION['client'])): ?>
        <!-- Formulaire pour ajouter l'article au panier si l'utilisateur est connecté -->
        <form method="post" action="ajouter.php">
            <!-- Champ caché pour envoyer l'ID de l'article -->
            <input type="hidden" name="id_art" value="<?php echo htmlspecialchars($_GET['id_art']); ?>">
            
            <!-- Sélection de la quantité -->
            <label for="quantite">Quantité :</label>
            <input type="number" id="quantite" name="quantite" value="1" min="1" max="<?php echo htmlspecialchars($article['quantite']); ?>" required>

            <!-- Bouton pour ajouter au panier -->
            <!-- Bouton pour ajouter au panier avec une icône de panier -->
             <input type="submit" value="&#xf07a; Ajouter à votre panier" class="fa">

        </form>
      <!-- ferme la condition php endif -->   
    <?php endif; ?>
</body>
</html>
