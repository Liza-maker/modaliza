<?php
include 'bd.php';  // Inclure la base de données

// Connexion à la base de données
$bdd = getBD();
if (!$bdd) {
    die('Erreur de connexion à la base de données');
}

// Exécution de la requête pour récupérer les clients
$stmt = $bdd->query('SELECT id_client, prenom, nom, ID_STRIPE FROM clients');
if (!$stmt) {
    die('Erreur dans la requête SQL');
}

// Affichage des résultats
while ($client = $stmt->fetch()) {
    echo "ID Client: " . htmlspecialchars($client['id_client']) . "<br>";
    echo "Nom: " . htmlspecialchars($client['prenom']) . " " . htmlspecialchars($client['nom']) . "<br>";
    echo "ID_STRIPE: " . htmlspecialchars($client['ID_STRIPE']) . "<br><br>";
}
?>
