<?php
function getBD(){
    try {
        $bdd = new PDO('mysql:host=localhost;dbname=modaliza;charset=utf8', 'root', 'root');
        return $bdd;
    } catch (PDOException $e) {
        die('Erreur de connexion à la base de données : ' . $e->getMessage());
    }
}
?>
