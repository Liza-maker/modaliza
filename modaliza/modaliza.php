<?php
session_start();  
include 'bd.php';

$bdd = getBD();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" href="modaliza.css" type="text/css" media="screen" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>MODALIZA</title>
</head>
<body>

<h1>MODALIZA</h1>
<p>Bienvenue chez <strong>MODALIZA</strong> ! Découvrez les dernières tendances de la mode.</p>

<div class="header-buttons">
<?php
// Vérification si l'utilisateur est connecté
if (isset($_SESSION['client'])) {
    // Si l'utilisateur est connecté, afficher un message de bienvenue
    echo '<p>Bonjour ' . htmlspecialchars($_SESSION['client']['prenom']) . ' ' . htmlspecialchars($_SESSION['client']['nom']) . ' !</p>';
    echo '<p class="deconnexion"><a href="deconnexion.php">Se déconnecter</a></p>';
    echo '<p class="panier"><a href="panier.php">Voir mon panier</a></p>'; // Lien vers le panier
} else {
    // Si l'utilisateur n'est pas connecté, afficher les liens pour s'inscrire ou se connecter
    echo '<p class="nouveau"><a href="nouveau.php">Nouveau Client</a></p>';
    echo '<p class="connexion"><a href="connexion.php">Se connecter</a></p>';
}
?>
</div>


<?php
$requete = $bdd->query('SELECT id_art, nom, quantite, prix FROM Articles');
?>
<table>
    <tr>
        <th>Numéro identifiant</th>
        <th>Nom</th>
        <th>Quantité en stock</th>
        <th>Prix</th>
    </tr>

    <?php
    while ($ligne = $requete->fetch()) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($ligne['id_art']) . '</td>';
        echo '<td><a href="articles/article.php?id_art=' . urlencode($ligne['id_art']) . '">' . htmlspecialchars($ligne['nom']) . '</a></td>';
        echo '<td>' . htmlspecialchars($ligne['quantite']) . '</td>';
        echo '<td>' . htmlspecialchars($ligne['prix']) . ' €</td>';
        echo '</tr>';
    }
    ?>
</table>

<?php if (isset($_SESSION['client'])): ?>
        <!-- Bouton pour accéder à l'historique des commandes -->
        <div class="left-actions">
            <a href="historique.php">Historique des commandes</a>
        </div>
<?php endif; ?>

<p class="contact">
      <a href="contact/contact.html">Contact</a>
</p>

<?php if (isset($_SESSION['client'])): ?>
   <div id="chat-container">
      <div id="chat-box"></div>
      <textarea id="message-input" placeholder="Tapez un message..."></textarea>
      <button id="send-button" type="button">Envoyer</button>
   </div>
<?php endif; ?>

<script>
    const sendButton = document.getElementById("send-button");
    const messageInput = document.getElementById("message-input");
    const chatBox = document.getElementById("chat-box");

    // Vérifie si le bouton est dans un formulaire et empêche le comportement par défaut
    if (sendButton.closest("form")) {
        sendButton.closest("form").addEventListener("submit", function(event) {
            event.preventDefault(); // Empêche le rechargement de la page
        });
    }

    sendButton.addEventListener("click", function() {
        const message = messageInput.value.trim();
        if (!message) {
            alert('Veuillez taper un message valide.');
            return;
        }

        // Envoi du message via AJAX à l'API Flask
        fetch('http://localhost:5000/send-message', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ message: message })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.is_hate === 0) {
                loadMessages(); // Recharger les messages après un envoi réussi
                messageInput.value = '';  // Réinitialiser le champ de message
            } else {
                alert('Message haineux détecté et non envoyé.');
            }
        })
        .catch(error => {
            alert('Une erreur est survenue lors de l\'envoi du message. Veuillez réessayer plus tard.');
            console.error('Erreur:', error);
        });
    });

    async function loadMessages() {
        try {
            // Appel à l'API Flask pour récupérer les messages
            const response = await fetch('http://127.0.0.1:5000/get-messages');
            const data = await response.json();

            // Réinitialiser le contenu du chat
            chatBox.innerHTML = '';

            // Afficher chaque message
            data.messages.forEach(msg => {
                const messageElement = document.createElement('div');
                messageElement.classList.add('chat-message');
                messageElement.innerHTML = `<span class="timestamp">${msg.date_enregistrement}</span>: ${msg.message}`;
                chatBox.appendChild(messageElement);
            });

            // Défilement automatique vers le bas
            chatBox.scrollTop = chatBox.scrollHeight;
        } catch (error) {
            console.error('Erreur lors du chargement des messages:', error);
        }
    }

    // Charger les messages au démarrage et toutes les 5 secondes
    loadMessages();
    setInterval(loadMessages, 5000);
</script>

</body>
</html>