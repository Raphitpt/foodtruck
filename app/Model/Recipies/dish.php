<?php 
require('./../../Config/config.local.php');


class Dish {

    public function create($dishData) {
        // Code pour créer un plat dans la base de données
        global $pdo; // Ajout de cette ligne si $pdo n'est pas déclaré ailleurs dans votre code
    
        $query = $pdo->prepare("INSERT INTO plats SET
            nom = :nom,
            composition = :composition,
            prix = :prix,
            id_categorie = :id_categorie");
            
        $query->execute([
            'nom' => $dishData['nom'],
            'composition' => $dishData['composition'],
            'prix' => $dishData['prix'],
            'id_categorie' => $dishData['id_categorie']
        ]);
    
        // Retournez l'ID de la dernière ligne insérée
        return $pdo->lastInsertId();
    }
    
    
    public function findAll() {
        // Code pour récupérer un plat dans la base de données
        global $pdo; // Ajout de cette ligne si $pdo n'est pas déclaré ailleurs dans votre code
    
        $query = $pdo->prepare("SELECT * FROM plats");
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
    
        // Retournez le résultat de la requête
        return $result;
    }
    



    public function update($dishData, $dishId) {
        // Code pour mettre à jour un plat dans la base de données
        global $pdo; // Ajout de cette ligne si $pdo n'est pas déclaré ailleurs dans votre code
    
        $query = $pdo->prepare("UPDATE plats SET
            nom = :nom,
            composition = :composition,
            prix = :prix,
            id_categorie = :id_categorie
            WHERE id_plat = :id_plat");
    
        $query->execute([
            'id_plat' => $dishId,
            'nom' => $dishData['nom'],
            'composition' => $dishData['composition'],
            'prix' => $dishData['prix'],
            'id_categorie' => $dishData['id_categorie']
        ]);
        return 'Le plat a bien été modifié';
    }
    
    
    public function delete($dishId) {
        // Code pour supprimer un plat dans la base de données
        global $pdo; // Ajout de cette ligne si $pdo n'est pas déclaré ailleurs dans votre code
    
        $query = $pdo->prepare("DELETE FROM plats WHERE id_plat = :id_plat");
        $query->execute([
            'id_plat' => $dishId
        ]);
        return true;
    }
    

}
