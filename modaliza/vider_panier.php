<?php
session_start(); // Démarrer la session

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['client'])) {
    header('Location: connexion.php');
    exit;
}

// Vider le panier en supprimant la variable de session 'panier'
unset($_SESSION['panier']);

// Rediriger l'utilisateur vers le panier ou la page d'accueil avec un message de succès
header('Location: panier.php?message=Votre panier a été vidé avec succès.');
exit;
