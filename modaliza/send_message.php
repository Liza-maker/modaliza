<?php
session_start();
include 'bd.php';  // Inclure la connexion à la base de données
$bdd = getBD();

// Fonction pour enregistrer les messages dans la base de données
function enregistrer_message($user, $message) {
    global $bdd;
    $requete = $bdd->prepare('INSERT INTO messages (user, message) VALUES (?, ?)');
    $requete->execute([$user, $message]);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupère les données envoyées par le formulaire
    $message = $_POST['message'];
    $user = $_SESSION['client']['prenom']; // Par exemple, utiliser le prénom de l'utilisateur connecté

    // Envoi du message à l'API Flask pour vérifier s'il est haineux
    $url = 'http://127.0.0.1:5000/predict';  // L'URL de ton serveur Flask
    $data = json_encode(['message' => $message]);

    // Initialiser cURL pour envoyer la requête
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    $response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response, true);
    
    // Si le message n'est pas haineux, l'enregistrer dans la base de données
    if ($result['is_hate'] == 0) {
        enregistrer_message($user, $message);
        echo "Message envoyé et enregistré avec succès!";
    } else {
        echo "Message haineux détecté, message non envoyé.";
    }
}
?>

<script>
// Charger l'historique des messages au démarrage
window.onload = function() {
    fetch('http://localhost:5000/get-messages')  // Appeler l'API Flask pour récupérer les messages
        .then(response => response.json())
        .then(data => {
            const messages = data.messages;
            const chatBox = document.getElementById("chat-box");

            // Ajouter chaque message dans la boîte de chat
            messages.forEach(message => {
                chatBox.innerHTML += `<div><strong>Utilisateur:</strong> ${message}</div>`;
            });
        })
        .catch(error => {
            console.error('Erreur lors de la récupération des messages:', error);
        });
};
</script>
