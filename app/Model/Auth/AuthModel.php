<?php
require('config.local.php');

class Auth
{
    public function create($userData)
    {
        // Code pour insérer un nouvel utilisateur dans la base de données
        global $pdo; // Ajout de cette ligne si $pdo n'est pas déclaré ailleurs dans votre code

        $hash = password_hash($userData['password'], PASSWORD_DEFAULT);
        $query = $pdo->prepare("INSERT INTO users SET
        nom = :nom,
        prenom = :prenom,
        email = :email,
        passwd = :passwd,");
        $query->execute([ //Execute la requête
            'nom' => $userData['nom'],
            'prenom' => $userData['prenom'],
            'email' => $userData['email'],
            'passwd' => $hash,
        ]);
        return $pdo->lastInsertId(); //Retourne l'ID du dernier utilisateur inséré
    }

    public function find($userId)
    {
        // Code pour récupérer un utilisateur dans la base de données
        global $pdo; // Ajout de cette ligne si $pdo n'est pas déclaré ailleurs dans votre code

        $query = $pdo->prepare("SELECT * FROM users WHERE id = :id");
        $query->execute([
            'id' => $userId
        ]);
        return $query->fetch();
    }

    public function delete($userId)
    {
        // Code pour supprimer un utilisateur dans la base de données
        global $pdo; // Ajout de cette ligne si $pdo n'est pas déclaré ailleurs dans votre code

        $query = $pdo->prepare("DELETE FROM users WHERE id = :id");
        $query->execute([
            'id' => $userId
        ]);
    }

    public function update($userData)
    {
        // Code pour mettre à jour un utilisateur dans la base de données
        global $pdo; // Ajout de cette ligne si $pdo n'est pas déclaré ailleurs dans votre code

        $query = $pdo->prepare("UPDATE users SET
        nom = :nom,
        prenom = :prenom,
        email = :email,
        passwd = :passwd,
        WHERE id = :id");
        $query->execute([
            'id' => $userData['id'],
            'nom' => $userData['nom'],
            'prenom' => $userData['prenom'],
            'email' => $userData['email'],
            'passwd' => $userData['passwd'],
        ]);
    }



}
?>