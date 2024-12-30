<?php 
session_start();
include 'bd.php'; // Connexion à la base de données

$response = []; // Contient la réponse à retourner en JSON

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérifier que les champs nécessaires sont fournis
    if (isset($_POST['mail'], $_POST['mdp'], $_POST['csrf_token'])) {
        $mail = trim($_POST['mail']);
        $mdp = $_POST['mdp'];
        $csrf_token = $_POST['csrf_token'];

        // Vérification du token CSRF
        if (!hash_equals($_SESSION['csrf_token'] ?? '', $csrf_token)) {
            $response = [
                'success' => false,
                'message' => 'Token CSRF invalide.'
            ];
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }

        try {
            $bdd = getBD(); // Connexion à la base de données

            // Requête pour récupérer les informations du client
            $stmt = $bdd->prepare('SELECT id_client, nom, prenom, mail, mdp FROM clients WHERE mail = :mail');
            $stmt->execute([':mail' => $mail]);
            $client = $stmt->fetch();

            if ($client && password_verify($mdp, $client['mdp'])) {
                // Stocker les informations du client dans la session
                $_SESSION['client'] = [
                    'id' => $client['id_client'],
                    'nom' => $client['nom'],
                    'prenom' => $client['prenom'],
                    'mail' => $client['mail']
                ];

                // Générer un nouveau token CSRF pour les futures requêtes
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

                // Réponse de succès
                $response = [
                    'success' => true,
                    'message' => 'Connexion réussie'
                ];
            } else {
                // Identifiants incorrects
                $response = [
                    'success' => false,
                    'message' => 'Identifiants incorrects.'
                ];
            }
        } catch (Exception $e) {
            // Erreur serveur
            $response = [
                'success' => false,
                'message' => 'Erreur serveur : ' . $e->getMessage()
            ];
        }
    } else {
        // Champs non remplis ou CSRF manquant
        $response = [
            'success' => false,
            'message' => 'Veuillez remplir tous les champs ou token CSRF manquant.'
        ];
    }
} else {
    // Mauvaise méthode HTTP
    $response = [
        'success' => false,
        'message' => 'Méthode non autorisée.'
    ];
}

// Définir le type de réponse en JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
