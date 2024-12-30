<?php
include 'bd.php';

header('Content-Type: application/json'); // La réponse sera en JSON

if (isset($_POST['email'])) {
    $email = $_POST['email'];

    try {
        $bdd = getBD();
        $stmt = $bdd->prepare('SELECT COUNT(*) FROM clients WHERE mail = :mail');
        $stmt->execute([':mail' => $email]);

        $exists = $stmt->fetchColumn() > 0;

        echo json_encode(['exists' => $exists]);
    } catch (Exception $e) {
        echo json_encode(['exists' => false, 'error' => 'Erreur lors de la vérification de l\'email.']);
    }
}
?>
