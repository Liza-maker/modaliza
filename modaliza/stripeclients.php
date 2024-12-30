<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('stripe.php');  // Inclure Stripe

// Définir la fonction createStripeCustomer ici
function createStripeCustomer($id_client, $prenom, $nom, $email) {
    global $stripe;

    try {
        $customer = $stripe->customers->create([
            'name' => "$prenom $nom",
            'email' => $email,
        ]);

        echo "Client Stripe créé avec succès : " . $customer->id . "<br>";

        $bdd = getBD();
        $stmt = $bdd->prepare('UPDATE clients SET ID_STRIPE = ? WHERE id_client = ?');
        $stmt->execute([$customer->id, $id_client]);

        if ($stmt->rowCount() > 0) {
            echo "Mise à jour réussie pour le client $id_client avec l'ID Stripe " . $customer->id . "<br>";
        } else {
            echo "Échec de la mise à jour pour le client $id_client<br>";
        }
    } catch (Exception $e) {
        echo "Erreur lors de la création du client Stripe pour $id_client : " . $e->getMessage() . "<br>";
    }
}

// Vérification de la connexion à la base de données
$bdd = getBD();
if (!$bdd) {
    die('Erreur de connexion à la base de données');
}

// Vérification de l’objet Stripe
if (!isset($stripe)) {
    die('Erreur : Stripe n’est pas initialisé');
}

// Exécution de la requête SQL
$stmt = $bdd->query('SELECT * FROM clients WHERE ID_STRIPE = "" OR ID_STRIPE IS NULL');
if (!$stmt) {
    die('Erreur dans la requête SQL');
}

if ($stmt->rowCount() === 0) {
    die('Aucun client sans ID Stripe trouvé');
}

// Traitement des clients
while ($client = $stmt->fetch()) {
    echo "Traitement du client : " . htmlspecialchars($client['prenom']) . " " . htmlspecialchars($client['nom']) . "<br>";
    createStripeCustomer($client['id_client'], $client['prenom'], $client['nom'], $client['mail']);
}

echo "Mise à jour des clients terminée.";
?>
