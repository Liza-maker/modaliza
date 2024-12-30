<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styles/nouveau.css" type="text/css" media="screen" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>Nouveau</title>
    <style>
        .success { color: green; }
        .error { color: red; }
        .valid { border: 2px solid green; }
        .invalid { border: 2px solid red; }
    </style>
</head>
<body>

<h1>Nouveau Client</h1>
<p>Création de Compte - Nouveau Client</p>

<div id="message"></div>

<form id="registrationForm">
    <p>
        Nom : <input type="text" name="n" id="nom" />
        <span class="error" id="error-nom"></span>
    </p>
    <p>
        Prénom : <input type="text" name="p" id="prenom" />
        <span class="error" id="error-prenom"></span>
    </p>
    <p>
        Adresse : <input type="text" name="adr" id="adresse" />
        <span class="error" id="error-adresse"></span>
    </p>
    <p>
        Numéro de téléphone : <input type="text" name="num" id="telephone" />
        <span class="error" id="error-telephone"></span>
    </p>
    <p>
        Adresse e-mail : <input type="email" name="mail" id="email" />
        <span class="error" id="error-email"></span>
    </p>
    <p>
        Mot de passe : <input type="password" name="mdp1" id="password" />
        <span class="error" id="error-password"></span>
    </p>
    <p>
        Confirmer le mot de passe : <input type="password" name="mdp2" id="confirmPassword" />
        <span class="error" id="error-confirmPassword"></span>
    </p>
    <p>
        <input type="button" value="Créer un compte" id="submitBtn" disabled>
    </p>
</form>

<script>
$(document).ready(function() {
    // Fonction de validation des champs
    function validateField(selector, condition, errorMessage) {
        const field = $(selector);
        const error = $(`#error-${field.attr('id')}`);

        if (condition) {
            field.removeClass('invalid').addClass('valid');
            error.text('');
            return true;
        } else {
            field.removeClass('valid').addClass('invalid');
            error.text(errorMessage);
            return false;
        }
    }

    // Validation des champs au changement
    $('input').on('blur', function() {
        validateForm();
    });

    function validateForm() {
        const isNomValid = validateField('#nom', $('#nom').val().trim() !== '', 'Le nom est obligatoire.');
        const isPrenomValid = validateField('#prenom', $('#prenom').val().trim() !== '', 'Le prénom est obligatoire.');
        const isAdresseValid = validateField('#adresse', $('#adresse').val().trim() !== '', 'L\'adresse est obligatoire.');
        const isTelephoneValid = validateField('#telephone', /^\d{10}$/.test($('#telephone').val().trim()), 'Le téléphone doit contenir 10 chiffres.');
        const isEmailValid = validateField('#email', /^[^@\s]+@[^@\s]+\.[^@\s]+$/.test($('#email').val().trim()), 'Adresse email invalide.');
        const isPasswordValid = validateField('#password', /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{6,}$/.test($('#password').val().trim()), 'Le mot de passe doit contenir au moins 1 lettre, 1 chiffre et 1 caractère spécial.');
        const isConfirmPasswordValid = validateField('#confirmPassword', $('#password').val() === $('#confirmPassword').val(), 'Les mots de passe ne correspondent pas.');

        $('#submitBtn').prop('disabled', !(isNomValid && isPrenomValid && isAdresseValid && isTelephoneValid && isEmailValid && isPasswordValid && isConfirmPasswordValid));
    }

    // Vérification AJAX pour l'email
    $('#email').on('blur', function() {
        const email = $('#email').val().trim();
        if (email) {
            $.ajax({
                url: 'check_email.php',
                type: 'POST',
                data: { email: email },
                success: function(response) {
                    if (response.exists) {
                        $('#error-email').text('Cette adresse email est déjà utilisée.');
                        $('#email').removeClass('valid').addClass('invalid');
                        $('#submitBtn').prop('disabled', true);  // Désactiver le bouton si l'email existe
                    } else {
                        $('#error-email').text('');
                        $('#email').removeClass('invalid').addClass('valid');
                        validateForm(); // Revalider l'email après la vérification
                    }
                },
                error: function() {
                    $('#error-email').text('Erreur lors de la vérification de l\'email.');
                }
            });
        }
    });

    // Envoi du formulaire avec AJAX
    $('#submitBtn').on('click', function() {
        const formData = {
            n: $('#nom').val().trim(),
            p: $('#prenom').val().trim(),
            adr: $('#adresse').val().trim(),
            num: $('#telephone').val().trim(),
            mail: $('#email').val().trim(),
            mdp1: $('#password').val().trim(),
            mdp2: $('#confirmPassword').val().trim()
        };

        $.ajax({
            url: 'enregistrement.php',
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    $('#message').html(`<p class="success">${response.message}</p>`);
                    setTimeout(() => { window.location.href = 'modaliza.php'; }, 1000);  // Redirection après succès
                } else {
                    $('#message').html(`<p class="error">${response.message}</p>`);
                }
            },
            error: function() {
                $('#message').html('<p class="error">Erreur lors de la création du compte.</p>');
            }
        });
    });
});
</script>

</body>
</html>
