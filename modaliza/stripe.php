<?php
// Charger les dépendances via Composer
require_once('vendor/autoload.php');

// Initialiser l'objet Stripe avec la clé secrète
$stripe = new \Stripe\StripeClient('sk_test_51QQTpKBakWN1tdAqqAhdqxo9w52fgtzmuAGoPfQj4pJMzj9hBXdpasmturRAg7jF9psliR0SQQpKusNdct4ChQ6200RyVPH0gO');
?>
