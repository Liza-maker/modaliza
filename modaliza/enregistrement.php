<?php
session_start();
include 'bd.php';
header('Content-Type: application/json');

if (isset($_POST['n'], $_POST['p'], $_POST['adr'], $_POST['num'], $_POST['mail'], $_POST['mdp1'], $_POST['mdp2'])) {
    $nom = trim($_POST['n']);
    $prenom = trim($_POST['p']);
    $adresse = trim($_POST['adr']);
    $numero = trim($_POST['num']);
    $email = trim($_POST['mail']);
    $mdp1 = $_POST['mdp1'];
    $mdp2 = $_POST['mdp2'];

    if ($mdp1 !== $mdp2) {
        echo json_encode(['success' => false, 'message' => 'Les mots de passe ne correspondent pas.']);
        exit;
    }

    try {
        $bdd = getBD();
        $stmt = $bdd->prepare('SELECT COUNT(*) FROM clients WHERE mail = :mail');
        $stmt->execute([':mail' => $email]);

        if ($stmt->fetchColumn() > 0) {
            echo json_encode(['success' => false, 'message' => 'Cette adresse e-mail est déjà utilisée.']);
            exit;
        }

        $hashPassword = password_hash($mdp1, PASSWORD_BCRYPT);

        $stmt = $bdd->prepare('INSERT INTO clients (nom, prenom, adresse, numero, mail, mdp) VALUES (:nom, :prenom, :adresse, :numero, :mail, :mdp)');
        $stmt->execute([
            ':nom' => $nom,
            ':prenom' => $prenom,
            ':adresse' => $adresse,
            ':numero' => $numero,
            ':mail' => $email,
            ':mdp' => $hashPassword
        ]);

        $id_client = $bdd->lastInsertId();

        if (!$id_client) {
            $response['success'] = false;
            $response['message'] = 'Erreur : ID client non généré.';
            echo json_encode($response);
            exit;
        }

        $_SESSION['client'] = [
            'id' => $id_client,
            'nom' => $nom,
            'prenom' => $prenom,
            'mail' => $mail
        ];
       

        echo json_encode(['success' => true, 'message' => 'Compte créé avec succès.']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Erreur serveur : ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Données incomplètes.']);
}
?>
