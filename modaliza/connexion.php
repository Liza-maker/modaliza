<?php
session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styles/nouveau.css" type="text/css" media="screen" />
    <title>Connexion</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Inclure jQuery -->
</head>
<body>

<h1>Connexion</h1>
<p>Pas encore de compte ? <a href="nouveau.php">Créer un compte</a></p>

<form id="connexion-form">
    <p>
        Adresse e-mail :
        <input type="email" name="mail" required>
    </p>
    <p>
        Mot de passe :
        <input type="password" name="mdp" required>
    </p>
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
    <p>
        <input type="submit" value="Se connecter">
    </p>
</form>

<div id="message"></div>

<script>
$(document).ready(function() {
    $('#connexion-form').on('submit', function(e) {
        e.preventDefault();

        const formData = $(this).serialize(); // Sérialise les données du formulaire

        $.ajax({
            url: 'connecteur.php',
            type: 'POST',
            dataType: 'json',
            data: formData,
            success: function(response) {
                if (response.success) {
                    window.location.href = 'modaliza.php';
                } else {
                    $('#message').html(`<p class="error">${response.message}</p>`);
                }
            },
            error: function() {
                $('#message').html('<p class="error">Erreur lors de la connexion au serveur.</p>');
            }
        });
    });
});
</script>

</body>
</html>
