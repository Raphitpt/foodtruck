<?php

class Suppl {

    public function create($supplData) {
        // Code pour créer un supplément dans la base de données
        global $pdo; // Ajout de cette ligne si $pdo n'est pas déclaré ailleurs dans votre code
    
        $query = $pdo->prepare("INSERT INTO supplements SET
            nom = :nom,
            prix = :prix");
            
        $query->execute([
            'nom' => $supplData['nom'],
            'prix' => $supplData['prix']
        ]);
    
        // Retournez l'ID de la dernière ligne insérée
        return $pdo->lastInsertId();
    }
    
    
    public function findAll() {
        // Code pour récupérer un supplément dans la base de données
        global $pdo; // Ajout de cette ligne si $pdo n'est pas déclaré ailleurs dans votre code
    
        $query = $pdo->prepare("SELECT * FROM supplements");
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
    
        // Retournez le résultat de la requête
        return $result;
    }
    

    public function update($supplData, $supplId) {
        // Code pour mettre à jour un supplément dans la base de données
        global $pdo; // Ajout de cette ligne si $pdo n'est pas déclaré ailleurs dans votre code
    
        $query = $pdo->prepare("UPDATE supplements SET
            nom = :nom,
            prix = :prix
            WHERE id_suppl = :id_suppl");
    
        $query->execute([
            'id_suppl' => $supplId,
            'nom' => $supplData['nom'],
            'prix' => $supplData['prix']
        ]);
    }
    
    public function delete($supplId) {
        // Code pour supprimer un supplément dans la base de données
        global $pdo; // Ajout de cette ligne si $pdo n'est pas déclaré ailleurs dans votre code
    
        $query = $pdo->prepare("DELETE FROM supplements WHERE id_suppl = :id_suppl");
    
        $query->execute([
            'id_suppl' => $supplId
        ]);
    }
}
